@extends('admin.layouts.app')
@section('title', 'Distributed Hub View')
@section('css')
<style>
    input[switch]+label {
        width: 75px !important;
    }

    input[switch]:checked+label:after {
        left: 54px !important;
    }

    .readOnlyClass {
        background: #E6E6E6;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- end page title -->
    <div class="row">
        <form id="searchForm" method="get" action="<?= url()->current() ?>">
            <input type="hidden" name="per_page" id="perPageHidden" />
        </form>
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Hub Overview : @if($hub->status_id == 1)<span class="text-success">Active</span> @else <span class="text-success">Inactive</span> @endif</h4>
                    <div class="page-title-right btn-card-header">
                        @can('edit_hub', $permission)
                        <a class="btn btn-success waves-effect waves-light hubModelForm" data-toggle="modal" data-hub_id="{{ $hub->hub_Id }}" data-hubid="{{ $hub->hubId }}" data-city="{{ $hub->city }}" data-state="{{ $hub->state }}" data-country="{{ $hub->country }}" data-slug="{{ $hub->slug }}" data-address1="{{ $hub->address_1 }}" data-address2="{{ $hub->address_2 }}" data-zipcode="{{ $hub->zip_code }}" data-hublimit="{{ $hub->hub_limit }}" data-fulladdress="{{ $hub->full_address }}" title="Edit Hub" style="cursor: pointer;margin-right: 5px;">Edit Hub</a>
                        @endcan
                        @can('add_inventry', $permission)
                        <a class="btn btn-outline-success waves-effect waves-light vehicleModelForm" data-toggle="modal" data-operation="add" data-hub_id="{{ $hub->hub_id }}" title="Add New NotVehicleification">Add Vehicle</a>
                        @endcan
                        <a href="" class="btn btn-outline-success waves-effect waves-light" title="Add New Notification">Assign an EV</a>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="detail_cnt">
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <h5> Hub Id : <span>{{$hub->hubId}}</span></h5>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h5>Hub Capacity: <span> {{$hub->hub_limit}}</span></h5>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h5>Vehicle Count in Hub : <span>###</span></h5>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h5>Total No. of Employees : <span>###</span></h5>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <h5>Hub Address : <span> {{$hub->address_1}}{{$hub->address_2 ? ', '.$hub->address_2: ''}}</span></h5>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div>
                                    <h5>Pincode : <span> {{$hub->zip_code}}</span></h5>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h5>City : <span> {{$hub->city}}</span></h5>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h5>State : <span> {{$hub->state}}</span></h5>
                            </div>

                            <div class="col-xl-6">
                                <h5>Total Accessories : <span>Helmets, T-shirts</span></h5>
                            </div>

                        </div>
                    </div>
                </div><!-- end card-body -->
            </div>
            <div class="col-12">
                <div class="nav_cust_menu">
                    <ul>
                        <li><a href="{{route('hub-view',['slug' => request()->route('slug'), 'param' => 'vehicle'])}}" class="{{request()->route('param')=='vehicle' ? 'active' : ''}}">Hub Inventory</a></li>
                        <li><a href="{{route('hub-view',['slug' => request()->route('slug'), 'param' => 'employee'])}}" class="{{request()->route('param')=='employee' ? 'active' : ''}}">Employees</a></li>
                        {{--<li><a href="{{route('hub-view',['slug' => request()->route('slug'), 'param' => 'accessories'])}}" class="{{request()->route('param')=='accessories' ? 'active' : ''}}">Accessories</a></li>--}}
                    </ul>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-filter">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" class="btn btn-link" onclick="refreshPage('<?= url()->current() ?>');">
                                        <img src="{{ asset('public/assets/images/icons/refresh.svg') }}" alt="">
                                    </a>
                                </li>
                                <li>
                                    <p>Total Record : <span>255</span></p>
                                </li>
                                <li>
                                    <p>Display up to :
                                    <div class="form-group">
                                        @include('admin.layouts.per_page')
                                    </div>
                                    </p>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-success waves-effect waves-light">
                                        <img src="{{asset('public/assets/images/icons/download.svg')}}" alt=""> Export
                                    </button>
                                </li>
                                @can('add_user', $permission)
                                @if(request()->route('param') == 'employee')
                                <li style="float:right">
                                    <a href="" class="btn btn-success waves-effect waves-light userModelForm" data-toggle="modal" data-operation="add" data-hub_id="{{ $hub->hub_id }}" data-empid="{{ $hub->max_emp_id }}" data-target="#userModelForm">Create New Employee</a></h1>
                                </li>
                                @endif
                                @endcan
                            </ul>
                        </div>
                        <div class="table-rep-plugin">
                            @if(request()->route('param')=='vehicle')
                            @include('admin.hub.vehicle_listing')
                            @elseif (request()->route('param')=='employee')
                            @include('admin.hub.employee_listing')
                            {{--@elseif (request()->route('param')=='accessories')
                            @include('admin.hub.accessories_listing') --}}
                            @endif
                        </div>
                    </div>
                </div>
                <!-- end card -->
            </div> <!-- end col -->
            <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- Add role model -->
<div class="modal fade" id="hubModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hubModalLabel">Edit Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateHub">
                    @csrf
                    <input type="hidden" class="form-control" name="slug" id="slug">
                    <div class="mb-2">
                        <label for="hubid" class="col-form-label">Hub Id <sup class="compulsayField">*</sup> <span class="spanColor hubid_error"></span></label>
                        <input type="text" name="hubId" class="form-control" id="hubId" value="{{$hub->hubId}}" readonly>
                    </div>
                    <div class="mb-2">
                        <label for="address1" class="col-form-label">Search Address</label>
                        <input id="autocomplete" name="street_address" placeholder="Enter your address" type="text" class="floating-input form-control">
                    </div>
                    <div class="mb-2">
                        <label for="address1" class="col-form-label">Address Line 1</label>
                        <input type="text" name="address1" class="form-control" id="address1">
                    </div>
                    <div class="mb-2">
                        <label for="address2" class="col-form-label">Address Line 2</label>
                        <input type="text" name="address2" class="form-control" id="address2">
                    </div>
                    <div class="mb-2">
                        <label for="city" class="col-form-label">City</label>
                        <input type="text" name="city" class="form-control" id="city">
                    </div>
                    <div class="mb-2">
                        <label for="state" class="col-form-label">State </label>
                        <input type="text" name="state" class="form-control" id="state">
                    </div>
                    <div class="mb-2">
                        <label for="country" class="col-form-label">Country</label>
                        <input type="text" name="country" class="form-control" id="country">
                    </div>
                    <div class="mb-2">
                        <label for="pincode" class="col-form-label">Pin Code</label>
                        <input type="text" name="zip_code" class="form-control" id="zip_code">
                    </div>
                    <div class="mb-2">
                        <label for="hublimit" class="col-form-label">Hub Limit</label>
                        <input type="text" name="hub_limit" class="form-control" id="hub_limit">
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class="text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitHub" class="btn btn-success waves-effect waves-light">Update
                </button>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        function toggleReadOnly(state) {
            $('.readOnlyClass').prop('readonly', state);
        }

        // Model data
        $('.hubModelForm').click(function() {
            $('#hubModelForm').modal('show');
            var slug = $(this).data('slug');
            if (slug) {
                var hub_id = $(this).data('hub_Id');
                var hubId = $(this).data('hubid');
                var city = $(this).data('city');
                var state = $(this).data('state');
                var country = $(this).data('country');
                var address1 = $(this).data('address1');
                var address2 = $(this).data('address2');
                var zip_code = $(this).data('zipcode');
                var hub_limit = $(this).data('hublimit');
                var full_address = $(this).data('fulladdress');

                $("#slug").val(slug);
                $("#hubId").val(hubId);
                $("#city").val(city);
                $("#state").val(state);
                $("#country").val(country);
                $("#address1").val(address1);
                $("#address2").val(address2);
                $("#zip_code").val(zip_code);
                $("#hub_limit").val(hub_limit);
                $("#autocomplete").val(full_address);
            }

            if (slug) {
                $('#submitHub').html('Update')
            }
            if (slug) {
                $('#hubModalLabel').html('Edit Hub')
            }
        });
        $('#submitHub').click(function(e) {
            e.preventDefault();
            var name = $('#hubId').val();
            if (name == "") {
                $(".hubid_error").html('This field is required!');
                $("input#hubId").focus();
                return false;
            }
            $('#submitHub').prop('disabled', true);
            $('#submitHub').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUpdateHub'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-update-hub') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitHub').prop('disabled', false);
                    $('#submitHub').html('Update');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);

                },
                errors: function() {
                    $('#message').html("<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });

        // employee model

        $('.userModelForm').on('click', function(event) {
            event.preventDefault();
            var operationType = $(this).data('operation'); // Retrieve the operation type from data attribute
            if (operationType === 'add') {
                var hub_id = $(this).data('hub_id');
                var empid = $(this).data('empid');
                $('#first_name').removeAttr('readonly');
                $('#first_name').removeClass('readOnlyClass');
                $('#last_name').removeAttr('readonly');
                $('#last_name').removeClass('readOnlyClass');
                $('#email').removeAttr('readonly');
                $('#email').removeClass('readOnlyClass');
                $('#hub_id').val(hub_id);
                $('#employee_id').val(empid);

            } else if (operationType === 'update') {
                $('#first_name').attr('readonly', true);
                $('#last_name').attr('readonly', true);
                $('#email').attr('readonly', true);
                $('#employee_id').attr('readonly', true);
            }

            $('#userModelForm').modal('show');
            var first_name = $(this).data('fname');
            var last_name = $(this).data('lname');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var slug = $(this).data('slug');
            var roleid = $(this).data('roleid');
            var rolename = $(this).data('rolename');
            var hub_id = $(this).data('hub_id');
            var empid = $(this).data('empid');

            $("#first_name").val(first_name);
            $("#last_name").val(last_name);
            $("#email").val(email);
            $("#phone").val(phone);
            $("#slug").val(slug);
            //$("#role_id").find(':selected').attr('data-roleid')
            $("#role_id").val(roleid);
            $("#rolename").val(rolename);
            $("#hub_id").val(hub_id);
            $("#employee_id").val(empid);

        });
        $('#submitUser').click(function(e) {
            e.preventDefault();
            var name = $('#first_name').val();
            if (name == "") {
                $(".name_error").html('This field is required!');
                $("input#first_name").focus();
                return false;
            }
            var name = $('#email').val();
            if (name == "") {
                $(".email_error").html('This field is required!');
                $("input#email").focus();
                return false;
            }
            $('#submitUser').prop('disabled', true);
            $('#submitUser').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUpdateUser'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-update-user') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitUser').prop('disabled', false);
                    $('#submitUser').html('Update');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                },
                errors: function() {
                    $('#message').html("<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });

        // vehicle model

        $('.vehicleModelForm').on('click', function(event) {
            event.preventDefault();
            var operationType = $(this).data('operation'); // Retrieve the operation type from data attribute
            if (operationType === 'add') {
                var hub_id = $(this).data('hub_id');
                $('#chassis_number').removeAttr('readonly');
                $('#chassis_number').removeClass('readOnlyClass');
                $('#hub_id').val(hub_id);

            } else if (operationType === 'update') {
                $('#chassis_number').attr('readonly', true);
            }

            $('#vehicleModelForm').modal('show');
            var slug = $(this).data('slug');
            if (slug) {
                var hub_id = $(this).data('hub_id');
                var title = $(this).data('title');
                var ev_number = $(this).data('ev_number');
                var ev_type_id = $(this).data('ev_type_id');
                var ev_category_id = $(this).data('ev_category_id');
                var profile_category = $(this).data('profile_category');
                var speed = $(this).data('speed');
                var rent_cycle = $(this).data('rent_cycle');
                var per_day_rent = $(this).data('per_day_rent');
                var battery_type = $(this).data('bettery_type');
                var km_per_charge = $(this).data('km_per_charge');
                var description = $(this).data('description');
                var is_display_on_app = $(this).data('is_display_on_app');
                var chassis_number = $(this).data('chassis_number');
                var gps_emei_number = $(this).data('gps_emei_number');
                var image = $(this).data('image');
                var bike_type = $(this).data('bike_type');
                var status_id = $(this).data('status');
                var updateurl = $(this).data('updateurl');

                $("#title").val(title);
                $("#hub_id").val(hub_id);
                $("#ev_number").val(ev_number);
                $("#speed").val(speed);
                $("#slug").val(slug);
                $("#chassis_number").val(chassis_number);
                $("#per_day_rent").val(per_day_rent);
                $("#km_per_charge").val(km_per_charge);
                $("#gps_emei_number").val(gps_emei_number);
                $("#description").val(description);
                $('#ev_type_id').val(ev_type_id).trigger('change');
                $('#ev_category').val(ev_category_id).trigger('change');
                $('#profile_category').val(profile_category).trigger('change');
                $('#rent_cycle').val(rent_cycle).trigger('change');
                $('#battery_type').val(battery_type).trigger('change');
                $('#bike_type').val(bike_type).trigger('change');
                $('#status_id').val(status_id).trigger('change');
                $("#updateurl").val(updateurl);
                var imageUrl = "{{ asset('public/upload/product')}}/" + image;
                if (image) {
                    $('.selectImageRemove').html('<img class="upload_des_preview clickable selectedImage" src="' + imageUrl + '" alt = "vehicle image" >');
                } else {
                    $('.selectImageRemove').html('<img class="upload_des_preview clickable selectedImage" src="{{asset("public/assets/images/uploadimg.png")}}" alt = "vehicle image" >');
                }

                if (is_display_on_app == 1) {
                    $('#is_display_on_app').prop('checked', true);
                } else {
                    $('#is_display_on_app').prop('checked', false);
                }
                $("#userModalLabel").html('Update Vehicle');
                $("#submitVehicle").html('Update');
            }

        });

        $('#submitVehicle').click(function(e) {
            e.preventDefault();
            var slug = $('#slug').val();
            var updateurl = $('#updateurl').val();
            var name = $('#title').val();
            if (name == "") {
                $(".title_error").html('This field is required!');
                $("input#title").focus();
                return false;
            }
            var name = $('#ev_number').val();
            if (name == "") {
                $(".ev_number_error").html('This field is required!');
                $("input#ev_number").focus();
                return false;
            }
            var name = $('#chassis_number').val();
            if (name == "") {
                $(".chassis_number_error").html('This field is required!');
                $("input#chassis_number").focus();
                return false;
            }
            $('#submitForm').prop('disabled', true);
            $('#submitForm').html('Please wait...')
            var formDatas = new FormData(document.getElementById('vehiclForm'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: slug ? updateurl : "{{route('add-product')}}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitForm').prop('disabled', false);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                },
                errors: function() {
                    $('#message').html("<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });

    });

    // Active inactive status toggle
    function toggleStatus(toggleId, params) {
        var slug = $("#" + toggleId).val();
        var newStatus = $(this).prop("checked");
        var token = "{{ csrf_token() }}";
        if (slug) {
            if (params === 'employee') {
                $.ajax({
                    url: "{{ route('user-status-changed') }}",
                    type: "POST",
                    data: {
                        "slug": slug,
                        "_token": token,
                    },
                    success: function(response) {
                        // Handle success
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                    }
                });
            } else if (params === 'vehicle') {
                // Handle 'vehicle' case, but the URL is missing in your example
                // Add the appropriate URL or action here
            }
        }
    }
</script>
@endsection