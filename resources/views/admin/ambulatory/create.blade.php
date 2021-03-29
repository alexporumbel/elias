@extends('admin.parts.layout')
@section('head')
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css"  href="/assets/dist/css/pignose.calendar.min.css">
@endsection

@section('footer')
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/dist/js/pignose.calendar.full.min.js"></script>
    <script type="text/javascript" >
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.speciality').on('change', function() {
            $.post( "/getMedics", {'service_id': this.value})
                .done(function( data ) {
                    var decodedData = data;
                    var paid = decodedData['is_paid'];
                    console.log('paid ' + paid);
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

                    var medics ='<option>Selecteaza medic</option>';
                    if(decodedData['medics']) {
                        $.each(decodedData['medics'], function (key, value) {
                            medics += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $.post( "/specialityApi", {'service_id': this.value})
                            .done(function( data ) {

                            });
                    }
                    $('.medic').html(medics);
                });
        });

        function showCalendar(min, max, selected, disabledDates) {
            $('.calendar').pignoseCalendar({
                // date format follows moment sheet.
                // Check below link.
                // https://momentjs.com/docs/#/parsing/string-format/
                format: 'YYYY-MM-DD',
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
                    console.log(date[0]._i);
                }
            });
            if(selected != null){
                $('.calendar').pignoseCalendar('set', selected);
            }
        }

        var today = moment().format('YYYY-MM-DD');
        var maxdate = moment().add(3, 'month').format('YYYY-MM-DD');
        showCalendar(today, maxdate, null, []);


    </script>
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Adauga programare</h1>
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
                            <form id="userform" method="POST" action="{{ route('user.store') }}">
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
                                        <select name="speciality" class="custom-select speciality">
                                            <option>Selecteaza specialitate</option>
                                            @foreach($specialities as $speciality)
                                                <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Medic</label>
                                        <select name="medic" class="custom-select medic">
                                            <option>Selecteaza medic</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Selecteaza data</label>
                                        <div class="calendar"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block" for="tipprezentare">Tip prezentare</label>
                                        <div class="icheck-danger rtrim">
                                            <input type="radio" name="tip_prezentare" value="trimitere" checked
                                                   id="trimitere">
                                            <label for="trimitere">
                                                Bilet de trimitere
                                            </label>
                                        </div>
                                        <div class="icheck-danger rcronic">
                                            <input type="radio" name="tip_prezentare" value="cronic"
                                                   id="cronic">
                                            <label for="cronic">
                                                Afectiune cronica
                                            </label>
                                        </div>
                                        <div class="icheck-danger rcontrol">
                                            <input type="radio" name="tip_prezentare" value="control"
                                                   id="control">
                                            <label for="control">
                                                Consultatie de control
                                            </label>
                                        </div>
                                        <div class="icheck-danger">
                                            <input type="radio" name="tip_prezentare" value="plata"
                                                   id="plata">
                                            <label for="plata">
                                                Cu plata
                                            </label>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label>Observatii(optional)</label>
                                        <textarea class="form-control" name="notes" rows="3" placeholder="Scrie motivul programarii"></textarea>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </form>
                            <div class="card-footer">
                                <button class="btn btn-primary submit">Salveaza</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection
