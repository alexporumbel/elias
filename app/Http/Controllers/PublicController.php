<?php

namespace App\Http\Controllers;

use App\Availability;
use App\Models\MedicalSpeciality;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Http\Request;

class PublicController extends Controller
{

    public function get_available_hours_ajax(Availability $availability)
    {
        return $this->get_available_hours(request('medic_id'), request('service_id'), request('date'), $availability);
    }

    public function get_available_hours($medic_id, $service_id, $date, Availability $availability)
    {
        $provider = User::where('id', $medic_id)->first()->toArray();
        $provider['services'] = [$service_id];
        $provider['settings'] = UserSettings::where('user_id', $medic_id)->first()->toArray();
        unset($provider['settings']['user_id']);
        $service = MedicalSpeciality::where('id', $service_id)->first()->toArray();
        return $availability->get_available_hours($date, $service, $provider);
    }

    public function get_first_appointment(Availability $availability)
    {
        $date = $this->get_first_available_date(request('medic_id'), request('service_id'), $availability);
        if(request('medic_id') === 'any') {
            $medics = UserSettings::where('speciality_id', request('service_id'))->get();
        }else{
            $medics = UserSettings::where('user_id', request('medic_id'))->get();
        }

        $data = [];
        foreach ($medics as $medic) {
            $schedule = $this->get_available_hours($medic->user_id, request('service_id'), $date, $availability);
            if (!empty($schedule)) {
                $data['medic'] = $medic->user->name . ' ' . $medic->user->lname;
                $data['medicId'] = $medic->user->id;
                $data['schedule'] = $schedule[0];
                $data['date'] = $date;
                break;
            }
        }

        return $data;
    }

    protected function search_providers_by_service($service_id)
    {
        $available_providers = UserSettings::where('speciality_id', $service_id)->get()->toArray();
        $provider_list = [];

        foreach ($available_providers as $provider) {

            // Check if the provider is affected to the selected service.
            $provider_list[] = $provider['user_id'];
        }

        return $provider_list;
    }

    public function ajax_get_unavailable_dates(Availability $availability)
    {
        $provider_id = request('medic_id');
        $service_id = request('service_id');
        $selected_date_string = request('date');
        $selected_date = new \DateTime($selected_date_string);
        $number_of_days_in_month = (int)$selected_date->format('t');
        $unavailable_dates = [];

        $provider_ids = $provider_id === 'any'
            ? $this->search_providers_by_service($service_id)
            : [$provider_id];


        for ($i = 1; $i <= $number_of_days_in_month; $i++) {
            $current_date = new \DateTime($selected_date->format('Y-m') . '-' . $i);

            if ($current_date < new \DateTime(date('Y-m-d 00:00:00'))) {
                // Past dates become immediately unavailable.
                $unavailable_dates[] = $current_date->format('Y-m-d');
                continue;
            }

            // Finding at least one slot of availability.
            foreach ($provider_ids as $current_provider_id) {

                $available_hours = $this->get_available_hours($current_provider_id, $service_id, $current_date->format('Y-m-d'), $availability);


                if (!empty($available_hours)) {
                    break;
                }
            }

            // No availability amongst all the provider.
            if (empty($available_hours)) {
                $unavailable_dates[] = $current_date->format('Y-m-d');
            }
        }

        return $unavailable_dates;
    }

    public function get_first_available_date($provider_id, $service_id, Availability $availability)
    {
        $provider_ids = $provider_id === 'any'
            ? $this->search_providers_by_service($service_id)
            : [$provider_id];

        $current_date = new \DateTime();
        while(true){
            foreach ($provider_ids as $current_provider_id) {
                $available_hours = $this->get_available_hours($current_provider_id, $service_id, $current_date->format('Y-m-d'), $availability);
                if (!empty($available_hours)) {
                    return $current_date->format('Y-m-d');
                }
            }
            $current_date = $current_date->add(new \DateInterval('P1D'));
            }
    }

    public function check_speciality_ajax()
    {
        $medics = UserSettings::where('speciality_id', request('service_id'))->get();
        $data['is_paid'] = MedicalSpeciality::where('id', request('service_id'))->first()->is_paid;
        $i = 0;
        foreach ($medics as $medic) {
            $data['medics'][$i]['id'] = $medic->user->id;
            $data['medics'][$i]['name'] = $medic->user->name . ' ' . $medic->user->lname;
            $i++;
        }
        return $data;

    }

}



