@extends('admin.parts.layout')
@section('head')

    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/plugins/timepicker/css/timepicker.css">
    <style>
        .work-start {
            max-width: 88px;
        }
    </style>
@endsection

@section('footer')
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/assets/plugins/timepicker/js/timepicker.js"></script>
    <script src="/assets/plugins/workingplan.js"></script>

    <script>

        $(function () {
            $("#monday-start").timepicker();
            $("#monday-end").timepicker();
            $("#tuesday-start").timepicker();
            $("#tuesday-end").timepicker();
            $("#wednesday-start").timepicker();
            $("#wednesday-end").timepicker();
            $("#thursday-start").timepicker();
            $("#thursday-end").timepicker();
            $("#friday-start").timepicker();
            $("#friday-end").timepicker();
            $("#saturday-start").timepicker();
            $("#saturday-end").timepicker();
            $("#sunday-start").timepicker();
            $("#sunday-end").timepicker();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
        $( document ).ready(function() {
            if($("#admin").is(':checked')){
                $('.speciality').hide();
                $('.schedule').hide();
                $('[name="is_admin"]').prop('checked', true);
                $('[name="is_admin"]').prop('disabled', true);

        }
        });



        $('[name="tip_cont"]').on('change', function () {
            if (this.value === 'medic') {
                $('.speciality').show();
                $('.schedule').show();
                $('[name="is_admin"]').prop('checked', false);
                $('[name="is_admin"]').prop('disabled', false);
            } else {
                $('.speciality').hide();
                $('.schedule').hide();
                $('[name="is_admin"]').prop('checked', true);
                $('[name="is_admin"]').prop('disabled', true);
            }
        });


        $(".getNewPass").click(function () {
            var field = $('#password');
            field.val(randString(field));
        });

        function randString(id) {
            var dataSet = $(id).attr('data-character-set').split(',');
            var possible = '';
            if ($.inArray('a-z', dataSet) >= 0) {
                possible += 'abcdefghijklmnopqrstuvwxyz';
            }
            if ($.inArray('A-Z', dataSet) >= 0) {
                possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }
            if ($.inArray('0-9', dataSet) >= 0) {
                possible += '0123456789';
            }
            if ($.inArray('#', dataSet) >= 0) {
                possible += '![]{}()%&*$#^<>~@|';
            }
            var text = '';
            for (var i = 0; i < $(id).attr('data-size'); i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        }
    </script>
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Adauga Cont Staff</h1>
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
                                        <label class="d-block" for="tipcont">Tip Cont</label>
                                        <div class="icheck-danger d-inline">
                                            <input type="radio" name="tip_cont" value="medic" checked
                                                   id="medic">
                                            <label for="medic">
                                                Medic
                                            </label>
                                        </div>
                                        <div class="icheck-danger d-inline">
                                            <input type="radio" name="tip_cont" value="administrativ" id="admin" {{(old('tip_cont') === 'administrativ') ? 'checked' : ''}}>
                                            <label for="admin">
                                                Administrativ
                                            </label>
                                        </div>

                                    </div>
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
                                        <label for="password">Parola</label>
                                        <div class="input-group mb-3">
                                            <input class="form-control @error('password') is-invalid @enderror"
                                                   type="text" data-size="10" data-character-set="a-z,A-Z,0-9,#"
                                                   value="" name="password" id="password">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger getNewPass">Genereaza
                                                </button>
                                            </div>
                                            <!-- /btn-group -->
                                            @error('password')
                                            <p class="error invalid-feedback">{{ $errors->first('password') }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group speciality">
                                        <label>Specialitate</label>
                                        <select class="custom-select">
                                            <option value="null">Fara specialitate</option>
                                            @foreach($specialities as $speciality)
                                                <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group schedule">
                                        <label>Program de lucru</label>
                                        <table class="working-plan table table-striped mt-2">
                                            <thead>
                                            <tr>
                                                <th>Ziua</th>
                                                <th>De la ora</th>
                                                <th>Pana la ora</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="icheck-primary form-check"><input
                                                            class="form-check-input" type="checkbox" id="monday"
                                                            name="monday" {{ old('monday') }}><label
                                                            class="form-check-label" for="monday">Luni</label></div>
                                                </td>
                                                <td><input id="monday-start" value="08:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                                <td><input id="monday-end" value="16:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="icheck-primary form-check"><input
                                                            class="form-check-input" type="checkbox" id="tuesday"
                                                            checked><label class="form-check-label"
                                                                           for="tuesday">Marti</label></div>
                                                </td>
                                                <td><input id="tuesday-start" value="08:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                                <td><input id="tuesday-end" value="16:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="icheck-primary form-check"><input
                                                            class="form-check-input" type="checkbox" id="wednesday"
                                                            checked><label class="form-check-label" for="wednesday">Miercuri</label>
                                                    </div>
                                                </td>
                                                <td><input id="wednesday-start" value="08:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                                <td><input id="wednesday-end" value="16:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="icheck-primary form-check"><input
                                                            class="form-check-input" type="checkbox" id="thursday"
                                                            checked><label class="form-check-label"
                                                                           for="thursday">Joi</label></div>
                                                </td>
                                                <td><input id="thursday-start" value="08:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                                <td><input id="thursday-end" value="16:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="icheck-primary form-check"><input
                                                            class="form-check-input" type="checkbox" id="friday"
                                                            checked><label class="form-check-label"
                                                                           for="friday">Vineri</label></div>
                                                </td>
                                                <td><input id="friday-start" value="08:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                                <td><input id="friday-end" value="16:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="icheck-primary form-check"><input
                                                            class="form-check-input" type="checkbox" id="saturday"
                                                            checked><label class="form-check-label" for="saturday">Sambata</label>
                                                    </div>
                                                </td>
                                                <td><input id="saturday-start" value="08:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                                <td><input id="saturday-end" value="16:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="icheck-primary form-check"><input
                                                            class="form-check-input" type="checkbox" id="sunday"
                                                            checked><label class="form-check-label" for="sunday">Duminica</label>
                                                    </div>
                                                </td>
                                                <td><input id="sunday-start" value="08:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                                <td><input id="sunday-end" value="16:00"
                                                           class="work-start form-control input-sm hasDatepicker"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="working_plan" name="working_plan" value="">
                                    </div>

                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="is_admin" id="checkboxPrimary3">
                                        <label for="checkboxPrimary3">
                                            Aloca drepturi de administrator
                                        </label>
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
