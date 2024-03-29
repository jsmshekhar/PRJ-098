@extends('admin.layouts.app')
@section('title', 'Distributed Hub View')
@section('css')

@endsection
@section('content')
    <div class="container-fluid">
        <!-- end page title -->
        <div class="row">
            {{-- <form id="searchForm" method="get" action="<?= url()->current() ?>">
                <input type="hidden" name="per_page" id="perPageHidden" />
            </form> --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Hub Overview : @if ($hub->status_id == 1)
                                <span class="text-success">Active</span>
                            @else
                                <span class="text-success">Inactive</span>
                            @endif
                        </h4>
                        <div class="page-title-right btn-card-header">
                            @can('edit_hub', $permission)
                                <a class="btn btn-success waves-effect waves-light hubModelForm" data-toggle="modal"
                                    data-hub_id="{{ $hub->hub_id }}" data-hubid="{{ $hub->hubId }}"
                                    data-city="{{ $hub->city }}" data-state="{{ $hub->state }}"
                                    data-country="{{ $hub->country }}" data-slug="{{ $hub->slug }}"
                                    data-address1="{{ $hub->address_1 }}" data-address2="{{ $hub->address_2 }}"
                                    data-zipcode="{{ $hub->zip_code }}" data-hublimit="{{ $hub->hub_limit }}"
                                    data-fulladdress="{{ $hub->full_address }}" data-latitude="{{ $hub->latitude }}"
                                    data-longitude="{{ $hub->longitude }}" title="Edit Hub"
                                    style="cursor: pointer;margin-right: 5px;">Edit Hub</a>
                            @endcan
                            @can('add_inventry', $permission)
                                @if (request()->route('param') == 'vehicle')
                                    <a class="btn btn-outline-success waves-effect waves-light vehicleModelForm"
                                        data-toggle="modal" data-operation="add" data-hub_id="{{ $hub->hub_id }}"
                                        title="Add New NotVehicleification">Add Vehicle</a>
                                @endif
                            @endcan
                            {{-- <a href="" class="btn btn-outline-success waves-effect waves-light" title="Add New Notification">Assign an EV</a> --}}
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="detail_cnt">
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <h5> Hub Id : <span>{{ $hub->hubId }}</span></h5>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <h5>Hub Capacity: <span> {{ $hub->hub_limit }}</span></h5>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <h5>Vehicle Count in Hub : <span>{{ $vehicleCount }}</span></h5>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <h5>Total No. of Employees : <span>{{ $empCount }}</span></h5>
                                </div>
                                <div class="col-xl-3 col-md-3">
                                    <h5>Hub Address : <span>
                                            {{ $hub->address_1 }}{{ $hub->address_2 ? ', ' . $hub->address_2 : '' }}</span>
                                    </h5>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div>
                                        <h5>Pincode : <span> {{ $hub->zip_code }}</span></h5>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <h5>City : <span> {{ $hub->city }}</span></h5>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <h5>State : <span> {{ $hub->state }}</span></h5>
                                </div>

                                <div class="col-xl-6">
                                    <h5>Accessories in Hub : <span>
                                            @foreach ($accessoriesinHub as $access)
                                                {{ $access == 1 ? 'Helmet' : ($access == 2 ? ', T-Shirt' : ($access == 3 ? ', Mobile Holder' : '')) }}
                                            @endforeach
                                        </span>
                                    </h5>
                                </div>

                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div>
                <div class="col-12">
                    <div class="nav_cust_menu">
                        <ul>
                            <li><a href="{{ route('hub-view', ['slug' => request()->route('slug'), 'param' => 'vehicle']) }}"
                                    class="{{ request()->route('param') == 'vehicle' ? 'active' : '' }}">Hub Inventory</a>
                            </li>
                            <li><a href="{{ route('hub-view', ['slug' => request()->route('slug'), 'param' => 'employee']) }}"
                                    class="{{ request()->route('param') == 'employee' ? 'active' : '' }}">Employees</a>
                            </li>
                            <li><a href="{{ route('hub-view', ['slug' => request()->route('slug'), 'param' => 'accessories']) }}"
                                    class="{{ request()->route('param') == 'accessories' ? 'active' : '' }}">Accessories</a>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-filter">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);" class="btn btn-link"
                                            onclick="refreshPage('<?= url()->current() ?>');">
                                            <img src="{{ asset('public/assets/images/icons/refresh.svg') }}"
                                                alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <p>Total Record : <span>{{ $count }}</span></p>
                                    </li>
                                    <li>
                                        <p>Display up to :
                                        <div class="form-group">
                                            @include('admin.layouts.per_page')
                                        </div>
                                        </p>
                                    </li>
                                    <li>
                                        <?php
                                        $refTableId = 0;
                                        if (request()->route('param') == 'vehicle') {
                                            $refTableId = config('table.REF_TABLE.HUB_INVENTROY');
                                        } elseif (request()->route('param') == 'employee') {
                                            $refTableId = config('table.REF_TABLE.HUB_EMPLOYEE');
                                        } elseif (request()->route('param') == 'accessories') {
                                            $refTableId = config('table.REF_TABLE.HUB_ACCESSORIES');
                                        }
                                        ?>
                                        <button type="button" class="btn btn-success waves-effect waves-light"
                                            onclick="exportData('<?= $refTableId ?>', '<?= $hub->slug ?>');">
                                            <img src="{{ asset('public/assets/images/icons/download.svg') }}"
                                                alt="">
                                            Export
                                        </button>
                                    </li>
                                    @if (request()->route('param') == 'vehicle')
                                        <li>
                                            <a class="btn btn-success waves-effect waves-light vehicleFilterForm"
                                                data-toggle="modal" data-target="#vehicleFilterForm">Advance Search</a></h1>
                                        </li>
                                    @endif
                                    @can('add_user', $permission)
                                        @if (request()->route('param') == 'employee')
                                            <li>
                                                <a href="" class="btn btn-success waves-effect waves-light userModelForm"
                                                    data-toggle="modal" data-operation="add" data-hub_id="{{ $hub->hub_id }}"
                                                    data-empid="{{ $hub->max_emp_id }}" data-target="#userModelForm">Create New
                                                    Employee</a></h1>
                                            </li>
                                        @endif
                                    @endcan
                                    @can('raise_request', $permission)
                                        @if (request()->route('param') == 'accessories' && !empty(Auth::user()->hub_id))
                                            <li>
                                                <a class="btn btn-success waves-effect waves-light raiseModelForm"
                                                    data-toggle="modal">Raise Request</a></h1>
                                            </li>
                                        @endif
                                    @endcan
                                </ul>
                            </div>
                            <div class="table-rep-plugin">
                                @if (request()->route('param') == 'vehicle')
                                    @include('admin.hub.vehicle_listing')
                                @elseif (request()->route('param') == 'employee')
                                    @include('admin.hub.employee_listing')
                                @elseif (request()->route('param') == 'accessories')
                                    @include('admin.hub_part_accessories.list')
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
@endsection
@section('js')
    <!-- Add role model -->
    <script type="text/javascript">
        $(document).ready(function() {
            function toggleReadOnly(state) {
                $('.readOnlyClass').prop('readonly', state);
            }

            $('.userModelForm').on('click', function(event) {
                event.preventDefault();
                var operationType = $(this).data(
                    'operation'); // Retrieve the operation type from data attribute
                if (operationType === 'add') {
                    var hub_id = $(this).data('hub_id');
                    var empid = $(this).data('empid');
                    empid = empid ? parseInt(empid) + 1 : 101;
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
                var slug = $(this).data('slug');
                if (slug) {
                    var first_name = $(this).data('fname');
                    var last_name = $(this).data('lname');
                    var email = $(this).data('email');
                    var phone = $(this).data('phone');
                    var roleid = $(this).data('roleid');
                    var rolename = $(this).data('rolename');
                    var hub_id = $(this).data('hub_id');
                    var empid = $(this).data('empid');
                    $("#first_name").val(first_name);
                    $("#last_name").val(last_name);
                    $("#email").val(email);
                    $("#phone").val(phone);
                    $("#hub_id").val(hub_id);
                    //$("#role_id").find(':selected').attr('data-roleid')
                    $("#role_id").val(roleid);
                    $("#rolename").val(rolename);
                    $("#hub_id").val(hub_id);
                    $("#employee_id").val(empid);
                    $("#uSlug").val(slug);
                } else {
                    $("#userModalLabel").html('Add Employee');
                    $("#submitUser").html('Add');
                }
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
                        $('#message').html("<span class='sussecmsg'>" + data.message +
                            "</span>");
                        $('#submitUser').prop('disabled', false);
                        $('#submitUser').html('Update');
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    },
                    errors: function() {
                        $('#message').html(
                            "<span class='sussecmsg'>Somthing went wrong!</span>");
                    }
                });
            });

            // vehicle model

            $('.vehicleModelForm').on('click', function(event) {
                event.preventDefault();
                var operationType = $(this).data(
                    'operation'); // Retrieve the operation type from data attribute
                if (operationType === 'add') {
                    var hub_id = $(this).data('hub_id');
                    $('#chassis_number').removeAttr('readonly');
                    $('#chassis_number').removeClass('readOnlyClass');
                    $('#hub_id').val(hub_id);

                } else if (operationType === 'update') {
                    $('#chassis_number').attr('readonly', true);
                }

                $('#vehicleModelForm').modal('show');
                // var hub_id = $(this).data('hub_id');
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
                    var vSlug = $(this).data('slug');
                    var total_range = $(this).data('total_range');


                    $("#title").val(title);
                    $("#hub_id").val(hub_id);
                    $("#ev_number").val(ev_number);
                    $("#hub_id").val(hub_id);
                    $("#chassis_number").val(chassis_number);
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
                    $("#vSlug").val(vSlug);
                    $("#speed").val(speed);
                    $("#per_day_rent").val(per_day_rent);
                    $("#km_per_charge").val(km_per_charge);
                    $("#total_range").val(total_range);

                    var imageUrl = "{{ asset('public/upload/product') }}/" + image;
                    if (image) {
                        $('.selectImageRemove').html(
                            '<img class="upload_des_preview clickable selectedImage" src="' + imageUrl +
                            '" alt = "vehicle image" >');
                    } else {
                        $('.selectImageRemove').html(
                            '<img class="upload_des_preview clickable selectedImage" src="{{ asset('public/assets/images/uploadimg.png') }}" alt = "vehicle image" >'
                        );
                    }

                    if (is_display_on_app == 1) {
                        $('#remember-check').prop('checked', true);
                    } else {
                        $('#remember-check').prop('checked', false);
                    }
                    $("#vehicleModalLabel").html('Update Vehicle');
                    $("#submitVehicle").html('Update');
                } else {
                    $("#vehicleModalLabel").html('Add Vehicle');
                    $("#submitVehicle").html('Add');
                }

            });

            $('#submitVehicle').click(function(e) {
                e.preventDefault();
                var hub_id = $('#hub_id').val();
                var slug = $('#vSlug').val();
                var updateurl = $('#updateurl').val();
                var title = $('#title').val();
                if (title == "") {
                    $(".title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                }
                var evnumber = $('#ev_number').val();
                if (evnumber == "") {
                    $(".ev_number_error").html('This field is required!');
                    $("input#ev_number").focus();
                    return false;
                }
                var chassis = $('#chassis_number').val();
                if (chassis == "") {
                    $(".chassis_number_error").html('This field is required!');
                    $("input#chassis_number").focus();
                    return false;
                }
                var ev_type_id = $('#ev_type_id').val();
                if (ev_type_id == "") {
                    $(".ev_type_id_error").html('This field is required!');
                    $("input#ev_type_id").focus();
                    return false;
                }
                var speed = $('#speed').val();
                if (speed == "") {
                    $(".speed_error").css("display", "");
                    $(".speed_error").html('This field is required!');

                    $("input#speed").focus();
                    return false;
                }
                var range = $('#range').val();
                if (range == "") {
                    $(".range_error").css("display", "");
                    $(".range_error").html('This field is required!');
                    $(".range_error").html('');
                    $("input#km_per_charge").focus();
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
                    url: slug ? updateurl : "{{ route('add-product') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#message').html("<span class='sussecmsg'>" + data.message +
                            "</span>");
                        $('#submitForm').prop('disabled', false);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);

                    },
                    errors: function() {
                        $('#message').html(
                            "<span class='sussecmsg'>Somthing went wrong!</span>");
                    }
                });
            });

            //raise request
            $('.raiseModelForm').click(function() {
                $('#raiseModelForm').modal('show');
            });

            //Customer overview
            $('.customerOverviewModelForm').on('click', function(event) {
                event.preventDefault();
                $('#customerOverviewModelForm').modal('show');
                var order_slug = $(this).data('order_slug');
                var ev_number = $(this).data('ev_number');
                var hubid = $(this).data('hubid');
                var chassis_number = $(this).data('chassis_number');
                var customer_id = $(this).data('customer_id');
                var profile_category_name = $(this).data('profile_category_name');
                var ev_category_name = $(this).data('ev_category_name');
                var cluster_manager = $(this).data('cluster_manager');
                var tl_name = $(this).data('tl_name');
                var client_name = $(this).data('client_name');
                var client_address = $(this).data('client_address');
                var kyc_status = $(this).data('kyc_status');

                $("#orderEVNumber").val(ev_number);
                $("#orderChassisNumber").val(chassis_number);
                $("#orderHubId").val(hubid);
                $("#orderCustomerId").val(customer_id);
                $("#orderProfile").val(profile_category_name);
                $("#orderAssignDate").val('data-roleid')
                $("#orderEvCategory").val(ev_category_name);
                $("#orderKycStatus").val(kyc_status);
                $("#orderClusterManager").val(cluster_manager);
                $("#orderClientName").val(client_name);
                $("#orderTlName").val(tl_name);
                $("#orderClientAddress").val(client_address);
            });
            //Customer overview
            $('.vehicleFilterForm').on('click', function(event) {
                event.preventDefault();
                $('#vehicleFilterForm').modal('show');
            });
            $('#ev_type_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                var value1 = selectedOption.data('value1');
                var value2 = selectedOption.data('value2');
                var value3 = selectedOption.data('value3');
                var value4 = selectedOption.data('value4');

                $('#km_per_charge').val(value1);
                $('#speed').val(value2);
                $('#per_day_rent').val(value3);
                $('#total_range').val(value4);
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
                        success: function(response) {},
                        error: function(xhr, status, error) {}
                    });
                } else if (params === 'vehicle') {}
            }
        }
    </script>
    @include('admin.models.hub_model')
@endsection
