<?php


namespace App;


use App\Models\Ambulatory;
use phpDocumentor\Reflection\Types\Null_;

class Availability
{

    /**
     * Get the available hours of a provider.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $service Service record.
     * @param array $provider Provider record.
     *
     * @return array
     *
     * @throws Exception
     */
    public function get_available_hours($date, $service, $provider)
    {
        $available_periods = $this->get_available_periods($date, $provider);


        $available_hours = $this->generate_available_hours($date, $service, $available_periods);

        return $this->consider_book_advance_timeout($date, $available_hours, $provider);
    }

    /**
     * Get an array containing the free time periods (start - end) of a selected date.
     *
     * This method is very important because there are many cases where the system needs to know when a provider is
     * available for an appointment. This method will return an array that belongs to the selected date and contains
     * values that have the start and the end time of an available time period.
     *
     * @param string $date Select date string.
     * @param array $provider Provider record.
     *
     * @return array Returns an array with the available time periods of the provider.
     *
     * @throws Exception
     */
    protected function get_available_periods(
        $date,
        $provider
    )
    {
        // Get the service, provider's working plan and provider appointments.
        $working_plan = json_decode($provider['settings']['working_plan'], TRUE);


        $appointments = Ambulatory::where('user_provider_id', $provider['id'])->get()->toArray();

        // Find the empty spaces on the plan. The first split between the plan is due to a break (if any). After that
        // every reserved appointment is considered to be a taken space in the plan.
        $working_day = strtolower(date('l', strtotime($date)));

        $date_working_plan = $working_plan[$working_day] ?? NULL;
if($working_plan[$working_day] == NULL){
    $periods = [];
}else{

        $periods[] = [
            'start' => $date_working_plan['start'],
            'end' => $date_working_plan['end']
        ];
}

        if (isset($date_working_plan['breaks']))
        {
            $periods[] = [
                'start' => $date_working_plan['start'],
                'end' => $date_working_plan['end']
            ];

            $day_start = new \DateTime($date_working_plan['start']);
            $day_end = new \DateTime($date_working_plan['end']);

            // Split the working plan to available time periods that do not contain the breaks in them.
            foreach ($date_working_plan['breaks'] as $index => $break)
            {
                $break_start = new \DateTime($break['start']);
                $break_end = new \DateTime($break['end']);

                if ($break_start < $day_start)
                {
                    $break_start = $day_start;
                }

                if ($break_end > $day_end)
                {
                    $break_end = $day_end;
                }

                if ($break_start >= $break_end)
                {
                    continue;
                }

                foreach ($periods as $key => $period)
                {
                    $period_start = new \DateTime($period['start']);
                    $period_end = new \DateTime($period['end']);

                    $remove_current_period = FALSE;

                    if ($break_start > $period_start && $break_start < $period_end && $break_end > $period_start)
                    {
                        $periods[] = [
                            'start' => $period_start->format('H:i'),
                            'end' => $break_start->format('H:i')
                        ];

                        $remove_current_period = TRUE;
                    }

                    if ($break_start < $period_end && $break_end > $period_start && $break_end < $period_end)
                    {
                        $periods[] = [
                            'start' => $break_end->format('H:i'),
                            'end' => $period_end->format('H:i')
                        ];

                        $remove_current_period = TRUE;
                    }

                    if ($break_start == $period_start && $break_end == $period_end)
                    {
                        $remove_current_period = TRUE;
                    }

                    if ($remove_current_period)
                    {
                        unset($periods[$key]);
                    }
                }
            }
        }

        // Break the empty periods with the reserved appointments.
        foreach ($appointments as $appointment)
        {
            foreach ($periods as $index => &$period)
            {
                $appointment_start = new \DateTime($appointment['start_datetime']);
                $appointment_end = new \DateTime($appointment['end_datetime']);

                if ($appointment_start >= $appointment_end)
                {
                    continue;
                }

                $period_start = new \DateTime($date . ' ' . $period['start']);
                $period_end = new \DateTime($date . ' ' . $period['end']);

                if ($appointment_start <= $period_start && $appointment_end <= $period_end && $appointment_end <= $period_start)
                {
                    // The appointment does not belong in this time period, so we  will not change anything.
                    continue;
                }
                else
                {
                    if ($appointment_start <= $period_start && $appointment_end <= $period_end && $appointment_end >= $period_start)
                    {
                        // The appointment starts before the period and finishes somewhere inside. We will need to break
                        // this period and leave the available part.
                        $period['start'] = $appointment_end->format('H:i');
                    }
                    else
                    {
                        if ($appointment_start >= $period_start && $appointment_end < $period_end)
                        {
                            // The appointment is inside the time period, so we will split the period into two new
                            // others.
                            unset($periods[$index]);

                            $periods[] = [
                                'start' => $period_start->format('H:i'),
                                'end' => $appointment_start->format('H:i')
                            ];

                            $periods[] = [
                                'start' => $appointment_end->format('H:i'),
                                'end' => $period_end->format('H:i')
                            ];
                        }
                        else if ($appointment_start == $period_start && $appointment_end == $period_end)
                        {
                            unset($periods[$index]); // The whole period is blocked so remove it from the available periods array.
                        }
                        else
                        {
                            if ($appointment_start >= $period_start && $appointment_end >= $period_start && $appointment_start <= $period_end)
                            {
                                // The appointment starts in the period and finishes out of it. We will need to remove
                                // the time that is taken from the appointment.
                                $period['end'] = $appointment_start->format('H:i');
                            }
                            else
                            {
                                if ($appointment_start >= $period_start && $appointment_end >= $period_end && $appointment_start >= $period_end)
                                {
                                    // The appointment does not belong in the period so do not change anything.
                                    continue;
                                }
                                else
                                {
                                    if ($appointment_start <= $period_start && $appointment_end >= $period_end && $appointment_start <= $period_end)
                                    {
                                        // The appointment is bigger than the period, so this period needs to be removed.
                                        unset($periods[$index]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return array_values($periods);
    }


    /**
     * Calculate the available appointment hours.
     *
     * Calculate the available appointment hours for the given date. The empty spaces
     * are broken down to 15 min and if the service fit in each quarter then a new
     * available hour is added to the "$available_hours" array.
     *
     * @param string $date Selected date (Y-m-d).
     * @param array $service Service record.
     * @param array $empty_periods Empty periods as generated by the "get_provider_available_time_periods"
     * method.
     *
     * @return array Returns an array with the available hours for the appointment.
     *
     * @throws Exception
     */
    protected function generate_available_hours(
        $date,
        $service,
        $empty_periods
    )
    {
        $service['duration'] = 20;
        $available_hours = [];

        foreach ($empty_periods as $period)
        {
            $start_hour = new \DateTime($date . ' ' . $period['start']);
            $end_hour = new \DateTime($date . ' ' . $period['end']);
            $interval = $service['duration'];

            $current_hour = $start_hour;
            $diff = $current_hour->diff($end_hour);

            while (($diff->h * 60 + $diff->i) >= (int)$service['duration'])
            {
                $available_hours[] = $current_hour->format('H:i');
                $current_hour->add(new \DateInterval('PT' . $interval . 'M'));
                $diff = $current_hour->diff($end_hour);
            }
        }

        return $available_hours;
    }


    /**
     * Consider the book advance timeout and remove available hours that have passed the threshold.
     *
     * If the selected date is today, remove past hours. It is important  include the timeout before booking
     * that is set in the back-office the system. Normally we might want the customer to book an appointment
     * that is at least half or one hour from now. The setting is stored in minutes.
     *
     * @param string $selected_date The selected date.
     * @param array $available_hours Already generated available hours.
     * @param array $provider Provider information.
     *
     * @return array Returns the updated available hours.
     *
     * @throws Exception
     */
    protected function consider_book_advance_timeout($selected_date, $available_hours, $provider)
    {
        $provider_timezone = new \DateTimeZone('Europe/Bucharest');

        $book_advance_timeout = '30';

        $threshold = new \DateTime('+' . $book_advance_timeout . ' minutes', $provider_timezone);

        foreach ($available_hours as $index => $value)
        {
            $available_hour = new \DateTime($selected_date . ' ' . $value, $provider_timezone);

            if ($available_hour->getTimestamp() <= $threshold->getTimestamp())
            {
                unset($available_hours[$index]);
            }
        }

        $available_hours = array_values($available_hours);
        sort($available_hours, SORT_STRING);
        return array_values($available_hours);
    }
}
