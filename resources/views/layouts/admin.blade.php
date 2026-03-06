<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="QR Code Absensi System">
        <meta name="keywords" content="qr,absensi,attendance">
        <meta name="author" content="Admin">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Title -->
        <title>@yield('title', 'QR Absensi')</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

        <!-- Theme Styles -->
        <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

        <style>
            /* Fix card layout when sidebar toggles */
            .stat-widget {
                min-height: 180px;
                margin-bottom: 20px;
            }

            .stat-widget .card-body {
                padding: 1.5rem;
            }

            /* Fix row spacing */
            .row {
                margin-left: -12px;
                margin-right: -12px;
            }

            .row > [class*='col'] {
                padding-left: 12px;
                padding-right: 12px;
                margin-bottom: 20px;
            }

            /* Fix table responsive */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Button icons alignment */
            .btn i[data-feather] {
                vertical-align: middle;
                margin-right: 2px;
            }

            /* DataTables layout fixes */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 15px;
            }

            .dataTables_wrapper .dataTables_length label,
            .dataTables_wrapper .dataTables_filter label {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .dataTables_wrapper .dataTables_length select {
                margin: 0 8px;
                width: auto;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                margin-top: 15px;
            }

            /* Action buttons in table */
            table .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                line-height: 1.2;
            }

            table .btn i[data-feather] {
                margin: 0;
            }

            /* DataTables styling */
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.25rem 0.5rem;
            }

            .dataTables_wrapper .dataTables_info {
                padding-top: 1rem;
            }
        </style>

        @yield('styles')
    </head>
    <body>

        <div class="page-container">
            <!-- Header -->
            <div class="page-header">
                <nav class="navbar navbar-expand-lg d-flex justify-content-between">
                  <div class="" id="navbarNav">
                    <ul class="navbar-nav" id="leftNav">
                      <li class="nav-item">
                        <a class="nav-link" id="sidebar-toggle" href="#"><i data-feather="arrow-left"></i></a>
                      </li>
                    </ul>
                  </div>

                  <div class="" id="headerNav">
                    <ul class="navbar-nav">
                      <li class="nav-item dropdown">
                        <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <img src="{{ asset('assets/images/avatars/profile-image.png') }}" alt="">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                          <a class="dropdown-item" href="#"><i data-feather="user"></i>{{ Auth::user()->name }}</a>
                          <div class="dropdown-divider"></div>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                          </form>
                          <a class="dropdown-item" href="#" id="logout-btn"><i data-feather="log-out"></i>Logout</a>
                        </div>
                      </li>
                    </ul>
                  </div>
                </nav>
            </div>

            <!-- Sidebar -->
            <div class="page-sidebar">
                <ul class="list-unstyled accordion-menu">
                  <li class="sidebar-title">
                    Menu Utama
                  </li>
                  <li class="{{ request()->routeIs('dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('dashboard') }}"><i data-feather="home"></i>Dashboard</a>
                  </li>

                  <li class="sidebar-title">
                    Data Master
                  </li>
                  <li class="{{ request()->routeIs('mahasiswa.*') ? 'active-page' : '' }}">
                    <a href="{{ route('mahasiswa.index') }}"><i data-feather="users"></i>Mahasiswa</a>
                  </li>
                  <li class="{{ request()->routeIs('panitia.*') ? 'active-page' : '' }}">
                    <a href="{{ route('panitia.index') }}"><i data-feather="user-check"></i>Panitia</a>
                  </li>

                  <li class="sidebar-title">
                    Absensi
                  </li>
                  <li class="{{ request()->routeIs('scan.*') ? 'active-page' : '' }}">
                    <a href="{{ route('scan.index') }}"><i data-feather="camera"></i>Scan QR</a>
                  </li>
                  <li class="{{ request()->routeIs('absensi.*') ? 'active-page' : '' }}">
                    <a href="{{ route('absensi.index') }}"><i data-feather="clipboard"></i>Rekap Absensi</a>
                  </li>
                </ul>
            </div>

            <!-- Page Content -->
            <div class="page-content">
              <div class="main-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i data-feather="check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i data-feather="alert-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
              </div>
            </div>

        </div>

        <!-- Javascripts -->
        <script src="{{ asset('assets/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/js/main.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

        <script>
            // Initialize feather icons
            feather.replace();

            // Logout confirmation with SweetAlert
            document.addEventListener('DOMContentLoaded', function() {
                const logoutBtn = document.getElementById('logout-btn');
                if (logoutBtn) {
                    logoutBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Logout?',
                            text: "Apakah Anda yakin ingin keluar dari sistem?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Logout!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('logout-form').submit();
                            }
                        });
                    });
                }
            });
        </script>

        @yield('scripts')
    </body>
</html>
