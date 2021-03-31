@extends('admin.parts.layout')
@section('head')
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/dist/css/pignose.calendar.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/plugins/sweetalert2/sweetalert2.min.css">
    <style>
        .slot {
            margin: 10px;
        }

        .slot-list {
            overflow: auto;
            height: 523px;
        }
        .pignose-calendar .pignose-calendar-unit {
            font-weight: bold;
        }
    </style>
@endsection

@section('footer')
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/plugins/moment/locale/ro.js"></script>
    <script type="text/javascript" src="/assets/dist/js/pignose.calendar.full.min.js"></script>
    <script src="/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var serviceId;
        var selectedDate;
        var medicId;
        var today = moment().format('YYYY-MM-DD');
        var maxdate = moment().add(3, 'month').format('YYYY-MM-DD');

        function translateDay(value) {
            switch (value) {
                case 'Sunday':
                    return 'Duminica';
                case 'Monday':
                    return 'Luni';
                case 'Tuesday':
                    return 'Marti';
                case 'Wednesday':
                    return 'Miercuri';
                case 'Thursday':
                    return 'Joi';
                case 'Friday':
                    return 'Vineri';
                case 'Saturday':
                    return 'Sambata';
            }
        };

        $(document).on('click', '.time-button', function () {
            var selectedTime = $(this).attr("data-value");
            $('.time-button').removeClass('btn-primary').addClass('btn-default');
            $(this).removeClass('btn-default').addClass('btn-primary');
            $('#selectedDate').val(selectedDate +' '+selectedTime);
        });

        function selectTime(time) {
            $('.time-button').removeClass('btn-primary').addClass('btn-default');
            $('.time-button[data-value="'+ time + '"]').removeClass('btn-default').addClass('btn-primary');
            $('#selectedDate').val(selectedDate +' '+time);
        }


        $('.speciality').on('change', function () {
            serviceId = this.value;
            if (serviceId.length < 4) {

                $.post("/api/getMedics", {'service_id': serviceId})
                    .done(function (decodedData) {
                        var paid = decodedData['is_paid'];
                        if (paid === 1) {
                            $('#trimitere').prop('disabled', true).prop('checked', false);
                            $('#control').prop('disabled', true).prop('checked', false);
                            $('#cronic').prop('disabled', true).prop('checked', false);
                            $('#plata').prop('checked', true);
                        } else {
                            $('#trimitere').prop('disabled', false).prop('checked', false);
                            $('#control').prop('disabled', false).prop('checked', false);
                            $('#cronic').prop('disabled', false).prop('checked', false);
                            $('#plata').prop('checked', false);
                        }
                        Swal.fire({
                            title: 'Te rog asteapta!',
                            html: 'Caut primul loc disponibil',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });

                        var medics = '<option>Selecteaza medic</option>';
                        if (decodedData['medics']) {
                            $.each(decodedData['medics'], function (key, value) {
                                medics += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                            $.post("/api/getUnavailDates", {'service_id': serviceId, 'medic_id': 'any', 'date': today})
                                .done(function (unavailDates) {
                                    showCalendar(today, maxdate, unavailDates);

                                    $.post("/api/getFirstAppointment", {'service_id': serviceId, 'medic_id': 'any'})
                                        .done(function (appointmentData) {
                                            var day = moment(appointmentData['date']).format('dddd');
                                            swal.close();
                                            Swal.fire({
                                                title: 'Esti de acord?',
                                                text: "Am gasit un loc disponibil " + translateDay(day) + " " + appointmentData['date'] + " la ora " + appointmentData['schedule'] + " la Dr. " + appointmentData['medic'],
                                                icon: 'warning',
                                                showCancelButton: true,
                                                allowOutsideClick: false,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Programeaza!',
                                                cancelButtonText: 'Nu, multumesc!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    var availdiv = '';
                                                    $('.medic option[value="' + appointmentData['medicId'] + '"]').prop('selected', true);
                                                    medicId = appointmentData['medicId'];
                                                    $.post("/api/getAvailHours", {
                                                        'service_id': serviceId,
                                                        'medic_id': appointmentData['medicId'],
                                                        "date": appointmentData['date']
                                                    }).done(function (availHours) {
                                                            $.each(availHours, function (key, value) {
                                                                availdiv += '<div class="slot">\
                                                        <button class="btn btn-block btn-default btn-lg time-button" id="update" type="button" data-value="' + value + '">\
                                                        <span class="time-button-title">' + value + '</span>\
                                                </button>\
                                                </div>';
                                                            });
                                                            $('.slot-list').html(availdiv);
                                                        $('.calendar').pignoseCalendar('set', appointmentData['date']);
                                                        selectedDate = appointmentData['date'];
                                                        selectTime(appointmentData['schedule']);
                                                        });
                                                }
                                            })

                                        });

                                });
                        } else {
                            setTimeout(function () {
                                swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Nu exista medici pentru aceasta specialitate!',
                                    footer: '<a href="{{ route('user.create') }}">Adauga un medic</a>'
                                });
                            }, 1000);
                            $('.slot-list').html('');
                            $('#selectedDate').val('');
                            showCalendar(today, today, [today]);
                        }
                        $('.medic').html(medics);
                    });
            }
        });

        $('.medic').on('change', function () {
            medicId = this.value;
            if (medicId.length < 4) {
                Swal.fire({
                    title: 'Te rog asteapta!',
                    html: 'Caut primul loc disponibil',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                $.post("/api/getUnavailDates", {'service_id': serviceId, 'medic_id': medicId, 'date': today})
                    .done(function (unavailDates) {
                        showCalendar(today, maxdate, unavailDates);

                        $.post("/api/getFirstAppointment", {'service_id': serviceId, 'medic_id': medicId})
                            .done(function (appointmentData) {
                                var day = moment(appointmentData['date']).format('dddd');
                                swal.close();
                                Swal.fire({
                                    title: 'Esti de acord?',
                                    text: "Am gasit un loc disponibil " + translateDay(day) + " " + appointmentData['date'] + " la ora " + appointmentData['schedule'] + " la Dr. " + appointmentData['medic'],
                                    icon: 'warning',
                                    showCancelButton: true,
                                    allowOutsideClick: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Programeaza!',
                                    cancelButtonText: 'Nu, multumesc!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        var availdiv = '';
                                        $.post("/api/getAvailHours", {
                                            'service_id': serviceId,
                                            'medic_id': medicId,
                                            "date": appointmentData['date']
                                        })
                                            .done(function (availHours) {
                                                $.each(availHours, function (key, value) {
                                                    availdiv += '<div class="slot">\
                                                        <button class="btn btn-block btn-default btn-lg time-button" id="update" type="button" data-value="' + value + '">\
                                                        <span class="time-button-title">' + value + '</span>\
                                                </button>\
                                                </div>';
                                                });
                                                $('.slot-list').html(availdiv);
                                                $('.calendar').pignoseCalendar('set', appointmentData['date']);
                                                selectedDate = appointmentData['date'];
                                                selectTime(appointmentData['schedule']);
                                            });
                                    }
                                })

                            });

                    });
            }

        });

        function showCalendar(min, max, disabledDates, initDate = null) {
            if(initDate == null){
                initDate = moment();
            }
            $('.calendar').pignoseCalendar({
                // date format follows moment sheet.
                // Check below link.
                // https://momentjs.com/docs/#/parsing/string-format/
                format: 'YYYY-MM-DD',
                date: initDate,
                initialize: false,
                minDate: min,
                maxDate: max,
                // Starting day of week. (0 is Sunday[default], 6 is Saturday
                // and all day of week is in consecutive order.
                // In this example, We will start from Saturday.
                week: 1,
                weeks: [
                    'Du',
                    'Lu',
                    'Ma',
                    'Mi',
                    'Jo',
                    'Vi',
                    'Sa'
                ],
                monthsLong: [
                    'Ianuarie',
                    'Februarie',
                    'Martie',
                    'Aprilie',
                    'Mai',
                    'Iunie',
                    'Iulie',
                    'August',
                    'Septembrie',
                    'Octombrie',
                    'Noiembrie',
                    'Decembrie'
                ],
                months: [
                    'Ian',
                    'Feb',
                    'Mar',
                    'Apr',
                    'Mai',
                    'Iun',
                    'Iul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Noi',
                    'Dec'
                ],
                disabledDates: disabledDates,
                select: function (date, context) {
                    /**
                     * @params this Element
                     * @params date moment[]
                     * @params context PignoseCalendarContext
                     * @returns void
                     */

                        // This is selected button Element.
                    var $this = $(this);

                    // You can get target element in `context` variable, This element is same `$(this)`.
                    var $element = context.element;

                    // You can also get calendar element, It is calendar view DOM.
                    var $calendar = context.calendar;

                    // Selected dates (start date, end date) is passed at first parameter, And this parameters are moment type.
                    // If you unselected date, It will be `null`.
                    selectedDate = date[0]._i;
                    $('#selectedDate').val();
                    var availdiv = '';
                    $.post("/api/getAvailHours", {
                        'service_id': serviceId,
                        'medic_id': medicId,
                        "date": selectedDate
                    })
                        .done(function (availHours) {
                            $.each(availHours, function (key, value) {
                                availdiv += '<div class="slot">\
                                                        <button class="btn btn-block btn-default btn-lg time-button" id="update" type="button" data-value="' + value + '">\
                                                        <span class="time-button-title">' + value + '</span>\
                                                </button>\
                                                </div>';
                            });
                            $('.slot-list').html(availdiv);
                            $('.calendar').pignoseCalendar('set', selectedDate);
                        });
                },
                page: function(info, context) {
                    /**
                     * @params context PignoseCalendarPageInfo
                     * @params context PignoseCalendarContext
                     * @returns void
                     */
                        // This is clicked arrow button element.
                    var $this = $(this);
                    if($('.speciality').find(":selected").val().length < 4 && $('.medic').find(":selected").val().length < 4){
                        if(info.type === 'next') {
                        if(moment(info.year+'-'+info.month).isBefore(moment().add(4, 'month').format('YYYY-MM'), 'month')) {
                            $.post("/api/getUnavailDates", {
                                'service_id': $('.speciality').find(":selected").val(),
                                'medic_id': $('.medic').find(":selected").val(),
                                "date": moment(info.year+'-'+info.month+'-01').format('YYYY-MM-DD')
                            })
                                .done(function (unavailDates) {
                                    showCalendar(today, maxdate, unavailDates, moment(info.year+'-'+info.month+'-01'));
                                });
                        }
                        }else if(info.type === 'prev') {
                            if(moment(info.year+'-'+info.month).isAfter(moment().subtract(1, 'month').format('YYYY-MM'), 'month') && moment(info.year+'-'+info.month).isBefore(moment().add(4, 'month').format('YYYY-MM'), 'month')) {
                                $.post("/api/getUnavailDates", {
                                    'service_id': $('.speciality').find(":selected").val(),
                                    'medic_id': $('.medic').find(":selected").val(),
                                    "date": moment(info.year+'-'+info.month+'-01').format('YYYY-MM-DD')
                                })
                                    .done(function (unavailDates) {
                                        showCalendar(today, maxdate, unavailDates, moment(info.year+'-'+info.month+'-01'));
                                    });
                            }
                        }
                    }
                }
            });
        }


        showCalendar(today, today, [today]);


    </script>
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Adauga programare ambulator</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 ">
                        <span class="breadcrumb float-sm-right"><a class="btn btn-block btn-primary"
                                                                   href="{{ url()->previous() }}">Inapoi</a></span>

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <div class="col-6 offset-3">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Completeaza datele</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="userform" method="POST" action="{{ route('ambulatory.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Nume</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                               value="{{ old('name') }}" name="name" id="name">
                                        @error('name')
                                        <p class="error invalid-feedback">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="lname">Prenume</label>
                                        <input class="form-control @error('lname') is-invalid @enderror" type="text"
                                               value="{{ old('lname') }}" name="lname" id="lname">
                                        @error('lname')
                                        <p class="error invalid-feedback">{{ $errors->first('lname') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="text"
                                               value="{{ old('email') }}" name="email" id="email">
                                        @error('email')
                                        <p class="error invalid-feedback">{{ $errors->first('email') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Telefon</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                               value="{{ old('phone') }}" name="phone" id="phone">
                                        @error('phone')
                                        <p class="error invalid-feedback">{{ $errors->first('phone') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Specialitate</label>
                                        <select name="speciality" class="custom-select speciality form-control @error('speciality') is-invalid @enderror">
                                            <option>Selecteaza specialitate</option>
                                            @foreach($specialities as $speciality)
                                                <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('speciality')
                                        <p class="error invalid-feedback">{{ $errors->first('speciality') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Medic</label>
                                        <select name="medic" class="custom-select medic @error('medic') is-invalid @enderror">
                                            <option>Selecteaza medic</option>
                                        </select>
                                        @error('medic')
                                        <p class="error invalid-feedback">{{ $errors->first('medic') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Selecteaza data</label>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="calendar">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="slot-list">

                                                </div>
                                            </div>

                                        </div>
                                        <input type="hidden" name="selectedDate" id="selectedDate">
                                        @error('selectedDate')
                                        <p class="error invalid-feedback d-block">{{ $errors->first('selectedDate') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block" for="tipprezentare">Tip prezentare</label>
                                        <div class="icheck-danger rtrim">
                                            <input type="radio" name="appointmentType" value="trimitere" checked
                                                   id="trimitere">
                                            <label for="trimitere">
                                                Bilet de trimitere
                                            </label>
                                        </div>
                                        <div class="icheck-danger rcronic">
                                            <input type="radio" name="appointmentType" value="cronic"
                                                   id="cronic">
                                            <label for="cronic">
                                                Afectiune cronica
                                            </label>
                                        </div>
                                        <div class="icheck-danger rcontrol">
                                            <input type="radio" name="appointmentType" value="control"
                                                   id="control">
                                            <label for="control">
                                                Consultatie de control
                                            </label>
                                        </div>
                                        <div class="icheck-danger">
                                            <input type="radio" name="appointmentType" value="plata"
                                                   id="plata">
                                            <label for="plata">
                                                Cu plata
                                            </label>
                                        </div>
                                        @error('appointmentType')
                                        <p class="error invalid-feedback">{{ $errors->first('appointmentType') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Observatii(optional)</label>
                                        <textarea class="form-control" name="notes" rows="3"
                                                  placeholder="Scrie motivul programarii"></textarea>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button class="btn btn-primary" type="submit">Salveaza</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection
