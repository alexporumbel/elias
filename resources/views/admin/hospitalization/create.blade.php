@extends('admin.parts.layout')
@section('head')
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/dist/css/pignose.calendar.min.css">
    <style>
        .pignose-calendar .pignose-calendar-unit {
            font-weight: bold;
        }
    </style>
@endsection

@section('footer')
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/plugins/moment/locale/ro.js"></script>
    <script type="text/javascript" src="/assets/dist/js/pignose.calendar.full.min.js"></script>
    <script type="text/javascript">
        var today = moment().format('YYYY-MM-DD');
        var maxdate = moment().add(6, 'month').format('YYYY-MM-DD');

        function showCalendar(min, max) {
            $('.calendar').pignoseCalendar({
                // date format follows moment sheet.
                // Check below link.
                // https://momentjs.com/docs/#/parsing/string-format/
                format: 'YYYY-MM-DD',
                date: moment(),
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
                select: function (date, context) {
                    $('#selectedDate').val(date[0]._i);
                },

            });
        }


        showCalendar(today, maxdate);


    </script>
@endsection



@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Adauga programare spitalizare</h1>
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
                            <form id="userform" method="POST" action="{{ route('hospitalization.store') }}">
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
                                        <label>Medic</label>
                                        <select name="medic" class="custom-select medic @error('medic') is-invalid @enderror">
                                            <option>Selecteaza medic</option>
                                            @foreach($medics as $medic)
                                                <option value="{{ $medic->user->id }}">{{ $medic->user->name.' '.$medic->user->lname }}</option>
                                            @endforeach
                                        </select>
                                        @error('medic')
                                        <p class="error invalid-feedback">{{ $errors->first('medic') }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Selecteaza data</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="calendar">
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
                                            <input type="radio" name="appointmentType" value="revenire"
                                                   id="revenire">
                                            <label for="revenire">
                                                Recomandare de revenire
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
                                        <label class="d-block" for="tipspitalizare">Tip spitalizare</label>
                                        <div class="icheck-danger">
                                            <input type="radio" name="hospitalizationType" value="zi" checked
                                                   id="zi">
                                            <label for="zi">
                                                Spitalizare de zi
                                            </label>
                                        </div>
                                        <div class="icheck-danger">
                                            <input type="radio" name="hospitalizationType" value="continua"
                                                   id="continua">
                                            <label for="continua">
                                                Spitalizare continua (>24h)
                                            </label>
                                        </div>
                                        @error('hospitalizationType')
                                        <p class="error invalid-feedback">{{ $errors->first('hospitalizationType') }}</p>
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
