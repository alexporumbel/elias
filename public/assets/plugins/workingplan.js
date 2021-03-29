$(function () {


    function getWeekDayName(weekDayId) {
        var result;

        switch (weekDayId) {

            case 0:
                result = 'sunday';
                break;

            case 1:
                result = 'monday';
                break;

            case 2:
                result = 'tuesday';
                break;

            case 3:
                result = 'wednesday';
                break;

            case 4:
                result = 'thursday';
                break;

            case 5:
                result = 'friday';
                break;

            case 6:
                result = 'saturday';
                break;

            default:
                throw new Error('Invalid weekday Id provided!', weekDayId);
        }

        return result;
    };

    function getWeekDayId(weekDayName) {
        var result;

        switch (weekDayName.toLowerCase()) {

            case 'sunday':
            case 'sun':
                result = 0;
                break;

            case 'monday':
            case 'mon':
                result = 1;
                break;

            case 'tuesday':
            case 'tue':
                result = 2;
                break;

            case 'wednesday':
            case 'wed':
                result = 3;
                break;

            case 'thursday':
            case 'thu':
                result = 4;
                break;

            case 'friday':
            case 'fri':
                result = 5;
                break;

            case 'saturday':
            case 'sat':
                result = 6;
                break;

            default:
                throw new Error('Invalid weekday name provided!', weekDayName);
        }

        return result;
    };

    function sortWeekDictionary(weekDictionary, startDayId) {
        var sortedWeekDictionary = {};

        for (var i = startDayId; i < startDayId + 7; i++) {
            var weekdayName = getWeekDayName(i % 7);
            sortedWeekDictionary[weekdayName] = weekDictionary[weekdayName];
        }

        return sortedWeekDictionary;
    };

    workdays = {
        // "sunday": 0, // << if sunday is first day of week
        "monday": 1,
        "tuesday": 2,
        "wednesday": 3,
        "thursday": 4,
        "friday": 5,
        "saturday": 6,
        "sunday": 7
    }

    function convertValueToDay (value) {
        switch (value) {
            case 'sunday':
                return 'Duminica';
            case 'monday':
                return 'Luni';
            case 'tuesday':
                return 'Marti';
            case 'wednesday':
                return 'Miercuri';
            case 'thursday':
                return 'Joi';
            case 'friday':
                return 'Vineri';
            case 'saturday':
                return 'Sambata';
        }
    };

    var weekDayId = getWeekDayId("monday");
    var workingPlanSorted = sortWeekDictionary(workdays, weekDayId);

    //$('.working-plan tbody').empty();
   // $('.breaks tbody').empty();

    // Build working plan day list starting with the first weekday as set in the General settings
    var timeFormat = "regular";

    $.each(workingPlanSorted, function (index, workingDay) {
        if($('#working_plan').val() !==''){
            var schedule = jQuery.parseJSON($('#working_plan').val());

        // var day = convertValueToDay(index);
        //
        // var dayDisplayName = day;
        //
        // $('<tr/>', {
        //     'html': [
        //         $('<td/>', {
        //             'html': [
        //                 $('<div/>', {
        //                     'class': 'icheck-primary form-check',
        //                     'html': [
        //                         $('<input/>', {
        //                             'class': 'form-check-input',
        //                             'type': 'checkbox',
        //                             'id': index
        //                         }),
        //                         $('<label/>', {
        //                             'class': 'form-check-label',
        //                             'text': dayDisplayName,
        //                             'for': index
        //                         }),
        //
        //                     ]
        //                 })
        //             ]
        //         }),
        //         $('<td/>', {
        //             'html': [
        //                 $('<input/>', {
        //                     'id': index + '-start',
        //                     'class': 'work-start form-control input-sm'
        //                 })
        //             ]
        //         }),
        //         $('<td/>', {
        //             'html': [
        //                 $('<input/>', {
        //                     'id': index + '-end',
        //                     'class': 'work-start form-control input-sm',
        //                 })
        //             ]
        //         })
        //     ]
        // })
        //     .appendTo('.working-plan tbody');

        if (schedule[index] != null) {
            $('#' + index).prop('checked', true);
            $('#' + index + '-start').val(schedule[index]['start']);
            $('#' + index + '-end').val(schedule[index]['end']);
        } else {
            $('#' + index).prop('checked', false);
            $('#' + index + '-start').prop('disabled', true).val('');
            $('#' + index + '-end').prop('disabled', true).val('');
        }
        }
    });

    $('.working-plan tbody').on('click', 'input:checkbox', function () {
        var id = $(this).attr('id');

        if ($(this).prop('checked') === true) {
            $('#' + id + '-start').prop('disabled', false).val('08:00');
            $('#' + id + '-end').prop('disabled', false).val('16:00');
        } else {
            $('#' + id + '-start').prop('disabled', true).val('');
            $('#' + id + '-end').prop('disabled', true).val('');
        }
    });

    function saveWorkingPlan() {

        var workingPlan = {};

        $('.working-plan input:checkbox').each(function (index, checkbox) {
            var id = $(checkbox).attr('id');
            if ($(checkbox).prop('checked') === true) {
                workingPlan[id] = {
                    start: $('#' + id + '-start').val(),
                    end: $('#' + id + '-end').val(),
                };
            } else {
                workingPlan[id] = null;
            }
        });
          $('#working_plan').val(JSON.stringify(workingPlan));
          console.log(JSON.stringify(workingPlan));
    }

    $('.submit').on('click', function() {
        if($("#medic").is(':checked')) {
            saveWorkingPlan();
        }
        $('#userform').submit();
    });



});
