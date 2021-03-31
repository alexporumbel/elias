@extends('admin.parts.layout')
@section('head')
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
@endsection




@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Adauga programare recuperare</h1>
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
                            <form id="userform" method="POST" action="{{ route('recovery.store') }}">
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
                                        <label>Serie recuperare</label>
                                        <select name="series" class="custom-select series @error('series') is-invalid @enderror">
                                            <option>Selecteaza serie</option>
                                            @foreach($series as $serie)
                                                <option value="{{ $serie->id }}">{{ $serie->start_date.' - '.$serie->end_date }}</option>
                                            @endforeach
                                        </select>
                                        @error('series')
                                        <p class="error invalid-feedback">{{ $errors->first('series') }}</p>
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
                                        @error('appointmentType')
                                        <p class="error invalid-feedback">{{ $errors->first('appointmentType') }}</p>
                                        @enderror
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
