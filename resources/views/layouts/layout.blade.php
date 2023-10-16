@extends('layouts.app')
@section('content_app')

<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="{{ url('/') }}">Smartrio</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ url('account_setting') }}">Account Setting</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item d-flex align-items-center" type="submit">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav pt-2">
                        <?php
                            $prefix = request()->route()->getPrefix() != ''? request()->route()->getPrefix() : 'home';
                        ?>
                        @foreach(menuSideBar() as $key => $row)
                            @if(!array_key_exists('childs',$row))
                                <a class="nav-link {{ $prefix == $key? 'active' : '' }}" href="{{ url($key) }}">
                                    <div class="sb-nav-link-icon"><i class="{{ $row['icon'] ?? null }}"></i></div>
                                    {{ $row['name'] ?? null }}
                                </a>
                            @else
                                <a class="nav-link {{ array_key_exists($prefix,$row['childs'])? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts{{ $prefix }}" aria-expanded="{{ array_key_exists($prefix,$row['childs'])? 'true' : '' }}" aria-controls="collapseLayouts{{ $prefix }}">
                                    <div class="sb-nav-link-icon"><i class="{{ $row['icon'] ?? null }}"></i></div>
                                    {{ $row['name'] ?? null }}
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ array_key_exists($prefix,$row['childs'])? 'show' : '' }}" id="collapseLayouts{{ $prefix }}" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        @foreach($row['childs'] as $key_child => $row_child)
                                            <a class="nav-link {{ $prefix == $key_child? 'active' : '' }}" href="{{ url($key_child) }}">{{ $row_child['name'] ?? null }}</a>
                                        @endforeach
                                    </nav>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ Auth::user()->name }}
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Smartrio 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>
@endsection