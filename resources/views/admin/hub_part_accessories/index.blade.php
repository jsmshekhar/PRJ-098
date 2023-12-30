@extends('admin.layouts.app')
@section('title', 'Hub parts and Accessories')
@section('css')
<style>
    textarea.remark {
        height: 120px;
    }

    .assignAccessoriesLink {
        pointer-events: none !important;
        color: #999999d1 !important;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Advance Search
                                </button>
                                <div class="collaps_btns">
                                    <button type="button" class="btn btn-outline-danger waves-effect waves-light" onclick="clearSearch('<?= url()->current() ?>');">Clear</button>
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="submitSearchForm();">Search</button>
                                </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="searchForm" method="get" action="<?= url()->current() ?>">
                                        <input type="hidden" name="is_search" id="isSearchHidden" value="0" />
                                        <input type="hidden" name="per_page" id="perPageHidden" />
                                        <input type="hidden" name="is_export" id="isExportHidden" />
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Hub Id</label>
                                                    <input type="text" class="form-control" name="hubid" value="<?= isset($_GET['hubid']) ? $_GET['hubid'] : '' ?>" />
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Hub Location</label>
                                                    <input type="text" class="form-control" name="hub_loc" value="<?= isset($_GET['hub_loc']) ? $_GET['hub_loc'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Manager First Name</label>
                                                    <input type="text" class="form-control" name="fname" value="<?= isset($_GET['fname']) ? $_GET['fname'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Manager Last Name</label>
                                                    <input type="text" class="form-control" name="lname" value="<?= isset($_GET['lname']) ? $_GET['lname'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Accessories Category</label>
                                                    <select class="form-control selectBasic" name="aci" id="accessoriesCategoryId">
                                                        <option value="">Select</option>
                                                        @foreach($accessories_categories as $key => $accCat)
                                                        <option value="{{$accCat}}" <?= (isset($_GET['aci']) && $_GET['aci'] == $accCat) ? 'selected' : '' ?>>{{$accCat == 1 ? "Helmet" : ($accCat == 2 ? "T-Shirt" : "Mobile Holder")}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label"> Status</label>
                                                    <select class="form-control selectBasic" name="status" id="status">
                                                        <option value="">Select</option>
                                                        <option value="1" <?= (isset($_GET['status']) && $_GET['status'] == 1) ? 'selected' : '' ?>>Raised</option>
                                                        <option value="2" <?= (isset($_GET['status']) && $_GET['status'] == 2) ? 'selected' : '' ?>>Shipped</option>
                                                        <option value="3" <?= (isset($_GET['status']) && $_GET['status'] == 3) ? 'selected' : '' ?>>Completed</option>
                                                        <option value="4" <?= (isset($_GET['status']) && $_GET['status'] == 4) ? 'selected' : '' ?>>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- end accordion -->
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-filter">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="btn btn-link">
                                    <img src="{{ asset('public/assets/images/icons/refresh.svg') }}" alt="" onclick="refreshPage();">
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
                            @if (count($hub_parts) > 0)
                            <li>
                                <button type="button" class="btn btn-success waves-effect waves-light" onclick="exportData('<?= config('table.REF_TABLE.RIDER') ?>');">
                                    <img src="{{ asset('public/assets/images/icons/download.svg') }}" alt="">
                                    Export
                                </button>
                            </li>
                            @if(session('message'))
                            <li id="successMessage">
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            </li>
                            @endif
                            @can('raise_request', $permission)
                            @if(!empty(Auth::user()->hub_id))
                            <li style="float:right">
                                <a class="btn btn-success waves-effect waves-light raiseModelForm" data-toggle="modal">Raise Request</a></h1>
                            </li>
                            @endif
                            @endcan
                            @endif
                        </ul>
                    </div>
                    @include('admin.hub_part_accessories.list')
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>



<div class="modal fade" id="assignModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Assign Accessories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data" id="assignRequestForm" action="{{route('update-accessories-hub-part')}}">
                <div class="modal-body">

                    @csrf
                    <div class="form-group mb-1">
                        <div class="form-group mb-1">
                            <input type="hidden" class="form-control" name="slug" id="slug">
                            <input type="hidden" class="form-control" name="per_piece_price" id="per_piece_price">
                            <label for="role-name" class="col-form-label">Hub Id</label>
                            <input type="text" class="form-control readOnlyClass" id="hubid" readonly>
                        </div>
                        <div class="form-group mb-1">
                            <label for="choices-single-no-search" class="form-label font-size-13 text-muted">Accessories Category</label>
                            <select class="form-control selectBasic" id="accessories_categoryId" disabled>
                                @foreach($accessories_categories as $key => $accCat)
                                <option value="{{$accCat}}">{{$accCat == 1 ? "Helmet" : ($accCat == 2 ? "T-Shirt" : "Mobile Holder")}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="role-name" class="col-form-label">Requested Quantity</label>
                            <input type="text" class="form-control readOnlyClass" id="requestedQty" readonly>
                        </div>
                        <div class="form-group mb-1">
                            <label for="role-name" class="col-form-label">Assigned Quantity &nbsp;<span class="spanColor onlyDigit_error"></span></label>
                            <input type="text" name="assign_qty" class="form-control onlyDigit" id="assign_qty">
                        </div>

                        <div class="form-group mb-1">
                            <label for="role-name" class="col-form-label">Accessories Cost</label>
                            <input type="text" name="assigned_cost" class="form-control" id="assigned_cost">
                        </div>

                        <div class="form-group mb-1">
                            <label for="role-name" class="col-form-label">Remarks</label>
                            <textarea id="assigned_remark" name="assigned_remark" class="form-control remark" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Update
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- view data -->
<div class="modal fade" id="viewModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" style="max-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">View Hub Part Accessories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Hub ID: </h5>
                        <p id="hubIds"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>HubLocation Date: </h5>
                        <p id="hub_location"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Hub Manager: </h5>
                        <p id="hub_manger"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Accessories: </h5>
                        <p id="accessories_name"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Requested Quantity: </h5>
                        <p id="requestedQuantities"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Requested Price: </h5>
                        <p id="requested_price"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Requested Date: </h5>
                        <p id="requested_date"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Requested Remark: </h5>
                        <p id="requestedRemark"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Assigned Quantity: </h5>
                        <p id="assignedQuantity"></p>
                    </div>

                    <div class="col-md-6">
                        <h5>Assigned Price: </h5>
                        <p id="assignedPrice"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Assigned Date: </h5>
                        <p id="assigned_date"></p>
                    </div>
                    <div class="col-md-12">
                        <h5>Assigned Remark: </h5>
                        <p id="assignedRemark"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Status: </h5>
                        <p id="statusId"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        //Request
        $('.raiseModelForm').click(function() {
            $('#raiseModelForm').modal('show');
        });

        // Assign
        $('.assignModelForm').click(function() {
            $('#assignModelForm').modal('show');
            var hubid = $(this).data('hubid');
            var slug = $(this).data('slug');
            var accessories_category = $(this).data('accessories_category');
            var requested_qty = $(this).data('requested_qty');
            var per_piece_price = $(this).data('per_piece_price')

            $("#slug").val(slug);
            $("#per_piece_price").val(per_piece_price);
            $("#hubid").val(hubid);
            $("#requestedQty").val(requested_qty);
            $("#accessories_categoryId").val(accessories_category).trigger('change');
        });

        // View
        $('.viewModelForm').click(function() {
            $('#viewModelForm').modal('show');
            var hubid = $(this).data('hubid');
            var hub_manger = $(this).data('manager');
            var hub_location = $(this).data('city');
            var requested_quantities = $(this).data('requested_qty');
            var acceessories = $(this).data('acceessories');
            var accessories = acceessories == 1 ? "Helmet" : acceessories == 2 ? "T-Shirt" : acceessories == 3 ? "Mobile Holder" : "";
            var accessories_price = $(this).data('accessories_price');
            var assigned_price = $(this).data('assigned_price');
            var assigned_qty = $(this).data('assigned_qty');
            var requested_remark = $(this).data('requested_remark');
            var requested_date = $(this).data('requested_date');
            var assign_date = $(this).data('assign_date');
            var assigned_remark = $(this).data('assigned_remark');
            var status = $(this).data('statusid');
            console.log(status)
            var statusId = status == 1 ? "Raised" : (status == 2 ? "Shipped" : (status == 3 ? "Completed" : (status == 4 ? "Rejected" : "")));

            $("#hub_manger").html(hub_manger);
            $("#hub_location").html(hub_location);
            $("#hubIds").html(hubid);
            $("#requestedQuantities").html(requested_quantities);
            $("#accessories_name").html(accessories);
            $("#requested_price").html(accessories_price);
            $("#assignedPrice").html(assigned_price);
            $("#assignedQuantity").html(assigned_qty);
            $("#assignedRemark").html(assigned_remark);
            $("#requested_date").html(requested_date);
            $("#requestedRemark").html(requested_remark);
            $("#assigned_date").html(assign_date);
            $("#statusId").html(statusId);
        });
    });

    setTimeout(function() {
        var message = document.getElementById('successMessage');
        if (message) {
            message.style.display = 'none';
        }
    }, 2000);

    // price calculation
    $('#assign_qty').on('input', function() {
        var quantity = $('#assign_qty').val();
        var price = $('#per_piece_price').val();
        //console.log(quantity, price);
        if (quantity !== '' && price !== '') {
            var total = parseFloat(quantity) * parseFloat(price);
            //console.log(total);
            $('#assigned_cost').val(total.toFixed(2));
        } else {
            $('#assigned_cost').val('');
        }
    });
</script>
@endsection