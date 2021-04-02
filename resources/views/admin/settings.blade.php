@extends('admin.parts.layout')
@section('footer')
    <script>
        $(function () {
            @if ($message = Session::get('success'))
                toastr["success"]("{{ $message }}");
            @endif
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
                        <h1 class="m-0">Modifica Setari</h1>
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
                                <h3 class="card-title">Setari actuale</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" action="{{ route('settings.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nume Site</label>
                                        <input class="form-control @error('site_name') is-invalid @enderror" type="text" value="{{ $settings['site_name'] }}"  name="site_name" id="site_name">
                                        @error('site_name')
                                        <p class="error invalid-feedback">{{ $errors->first('site_name') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Durata programari ambulator(minute)</label>
                                        <input class="form-control @error('ambulatory_duration') is-invalid @enderror" type="text" value="{{ $settings['ambulatory_duration'] }}"  name="ambulatory_duration" id="ambulatory_duration">
                                        @error('ambulatory_duration')
                                        <p class="error invalid-feedback">{{ $errors->first('ambulatory_duration') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nume expeditor email</label>
                                        <input class="form-control @error('mail_name') is-invalid @enderror" type="text" value="{{ $settings['mail_name'] }}"  name="mail_name" id="mail_name">
                                        @error('mail_name')
                                        <p class="error invalid-feedback">{{ $errors->first('mail_name') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Server Mail</label>
                                        <input class="form-control @error('mail_server') is-invalid @enderror" type="text" value="{{ $settings['mail_server'] }}"  name="mail_server" id="mail_server">
                                        @error('mail_server')
                                        <p class="error invalid-feedback">{{ $errors->first('mail_server') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Port Server</label>
                                        <input class="form-control @error('mail_port') is-invalid @enderror" type="text" value="{{ $settings['mail_port'] }}"  name="mail_port" id="mail_port">
                                        @error('mail_port')
                                        <p class="error invalid-feedback">{{ $errors->first('mail_port') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Parola Server</label>
                                        <input class="form-control @error('mail_password') is-invalid @enderror" type="password" placeholder="*******"  name="mail_password" id="mail_password">
                                        @error('mail_password')
                                        <p class="error invalid-feedback">{{ $errors->first('mail_password') }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Modifica</button>
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
