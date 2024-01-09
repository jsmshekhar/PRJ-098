<?php
$basePath = asset('public/upload/');
$roles = DB::table('roles')
    ->where('role_id', auth()->user()->role_id)
    ->select('name')
    ->first();
if ($roles) {
    $role = $roles->name;
} else {
    $role = 'Superadmin';
}

$configurations = DB::table('site_configuration')
    ->select(['company_name', 'company_address', 'company_logo'])
    ->first();
$companyName = $configurations->company_name ?? 'Evatoz Solutions';
$companyAddress = $configurations->company_address ?? 'Noida Sector 22';
$companyLogo = '';
if ($configurations->company_logo) {
    $companyLogo = $basePath . '/settengs/' . $configurations->company_logo;
} else {
    $companyLogo = asset('public/assets/images/logo-sm.svg');
}

$userImage = asset('public/assets/images/users/avatar-1.jpg');
if (auth()->user()->photo) {
    $userImage = $basePath . '/users/' . auth()->user()->photo;
}

?>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('home') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ $companyLogo }}" alt="" height="58">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $companyLogo }}" alt="" height="58">
                    </span>
                </a>

                <a href="{{ route('home') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ $companyLogo }}" alt="" height="58">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ $companyLogo }}" alt="" height="58">
                    </span>
                </a>
                <div><b>{{ $companyName }}</b></div>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item mob-insvisble"
                id="vertical-menu-btn">
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
                <button type="button" class="btn header-item" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon position-relative"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('public/assets/images/icons/setting-icon.svg') }}" alt="">
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end admin_profilt_pop">
                    <div data-simplebar>
                        <div class="w-100 text-end">
                            <a href="#" class="btn btn-link-cust" data-bs-toggle="modal"
                                data-bs-target="#Editcompany"> Edit </a>
                        </div>
                        <div class="profil_img">
                            <img class="img-thumbnail border-0 p-0 avatar-xl" alt="200x200" src="{{ $companyLogo }}"
                                data-holder-rendered="true">
                        </div>
                        <ul>
                            <li>Company name : <span>{{ $companyName }}</span></li>
                            <li>Company Address : <span> {{ $companyAddress }}</span></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="dropdown d-inline-block">
                @include('admin.common.request_notification')
            </div>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ $userImage }}"
                        alt="{{ Auth::user()->first_name }}">
                    <span class="active"></span>
                    <span class="d-none d-xl-inline-block fw-medium">{{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    <span class="d-grid d-grid justify-content-start">{{ $role }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end admin_profilt_pop">
                    <div data-simplebar>
                        <div class="w-100 text-end">
                            <a href="#" class="btn btn-link-cust" data-bs-toggle="modal" data-bs-toggle="modal"
                                data-bs-target="#Editprofile"> Edit </a>
                        </div>
                        <div class="profil_img">
                            <img class="img-thumbnail rounded-circle avatar-xl" alt="200x200"
                                src="{{ $userImage }}" data-holder-rendered="true">
                            <span class="active"></span>
                        </div>
                        <ul>
                            <li>Name : <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></li>
                            <li>Email : <span> {{ Auth::user()->email }}</span></li>
                            <li>Mobile number : <span> {{ Auth::user()->phone }}</span></li>
                            <li> Designation : <span> {{ $role }}</span></li>
                        </ul>
                        <div class="d-flex">
                            <a href="#" class="btn btn-theme-drop mr-2" data-bs-toggle="modal"
                                data-bs-target="#ChangePassword"> Change Password </a>
                            <a class="btn btn-theme-drop" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

</header>

<div id="Editprofile" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    data-bs-scroll="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="updateUserProfile" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="userFname" class="col-form-label">First Name <sup
                                        class="compulsayField">*</sup> <span
                                        class="spanColor user_fname_error"></span></label>
                                <input type="text" name="user_fname" class="form-control" id="userFname"
                                    value="{{ Auth::user()->first_name }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="userLname" class="col-form-label">Last Name <sup
                                        class="compulsayField">*</sup> <span
                                        class="spanColor user_lname_error"></span></label>
                                <input type="text" name="user_lname" class="form-control" id="userLname"
                                    value="{{ Auth::user()->last_name }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="user_phone" class="col-form-label ">Phone Number<span class="spanColor"
                                        id="user_phone"></span></label>
                                <input type="text" name="user_phone" class="form-control" id="user_phone"
                                    value="{{ Auth::user()->phone }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="title" class="form-label">Profile Image</label>
                                <div class="">
                                    <label for="userImage" class="selectImageRemove">
                                        <img class="upload_des_preview clickable selectedImage "
                                            src="{{ $userImage }}" alt="Logo Image" />
                                    </label>
                                    <input type="file" class="form-control d-none customFile" name="user_image"
                                        id="userImage" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="messageEvType" style="margin-right: 10px"></span>
                <button type="button" id="submitUserProfileForm"
                    class="btn btn-success waves-effect waves-light">Save
                </button>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="Editcompany" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    data-bs-scroll="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeModalLabel">Edit Company Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="companyDetailForm" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="company_name" class="col-form-label">Company Name <sup
                                        class="compulsayField">*</sup> <span
                                        class="spanColor company_name_error"></span></label>
                                <input type="text" name="company_name" class="form-control" id="company_name"
                                    value="{{ $companyName }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="company_address" class="col-form-label ">Company Address &nbsp;<span
                                        class="spanColor" id="company_address_errors"></span></label>
                                <input type="text" name="company_address" class="form-control"
                                    id="company_address" value="{{ $companyAddress }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="title" class="form-label">Change Logo</label>
                                <div class="">
                                    <label for="logoImage" class="selectImageRemove">
                                        <img class="upload_des_preview clickable selectedImage "
                                            src="{{ $companyLogo }}" alt="Logo Image" />
                                    </label>
                                    <input type="file" class="form-control d-none customFile" name="company_logo"
                                        id="logoImage" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="" style="margin-right: 10px"></span>
                <button type="button" id="submitCompanyForm" class="btn btn-success waves-effect waves-light">Save
                </button>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="ChangePassword" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    data-bs-scroll="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="passwordChangeForm" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="company_name" class="col-form-label"> Old Password <span
                                        class="spanColor old_password_error"></span></label>
                                <input type="password" name="old_password" class="form-control" id="old_password">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label class="col-form-label ">New Password <span
                                        class="spanColor new_password_error"></span></label>
                                <input type="password" name="password" class="form-control" id="new_password">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label class="col-form-label ">Confirm Password &nbsp;<span
                                        class="spanColor confirm_password_error"></span></label>
                                <input type="password" name="confirm_password" class="form-control"
                                    id="confirm_password">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="message" style="margin-right: 10px"></span>
                <button type="button" id="submitPasswordForm" class="btn btn-success waves-effect waves-light">Save
                </button>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
