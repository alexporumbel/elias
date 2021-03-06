@extends('admin.parts.layout')
@section('head')

    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection

@section('footer')
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script>
        $(function () {
            @if ($message = Session::get('success'))
                toastr["success"]("{{ $message }}");
            @elseif($message = Session::get('info'))
                toastr["warning"]("{{ $message }}");
            @elseif($message = Session::get('warning'))
                toastr["error"]("{{ $message }}");
            @endif
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
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
                        <h1 class="m-0">Specialitati</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 ">
                        <span class="breadcrumb float-sm-right"><a class="btn btn-block btn-primary" href="{{ route('speciality.create') }}">Adauga Specialitate</a></span>

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
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Specialitate</th>
                                        <th>Cu plata</th>
                                        <th>Modifica</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($specialities as $speciality)
                                    <tr>
                                        <td>{{ $speciality->name }}</td>
                                        <td>{{ $speciality->is_paid == 1 ? 'DA':'NU' }}
                                        </td>
                                        <td><div class="mng-icons"><a class="btn btn-default" style="display: inline-block;" href="{{ route('speciality.edit', $speciality) }}"><i class="far fa-edit"></i></a>
                                            <form style="display: inline-block;" action="{{ route('speciality.delete', $speciality) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-default" type="submit"><i class="far fa-trash-alt"></i></button>
                                            </form></div>
                                            </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Specialitate</th>
                                        <th>Cu plata</th>
                                        <th>Modifica</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- /.content-wrapper -->
@endsection
