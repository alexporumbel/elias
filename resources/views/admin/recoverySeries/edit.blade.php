@extends('admin.parts.layout')

@section('head')
    <link rel="stylesheet" href="/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endsection

@section('footer')
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/plugins/moment/locale/ro.js"></script>
    <script src="/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(function () {
            $('#datetimepicker1').datetimepicker({ format: 'L', locale: 'ro' });
            $('#datetimepicker2').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: 'ro'
            });
            $('#datetimepicker1').datetimepicker('date', moment('{{ $recoveryseries->start_date }}', 'DD-MM-YYYY'));
            $('#datetimepicker2').datetimepicker('date', moment('{{ $recoveryseries->end_date }}', 'DD-MM-YYYY'));
            $("#datetimepicker1").on("change.datetimepicker", function (e) {
                $('#datetimepicker2').datetimepicker('minDate', e.date);
            });
            $("#datetimepicker2").on("change.datetimepicker", function (e) {
                $('#datetimepicker1').datetimepicker('maxDate', e.date);
            });

        });
    </script>
@endsection
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Adauga Serie Recuperare</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 ">
                        <span class="breadcrumb float-sm-right"><a class="btn btn-block btn-primary" href="{{ url()->previous() }}">Inapoi</a></span>

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
                            <form method="POST" action="{{ route('recoveryseries.update', $recoveryseries) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nume Serie</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" value="{{ $recoveryseries->series }}"  name="name" id="name">
                                        @error('name')
                                        <p class="error invalid-feedback">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Data inceput</label>
                                        <input class="form-control @error('start_datetime') is-invalid @enderror datetimepicker-input" id="datetimepicker1" data-toggle="datetimepicker" data-target="#datetimepicker1" type="text"  name="start_datetime">
                                        @error('start_datetime')
                                        <p class="error invalid-feedback">{{ $errors->first('start_date') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Data sfarsit</label>
                                        <input class="form-control @error('end_datetime') is-invalid @enderror datetimepicker-input" id="datetimepicker2" data-toggle="datetimepicker" data-target="#datetimepicker2" type="text" name="end_datetime" id="end_datetime">
                                        @error('end_datetime')
                                        <p class="error invalid-feedback">{{ $errors->first('end_datetime') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Capacitate totala</label>
                                        <input class="form-control @error('capacity') is-invalid @enderror" type="text" value="{{ $recoveryseries->capacity }}"  name="capacity" id="capacity">
                                        @error('capacity')
                                        <p class="error invalid-feedback">{{ $errors->first('capacity') }}</p>
                                        @enderror
                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Salveaza</button>
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
