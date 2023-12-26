<div class="table-rep-plugin">
    @if (count($hub_parts) > 0)
    <div class="table-responsive mb-0" data-pattern="priority-columns">
        <table id="tech-companies-1" class="table">
            <thead>
                <tr>
                    @if(request()->route('param') != 'accessories')
                    <th>Hub ID</th>
                    <th>Hub Location</th>
                    <th>Hub Manager</th>
                    @endif
                    <th>Accessories</th>
                    <th>Req Qty</th>
                    <th>Ass Qty</th>
                    <th>Requested Cost</th>
                    <th>Assigned Cost</th>
                    <th>Requested Date</th>
                    <th>Assigned Date</th>
                    <th>Status</th>
                    @if(request()->route('param') != 'accessories')
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($hub_parts as $key => $hub_part)
                @if(is_object($hub_part))
                <tr>
                    @if(request()->route('param') != 'accessories')
                    <td>
                        <a class="dropdown-item viewModelForm text-info" data-toggle="modal" data-manager="{{ $hub_part->name }}" data-hubid="{{ $hub_part->hubId }}" data-city="{{ $hub_part->city }}" data-acceessories="{{ $hub_part->accessories_category_id }}" data-requested_qty="{{ $hub_part->requested_qty }}" data-accessories_price="{{ $hub_part->accessories_price }}" data-assigned_price="{{ $hub_part->assigned_price }}" data-assigned_qty="{{ $hub_part->assigned_qty }}" data-requested_remark="{{ $hub_part->requested_remark }}" data-assigned_remark="{{ $hub_part->assigned_remark }}" data-statusid="{{ $hub_part->status_id }}" data-requested_date="{{ dateFormat($hub_part->requested_date) }}" data-assign_date="{{ dateFormat($hub_part->assign_date) }}" title="View Hub Part Accessories" style="cursor: pointer;margin-right: 5px;">{{ $hub_part->hubId }}
                        </a>

                    </td>
                    <td>{{ $hub_part->city }}</td>
                    <td>{{ $hub_part->name }}</td>
                    @endif
                    <td>{{ $hub_part->accessories }}</td>
                    <td>{{ $hub_part->requested_qty }}</td>
                    <td>{{ $hub_part->assigned_qty }}</td>
                    <td>₹{{ $hub_part->accessories_price * $hub_part->requested_qty }}</td>
                    <td>₹{{ $hub_part->accessories_price * $hub_part->assigned_qty }}</td>
                    <td>{{ dateFormat($hub_part->requested_date) }}</td>
                    <td>{{ dateFormat($hub_part->assign_date) }}</td>
                    <td>
                        @if ($hub_part->status_id == 1)
                        <label class="text-success">Raised</label>
                        @elseif($hub_part->status_id == 2)
                        <label class="text-warning">Shipped</label>
                        @elseif($hub_part->status_id == 3)
                        <label class="text-info">Completed</label>
                        @elseif($hub_part->status_id == 4)
                        <label class="text-danger">Rejected</label>
                        @endif
                    </td>
                    @if(request()->route('param') != 'accessories')
                    <td>
                        <div class="dropdown">
                            <a href="#" class="btn btn-link p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu">
                                @can('assiegn_request', $permission)
                                @if ($hub_part->status_id == 3 || $hub_part->status_id == 4)
                                <a class="dropdown-item assignAccessoriesLink disabled" data-toggle="modal" title="Dissabled" style="cursor: pointer;margin-right: 5px;" disabled><i class="fa fa-ban"></i> Assign Accessories
                                </a>
                                @else
                                <a class="dropdown-item assignModelForm" data-toggle="modal" data-slug="{{ $hub_part->slug }}" data-hubid="{{ $hub_part->hubId }}" data-accessories_category="{{ $hub_part->accessories_category_id }}" data-requested_qty="{{ $hub_part->requested_qty }}" data-per_piece_price="{{ $hub_part->price }}" title="Assign Accessories" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-tasks"></i> Assign Accessories
                                </a>
                                @endif
                                @endcan
                                <a class="dropdown-item viewModelForm" data-toggle="modal" data-manager="{{ $hub_part->name }}" data-hubid="{{ $hub_part->hubId }}" data-city="{{ $hub_part->city }}" data-acceessories="{{ $hub_part->accessories_category_id }}" data-requested_qty="{{ $hub_part->requested_qty }}" data-accessories_price="{{ $hub_part->accessories_price }}" data-assigned_price="{{ $hub_part->assigned_price }}" data-assigned_qty="{{ $hub_part->assigned_qty }}" data-requested_remark="{{ $hub_part->requested_remark }}" data-assigned_remark="{{ $hub_part->assigned_remark }}" data-statusid="{{ $hub_part->status_id }}" data-requested_date="{{ dateFormat($hub_part->requested_date) }}" data-assign_date="{{ dateFormat($hub_part->assign_date) }}" title="View Hub Part Accessories" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </td>
                    @endif
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $hub_parts->withQueryString()->links('pagination::bootstrap-4') }}
    </div>

    @else
    <div>
        @include('admin.common.no_record')
    </div>
    @endif
</div>
<div class="modal fade" id="raiseModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Raise Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data" id="raiseRequestForm" action="{{route('add-accessories-hub-part')}}">
                <div class="modal-body">

                    @csrf
                    <div class="form-group mb-1">
                        <div class="form-group mb-1">
                            <label for="choices-single-no-search" class="form-label font-size-13 text-muted">Accessories Category</label>
                            <select class="form-control selectBasic" name="accessories_category_id" id="accessories_category_id">
                                @foreach($accessories_categories as $key => $accCat)
                                <option value="{{$accCat}}">{{$accCat == 1 ? "Helmet" : ($accCat == 2 ? "T-Shirt" : "Mobile Holder")}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="role-name" class="col-form-label">Request Quantity &nbsp;<span class="spanColor onlyDigit_error"></span></label>
                            <input type="text" name="requested_qty" class="form-control onlyDigit" id="requested_qty">
                        </div>


                        <div class="form-group mb-1">
                            <label for="role-name" class="col-form-label">Remarks</label>
                            <textarea id="requested_remark" name="requested_remark" class="form-control remark" rows="5"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <span class="text-success d-block" id="message" style="margin-right: 10px"></span>

                    <button type="submit" class="btn btn-success waves-effect waves-light">Update
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>