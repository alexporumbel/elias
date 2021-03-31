<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    @yield('head')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="/assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->


        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.logout') }}" role="button">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="/assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    @auth()
                    <a href="#" class="d-block">{{ Auth::user()->name .' '. Auth::user()->lname }}</a>
                    @endauth
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link {{ Request::path() == 'admin/ambulatory' || Request::path() == 'admin/hospitalization' || Request::path() == 'admin/recovery' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Tablou de bord
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('ambulatory') }}" class="nav-link {{ Request::path() == 'admin/ambulatory' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Programari ambulator</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hospitalization') }}" class="nav-link {{ Request::path() == 'admin/hospitalization' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Programari spitalizare</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('recovery') }}" class="nav-link {{ Request::path() == 'admin/recovery' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Programari recuperare</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @can('admin')
                    <li class="nav-item">
                        <a href="{{ route('specialities.index') }}" class="nav-link {{ Request::path() == 'admin/specialities' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>
                                Specialitati
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('recoveryseries.index') }}" class="nav-link {{ Request::path() == 'admin/recoveryseries' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-notes-medical"></i>
                            <p>
                                Serii recuperare
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ Request::path() == 'admin/users' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Staff
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings') }}" class="nav-link {{ Request::path() == 'admin/settings' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Setari
                            </p>
                        </a>
                    </li>
                    @endcan

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
   @yield('content')
    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.1.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 4 -->
<script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
@yield('footer')
<script src="/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/assets/dist/js/adminlte.js"></script>
</body>
</html>
