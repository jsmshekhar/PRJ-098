<div class="table-rep-plugin">
    <div class="table-responsive mb-0" data-pattern="priority-columns">
        <table id="tech-companies-1" class="table">
            <thead>
                <tr>
                    <th>EV NUMBER</th>
                    <th>CHASSIS NUM</th>
                    <th>GPS DEVICE</th>
                    <th>EV CATEGORY</th>
                    <th>Customer Id</th>
                    <th>PROFILE</th>
                    <th>STATUS</th>
                    <th>PAYMENT STATUS</th>
                    <th>KYC STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $key => $vehicle)
                <tr>
                    <td>{{$vehicle->ev_number}}</td>
                    <td>{{$vehicle->chassis_number}}</td>
                    <td>{{$vehicle->gps_emei_number ? "Installed" : "No Device"}}</td>
                    <td>{{$vehicle->ev_category_name}}</td>
                    <td>@if($vehicle->customer_id)
                        <a class="customerOverviewModelForm" data-toggle="modal" data-ev_number="{{ $vehicle->ev_number }}" data-order_slug="{{ $vehicle->order_slug }}" data-hubid="{{ $hub->hubId }}" data-chassis_number="{{ $vehicle->chassis_number }}" data-customer_id="CUS{{ $vehicle->customer_id }}" data-profile_category_name="{{ $vehicle->profile_category_name }}" data-ev_category_name="{{ $vehicle->ev_category_name }}" data-kyc_status="{{ $vehicle->kycStatus }}" data-cluster_manager="{{ $vehicle->cluster_manager }}" data-tl_name="{{ $vehicle->tl_name }}" data-client_name="{{ $vehicle->client_name }}" data-client_address="{{ $vehicle->client_address }}" title="Customer Overview" style="cursor: pointer;margin-right: 5px;"> {{"CUS".$vehicle->customer_id}}
                        </a>
                        @else
                        NA
                        @endif
                    </td>
                    <td>{{$vehicle->profile_category_name}}</td>
                    <td>
                        @if ($vehicle->status_id == 1)
                        <label class="text-success">Active</label>
                        @elseif($vehicle->status_id == 2)
                        <label class="text-danger">Inactive</label>
                        @elseif($vehicle->status_id == 3)
                        <label class="text-warning">NF</label>
                        @elseif($vehicle->status_id == 4)
                        <label class="text-info">Assigned</label>
                        @elseif($vehicle->status_id == 6)
                        <label class="text-info">RFD</label>
                        @endif
                    </td>
                    <td> @if ($vehicle->payment_status == 1)
                        <label class="text-success">Paid</label>
                        @elseif($vehicle->payment_status == 2)
                        <label class="text-warning">Pending</label>
                        @elseif($vehicle->payment_status == 3)
                        <label class="text-danger">Failed</label>
                        @elseif($vehicle->payment_status == 4)
                        <label class="text-info">Rejected</label>
                        @else
                        <label class="text-secondary">NA</label>
                        @endif
                    </td>
                    <td> @if ($vehicle->kyc_status == 1)
                        <label class="text-success">Verified</label>
                        @elseif($vehicle->kyc_status == 2)
                        <label class="text-info">Pending</label>
                        @elseif($vehicle->kyc_status == 3)
                        <label class="text-danger">Red Flag</label>
                        @elseif($vehicle->kyc_status == 3)
                        <label class="text-danger">Not Verified</label>
                        @else
                        <label class="text-secondary">NA</label>
                        @endif
                    </td>

                    <td>
                        <div class="dropdown">
                            <a href="#" class="btn btn-link p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu">
                                @can('edit_inventry', $permission)
                                <a class="dropdown-item vehicleModelForm" data-toggle="modal" data-product_id="{{ $vehicle->product_id }}" data-slug="{{ $vehicle->slug }}" data-hub_id="{{ $vehicle->hub_id }}" data-title="{{ $vehicle->title }}" data-ev_number="{{ $vehicle->ev_number }}" data-ev_type_id="{{ $vehicle->ev_type_id }}" data-ev_category_id="{{ $vehicle->ev_category_id }}" data-profile_category="{{ $vehicle->profile_category }}" data-speed="{{ $vehicle->speed }}" data-rent_cycle="{{ $vehicle->rent_cycle }}" data-per_day_rent="{{ $vehicle->per_day_rent }}" data-bettery_type="{{ $vehicle->bettery_type }}" data-km_per_charge="{{ $vehicle->km_per_charge }}" data-total_range="{{ $vehicle->total_range }}" data-description="{{ $vehicle->description }}" data-is_display_on_app="{{ $vehicle->is_display_on_app }}" data-chassis_number="{{ $vehicle->chassis_number }}" data-gps_emei_number="{{ $vehicle->gps_emei_number }}" data-image="{{ $vehicle->image }}" data-bike_type="{{ $vehicle->bike_type }}" data-status="{{ $vehicle->status_id }}" data-updateurl="{{ route('update-product',['slug'=>$vehicle->slug]) }}" title="Edit Vehicle" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i> Edit
                                </a>
                                @endcan
                                @can('delete_inventry', $permission)
                                <form id="delete-form-{{$vehicle->slug}}" method="post" action="{{ route('product-delete', $vehicle->slug) }}" style="display: none;">
                                    @csrf
                                    {{ method_field('POST') }} <!-- delete query -->
                                </form>
                                <a href="" class="dropdown-item" onclick="
                                    if (confirm('Are you sure, You want to delete?'))
                                    {
                                        event.preventDefault();
                                        document.getElementById('delete-form-{{$vehicle->slug}}').submit();
                                    }else {
                                        event.preventDefault();
                                    }
                                    " title="delete">
                                    <i class="fa fa-trash" style="color:#d74b4b;"></i> Delete
                                </a>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $vehicles->withQueryString()->links('pagination::bootstrap-4') }}
</div>

<div class="modal fade" id="vehicleModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehicleModalLabel">Add Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="vehiclForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="example-title-input" class="form-label">Title* &nbsp; <span class="spanColor title_error"></span></label>
                            <input type="hidden" class="form-control" name="slug" id="vSlug">
                            <input type="hidden" class="form-control" name="hub_id" id="hub_id">
                            <input type="hidden" class="form-control" name="updateurl" id="updateurl">
                            <input class="form-control" type="text" name="title" id="title" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="example-title-input" class="form-label">EV Number* &nbsp; <span class="spanColor ev_number_error"></label>
                            <input class="form-control" type="text" name="ev_number" id="ev_number" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="example-title-input" class="form-label">Chassis Number* &nbsp; <span class="spanColor chassis_number_error"></label>
                            <input class="form-control readOnlyClass" type="text" name="chassis_number" id="chassis_number" value="" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="example-title-input" class="form-label">GPS IMEI</label>
                            <input class="form-control" type="text" name="gps_emei_number" id="gps_emei_number" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ev_category" class="form-label">EV Category </label>
                            <select class="form-control selectBasic" name="ev_category" id="ev_category">
                                @foreach($ev_categories as $key => $ev_category)
                                <option value="{{$ev_category}}">{{$ev_category == 1 ? "Two Wheeler" : "Three Wheeler"}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ev_type_id" class="form-label">EV Type* &nbsp;<span class="spanColor ev_type_id_error"></span></label>
                            <select class="form-control selectBasic" name="ev_type_id" id="ev_type_id">
                                <option value=""> Select EV Type </option>
                                @foreach($ev_types as $key => $ev_type)
                                <option value="{{$ev_type->ev_type_id}}" data-value1="{{$ev_type->speed}}" data-value2="{{$ev_type->range}}" data-value3="{{$ev_type->rs_perday}}" data-value4="{{$ev_type->total_range}}">{{$ev_type->ev_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="speed" class="form-label">Speed* (km/h) &nbsp;<span class="spanColor onlyDigitSpeed_error speed_error" id="speed_error"></span></label>
                            <input class="form-control onlyDigitSpeed" type="text" name="speed" id="speed" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="range" class="form-label ">Single charge Run* (km) &nbsp;<span class="spanColor onlyDigit_error range_error" id="range_error"></span></label>
                            <input class="form-control onlyDigit" type="text" name="km_per_charge" id="km_per_charge" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="example-title-input" class="form-label">Rent per Day(Rs) &nbsp; <span class="spanColor onlyDigitRent_error rent_error" id="rent_error"> </span></label>
                            <input class="form-control onlyDigitRent" type="text" name="per_day_rent" id="per_day_rent" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="range" class="form-label ">Monthly Range (km) &nbsp;<span class="spanColor onlyDigitMonthly_error monthly_range_error" id="monthly_range_error"></span></label>
                            <input class="form-control onlyDigitMonthly" type="text" name="total_range" id="total_range" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile_category" class="form-label">Profile Category</label>
                            <select class="form-control selectBasic" name="profile_category" id="profile_category">
                                @foreach($profile_categories as $key => $profile_category)
                                <option value="{{$profile_category}}">{{$profile_category == 1 ? "Corporate" : ($profile_category == 2 ? 'Individual' : ($profile_category == 3 ? 'Student' : 'Vendor'))}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rent_cycle" class="form-label">Rent Cycle</label>
                            <select class="form-control selectBasic" name="rent_cycle" id="rent_cycle">
                                @foreach($rent_cycles as $key => $rent_cycle)
                                <option value="{{$rent_cycle}}">{{$rent_cycle == 15 ? "15 Days" : "30 Days"}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bike_type" class="form-label">Bike Type</label>
                            <select class="form-control selectBasic" name="bike_type" id="bike_type">
                                @foreach($bike_types as $key => $bike_type)
                                <option value="{{$bike_type}}">{{$bike_type == 1 ? "Cargo Bike" : "Normal Bike"}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="battery_type" class="form-label">Battery Type</label>
                            <select class="form-control selectBasic" name="battery_type" id="battery_type">
                                @foreach($battery_types as $key => $battery_type)
                                <option value="{{$battery_type}}">{{$battery_type == 1 ? "Swappable" : "Fixed"}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Description &nbsp; <span class="spanColor description_error"></span></label>
                            <textarea id="description" name="description" class="form-control" rows="6" placeholder="Write here." style="height: 150px;"></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="title" class="form-label">Image Upload</label>
                            <div class="">
                                <label for="customHubFile" class="selectImageRemove">
                                    <img class="upload_des_preview clickable selectedImage" src="{{asset('public/assets/images/uploadimg.png')}}" alt="example placeholder" />
                                </label>
                                <input type="file" class="form-control d-none customFile" name="image" id="customHubFile" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_id" class="form-label">Status</label>
                            <select class="form-control selectBasic" name="status_id" id="status_id">
                                @foreach($evStatus as $key => $status_id)
                                <option value="{{$key}}">{{$status_id}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="hub_id" class="form-label">Product Visivility on App</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-check" name="is_display_on_app">
                                <label class="form-check-label mt-1" for="remember-check">
                                    &nbsp; The product is displayed on the app.
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <span class="text-success d-block" id="message"></span>
                <button type="button" id="submitVehicle" class="btn btn-success waves-effect waves-light">Add
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Customer overview -->

<div class="modal fade" id="customerOverviewModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Overview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="customerOverviewForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">EV Number</label>
                            <input type="hidden" class="form-control" name="orderSlug" id="orderSlug">
                            <input class="form-control readOnlyClass" type="text" id="orderEVNumber" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chassis Number</label>
                            <input class="form-control readOnlyClass" type="text" id="orderChassisNumber" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hub Id</label>
                            <input class="form-control readOnlyClass" type="text" id="orderHubId" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Id</label>
                            <input class="form-control readOnlyClass" type="text" id="orderCustomerId" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Profile Category</label>
                            <input class="form-control readOnlyClass" type="text" id="orderProfile" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assigned Date</label>
                            <input class="form-control readOnlyClass" type="text" id="orderAssignDate" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">EV Category</label>
                            <input class="form-control readOnlyClass" type="text" id="orderEvCategory" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">KYC Status</label>
                            <input class="form-control readOnlyClass" type="text" id="orderKycStatus" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cluster Manager</label>
                            <input class="form-control readOnlyClass" type="text" id="orderClusterManager" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">TL Name</label>
                            <input class="form-control readOnlyClass" type="text" id="orderTlName" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client Name</label>
                            <input class="form-control readOnlyClass" type="text" id="orderClientName" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client Address</label>
                            <input class="form-control readOnlyClass" type="text" id="orderClientAddress" readonly>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="vehicleFilterForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advance Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="get" action="<?= url()->current() ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="example-title-input" class="form-label">EV Number</label>
                            <input type="hidden" name="is_search" value="1" />
                            <input class="form-control" type="text" name="ev" value="<?= isset($_GET['ev']) ? $_GET['ev'] : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="example-title-input" class="form-label">Chassis Number</label>
                            <input class="form-control" type="text" name="ch_no" value="<?= isset($_GET['ch_no']) ? $_GET['ch_no'] : '' ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">EV Category </label>
                            <select class="form-control selectBasic" name="ev_cat">
                                <option value=""> Select EV Category</option>
                                @foreach($ev_categories as $key => $ev_category)
                                <option value="{{$ev_category}}" <?= (isset($_GET['ev_cat']) && $ev_category == $_GET['ev_cat']) ? 'selected' : '' ?>>{{$ev_category == 1 ? "Two Wheeler" : "Three Wheeler"}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile_category" class="form-label">Profile Category</label>
                            <select class="form-control selectBasic" name="pro_cat">
                                <option value=""> Select Profile Category</option>
                                @foreach($profile_categories as $key => $profile_category)
                                <option value="{{$profile_category}}" <?= (isset($_GET['pro_cat']) && $profile_category == $_GET['pro_cat']) ? 'selected' : '' ?>>{{$profile_category == 1 ? "Corporate" : ($profile_category == 2 ? 'Individual' : ($profile_category == 3 ? 'Student' : ($profile_category == 4 ? 'Vendor' : '')))}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_id" class="form-label">GPS Device</label>
                            <select class="form-control selectBasic" name="gps">
                                <option value=""> Select GPS Device</option>
                                @foreach($gpsDevice as $key => $device)
                                <option value="{{$key}}" <?= (isset($_GET['gps']) && $key == $_GET['gps']) ? 'selected' : '' ?>>{{$device}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_id" class="form-label">Status</label>
                            <select class="form-control selectBasic" name="status">
                                <option value=""> Select Status</option>
                                @foreach($evStatus as $key => $status_id)
                                <option value="{{$key}}" <?= (isset($_GET['status']) && $key == $_GET['status']) ? 'selected' : '' ?>>{{$status_id}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rent_cycle" class="form-label">Payment Status</label>
                            <select class="form-control selectBasic" name="pay_status">
                                <option value="">Select Payment Status</option>
                                @foreach($paymentStatus as $key => $pay_status)
                                <option value="{{$key}}" <?= (isset($_GET['pay_status']) && $key == $_GET['pay_status']) ? 'selected' : '' ?>>{{$pay_status}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rent_cycle" class="form-label">Kyc Status</label>
                            <select class="form-control selectBasic" name="kyc">
                                <option value="">Select Kyc Status</option>
                                @foreach($kycStatus as $key => $kyc_status)
                                <option value="{{$key}}" <?= (isset($_GET['kyc']) && $key == $_GET['kyc']) ? 'selected' : '' ?>>{{$kyc_status}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>