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
                <button type="button" class="btn header-item noti-icon position-relative" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('public/assets/images/icons/setting-icon.svg') }}" alt="">
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end admin_profilt_pop">
                    <div data-simplebar>
                        <div class="w-100 text-end">
                            <a href="#" class="btn btn-link-cust" data-bs-toggle="modal" data-bs-target="#Editcompany"> Edit </a>
                        </div>
                        <div class="profil_img">
                            <img class="img-thumbnail border-0 p-0 avatar-xl" alt="200x200" src="http://localhost/PRJ-098/public/assets/images/logo-sm.svg" data-holder-rendered="true">
                        </div>
                        <ul>
                            <li>Company name : <span>Evatoz Solutions</span></li>
                            <li>Company Address : <span> flat No121, sector 63, Noida</span></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="dropdown d-inline-block">
                @include('admin.common.request_notification')
            </div>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('public/assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                    <span class="active"></span>
                    <span class="d-none d-xl-inline-block fw-medium">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    <span class="d-grid d-grid justify-content-start">{{$role}}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end admin_profilt_pop">
                    <div data-simplebar>
                        <div class="w-100 text-end">
                            <a href="#" class="btn btn-link-cust" data-bs-toggle="modal" data-bs-target="#Editprofile"> Edit </a>
                        </div>
                        <div class="profil_img">
                            <img class="img-thumbnail rounded-circle avatar-xl" alt="200x200" src="http://localhost/PRJ-098/public/assets/images/users/avatar-3.jpg" data-holder-rendered="true">
                            <span class="active"></span>
                        </div>
                        <ul>
                            <li>Name : <span>Ankit Lodhi</span></li>
                            <li>Email : <span> Ankit@gmail.com</span></li>
                            <li>Mobile number : <span> +91 789 456 7786</span></li>
                            <li> Designation : <span> Admin</span></li>
                        </ul>
                        <div class="d-flex">
                            <a href="#" class="btn btn-theme-drop mr-2"> Change Password </a>
                            <a href="#" class="btn btn-theme-drop"> Logout </a>
                        </div>
                    </div>
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

<div id="Editprofile" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateEvType" autocomplete="off">
                    @csrf
                    <div class="row">

                        <div class="col-12">
                            <input type="hidden" class="form-control" name="slug" id="evslug">
                            <div class="form-group mb-2">
                                <label for="ev_type_name" class="col-form-label">Email <sup class="compulsayField">*</sup> <span class="spanColor ev_type_name_error"></span></label>
                                <input type="text" name="ev_type_name" class="form-control" id="ev_type_name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="range" class="col-form-label ">Phone Number &nbsp;<span class="spanColor" id="range_errors"></span></label>
                                <input type="text" name="range" class="form-control" id="range">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="messageEvType" style="margin-right: 10px"></span>
                <button type="button" id="submitEvType" class="btn btn-success waves-effect waves-light">Save
                </button>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="Editcompany" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeModalLabel">Edit Company Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateEvType" autocomplete="off">
                    @csrf
                    <div class="row">

                        <div class="col-12">
                            <input type="hidden" class="form-control" name="slug" id="evslug">
                            <div class="form-group mb-2">
                                <label for="ev_type_name" class="col-form-label">Company Name <sup class="compulsayField">*</sup> <span class="spanColor ev_type_name_error"></span></label>
                                <input type="text" name="ev_type_name" class="form-control" id="ev_type_name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="range" class="col-form-label ">Company Address &nbsp;<span class="spanColor" id="range_errors"></span></label>
                                <input type="text" name="range" class="form-control" id="range">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="messageEvType" style="margin-right: 10px"></span>
                <button type="button" id="submitEvType" class="btn btn-success waves-effect waves-light">Save
                </button>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->