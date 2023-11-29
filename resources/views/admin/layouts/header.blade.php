<?php
$roles = DB::table('roles')->where('role_id', auth()->user()->role_id)->select('name')->first();
if ($roles) {
    $role = $roles->name;
} else {
    $role = "Superadmin";
}
?>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{route('home')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('public/assets/images/logo-sm.svg') }}" alt="" height="58">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('public/assets/images/logo-sm.svg') }}" alt="" height="58">
                    </span>
                </a>

                <a href="{{route('home')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('public/assets/images/logo-sm.svg') }}" alt="" height="58">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('public/assets/images/logo-sm.svg') }}" alt="" height="58">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item mob-insvisble" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <img src="{{ asset('public/assets/images/icons/Search.svg') }}" alt="" class="search_inpot">
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </form>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('public/assets/images/icons/bell-icon.svg') }}" alt="">
                    <span class="badge bg-danger rounded-pill"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                    
                    <div data-simplebar style="max-height: 230px;">
                       
                        <a href="#!" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-grow-1 p-3 border-bottom">
                                    <p class="notify_text">Hello , you get a Request for Accessories from Hub Id 12453.</p>
                                    <div class="notify_btn_box">
                                        <a href="#" class="btn btn-outline-danger waves-effect waves-light">Reject</a>
                                        <a href="#" class="btn btn-success waves-effect waves-light">Open</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#!" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-grow-1 p-3 border-bottom">
                                    <p class="notify_text">Hello , you get a Request for Accessories from Hub Id 12453.</p>
                                    <div class="notify_btn_box">
                                        <a href="#" class="btn btn-outline-danger waves-effect waves-light">Reject</a>
                                        <a href="#" class="btn btn-success waves-effect waves-light">Open</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#!" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-grow-1 p-3 border-bottom">
                                    <p class="notify_text">Hello , you get a Request for Accessories from Hub Id 12453.</p>
                                    <div class="notify_btn_box">
                                        <a href="#" class="btn btn-outline-danger waves-effect waves-light">Reject</a>
                                        <a href="#" class="btn btn-success waves-effect waves-light">Open</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('public/assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                    <span class="active"></span>
                    <span class="d-none d-xl-inline-block fw-medium">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    <span class="d-grid d-grid justify-content-start">{{$role}}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <!-- <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Lock Screen</a>
                    <div class="dropdown-divider"></div> -->
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>