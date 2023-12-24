<div class="table-rep-plugin">
    <div class="table-responsive mb-0" data-pattern="priority-columns">
        <table id="tech-companies-1" class="table">
            <thead>
                <tr>
                    <th>EV NUMBER</th>
                    <th>CHASSIS NUM</th>
                    <th>GPS DEVICE</th>
                    <th>EV CATEGORY</th>
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
                    <td>{{$vehicle->profile_category_name}}</td>
                    <td>
                        @if ($vehicle->status_id == 1)
                        <label class="text-success">Active</label>
                        @elseif($vehicle->status_id == 2)
                        <label class="text-danger">Inactive</label>
                        @elseif($vehicle->status_id == 3)
                        <label class="text-warning">Non Functional</label>
                        @elseif($vehicle->status_id == 4)
                        <label class="text-info">Not Available</label>
                        @endif
                    </td>
                    <td>Paid</td>
                    <td>Verified</td>

                    <td>
                        <div class="dropdown">
                            <a href="#" class="btn btn-link p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu">
                                @can('edit_inventry', $permission)
                                <a class="dropdown-item vehicleModelForm" data-toggle="modal" data-product_id="{{ $vehicle->product_Id }}" data-slug="{{ $vehicle->slug }}" data-hub_id="{{ $vehicle->hub_id }}" data-title="{{ $vehicle->title }}" data-ev_number="{{ $vehicle->ev_number }}" data-ev_type_id="{{ $vehicle->ev_type_id }}" data-ev_category_id="{{ $vehicle->ev_category_id }}" data-profile_category="{{ $vehicle->profile_category }}" data-speed="{{ $vehicle->speed }}" data-rent_cycle="{{ $vehicle->rent_cycle }}" data-per_day_rent="{{ $vehicle->per_day_rent }}" data-bettery_type="{{ $vehicle->bettery_type }}" data-km_per_charge="{{ $vehicle->km_per_charge }}" data-description="{{ $vehicle->description }}" data-is_display_on_app="{{ $vehicle->is_display_on_app }}" data-chassis_number="{{ $vehicle->chassis_number }}" data-gps_emei_number="{{ $vehicle->gps_emei_number }}" data-image="{{ $vehicle->image }}" data-bike_type="{{ $vehicle->bike_type }}" data-status="{{ $vehicle->status_id }}" data-updateurl="{{ route('update-product',['slug'=>$vehicle->slug]) }}" title="Edit Vehicle" style="cursor: pointer;margin-right: 5px;"></i><i class="fa fa-edit"></i> Edit
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
    <div class="modal-dialog" style="max-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="vehiclForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="example-title-input" class="form-label">Title* &nbsp; <span class="spanColor title_error"></span></label>
                            <input type="hidden" class="form-control" name="slug" id="slug">
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
                        <div class="col-md-4 mb-3">
                            <label for="example-title-input" class="form-label">Speed (km/h)</label>
                            <input class="form-control" type="text" name="speed" id="speed" value="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="example-title-input" class="form-label">Single charge Run Time(km)</label>
                            <input class="form-control" type="text" name="km_per_charge" id="km_per_charge" value="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="example-title-input" class="form-label">Rent per Day(Rs)</label>
                            <input class="form-control" type="text" name="per_day_rent" id="per_day_rent" value="">
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
                        <div class="col-md-6 mb-3">
                            <label for="ev_category" class="form-label">EV Category</label>
                            <select class="form-control selectBasic" name="ev_category" id="ev_category">
                                @foreach($ev_categories as $key => $ev_category)
                                <option value="{{$ev_category}}">{{$ev_category == 1 ? "Two Wheeler" : "Three Wheeler"}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ev_type_id" class="form-label">EV Type</label>
                            <select class="form-control selectBasic" name="ev_type_id" id="ev_type_id">
                                <option value="">Select EV Type</option>
                                @foreach($ev_types as $key => $ev_type)
                                <option value="{{$ev_type->ev_type_id}}">{{$ev_type->ev_type_name}}</option>
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
                                <label for="customFile" class="selectImageRemove">
                                    <img class="upload_des_preview clickable selectedImage " src="{{asset('public/assets/images/uploadimg.png')}}" alt="example placeholder" />
                                </label>
                                <input type="file" class="form-control d-none customFile" name="image" id="customFile" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_id" class="form-label">Status</label>
                            <select class="form-control selectBasic" name="status_id" id="status_id">
                                @foreach($vehicleStatus as $key => $status_id)
                                <option value="{{$status_id}}">{{$status_id == 1 ? "Active" : ($status_id == 2 ? 'Inactive' : ($status_id == 3 ? 'Non Functional' : 'Not Available'))}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="hub_id" class="form-label">Product Visivility on App</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-check" name="is_display_on_app" checked>
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