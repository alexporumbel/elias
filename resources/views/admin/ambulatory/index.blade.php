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
    <script src="/assets/plugins/jszip/jszip.min.js"></script>
    <script src="/assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>
        $(function () {
            @if ($message = Session::get('success'))
                toastr["success"]("{{ $message }}");
            @elseif($message = Session::get('warning'))
                toastr["error"]("{{ $message }}");
            @endif

            $("#example2").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
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
                        <h1 class="m-0">Programari Ambulator</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 ">
                        <span class="breadcrumb float-sm-right"><a class="btn btn-block btn-primary" href="{{ route('ambulatory.create') }}">Adauga Programare</a></span>

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
                                        <th>Pacient</th>
                                        <th>Medic</th>
                                        <th>Specialitate</th>
                                        <th>Telefon</th>
                                        <th>Tip prezentare</th>
                                        <th>Observatii</th>
                                        <th>Data Programarii</th>
                                        <th>Modifica</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ambulatories as $ambulatory)
                                        <tr>
                                            <td>{{ $ambulatory->name . ' '. $ambulatory->lname }}</td>
                                            <td>{{ $ambulatory->medic->name .' '.$ambulatory->medic->lname }}</td>
                                            <td>{{ $ambulatory->medic->settings->speciality->name }}</td>
                                            <td>{{ $ambulatory->phone }}</td>
                                            <td>{{ $types[$ambulatory->appointment_type] }}</td>
                                            <td>{{ $ambulatory->notes }}</td>
                                            <td>{{ $ambulatory->start_datetime }}</td>
                                            <td><div class="mng-icons">
                                                    <form style="display: inline-block;" action="{{ route('ambulatory.delete', $ambulatory) }}" method="post">
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
                                        <th>Pacient</th>
                                        <th>Medic</th>
                                        <th>Specialitate</th>
                                        <th>Telefon</th>
                                        <th>Tip prezentare</th>
                                        <th>Observatii</th>
                                        <th>Data Programarii</th>
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
