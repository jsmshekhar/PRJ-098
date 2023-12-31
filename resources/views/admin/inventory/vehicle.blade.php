@extends('admin.layouts.app')
@section('title', 'Vehicles')
@section('css')
<style>

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
                                    <button type="button" onclick="submitSearchForm()" class="btn btn-success waves-effect waves-light">Search</button>
                                </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="searchForm" method="get" action="<?= url()->current() ?>">
                                        <input type="hidden" name="is_search" value="1" />
                                        <input type="hidden" name="per_page" id="perPageHidden" />
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">EV No.</label>
                                                    <input type="text" class="form-control" name="ev_no" value="<?= isset($_GET['ev_no']) ? $_GET['ev_no'] : '' ?>" />
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Customer Id</label>
                                                    <input type="text" class="form-control" name="cus_id" value="<?= isset($_GET['cus_id']) ? $_GET['cus_id'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" class="form-control" name="ph" value="<?= isset($_GET['ph']) ? $_GET['ph'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <label class="form-label">EV Category</label>
                                                <select class="form-control selectBasic" name="ev_cat">
                                                    <option value="">Select</option>
                                                    @foreach($ev_category as $key => $ev_cat)
                                                    <option value="{{$ev_cat}}" <?= (isset($_GET['ev_cat']) && $ev_cat == $_GET['ev_cat']) ? 'selected' : '' ?>>{{$ev_cat == 1 ? "Two Wheeler" : "Three Wheeler"}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <label class="form-label">Hub Id</label>
                                                <select class="form-control selectBasic" name="hid">
                                                    <option value="">Select</option>
                                                    @foreach($hubs as $key => $hub)
                                                    <option value="{{$hub->hub_id}}" <?= (isset($_GET['hid']) && $hub->hub_id == $_GET['hid']) ? 'selected' : '' ?>>{{$hub->hubid}} ({{$hub->city}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <label class="form-label">Payment Status</label>
                                                <select class="form-control selectBasic" name="pay">
                                                    <option value="">Select</option>
                                                    @foreach($payment_status as $key => $pay_status)
                                                    <option value="{{$pay_status}}" <?= (isset($_GET['pay']) && $pay_status == $_GET['pay']) ? 'selected' : '' ?>>{{$pay_status == 1 ? "Paid" : ($pay_status == 2 ? "Pending" : ($pay_status == 3 ? "Failed" : ($pay_status == 4 ? "Rejected" : "")))}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <label class="form-label">Vehicle Status</label>
                                                <select class="form-control selectBasic" name="status">
                                                    <option value="">Select</option>
                                                    @foreach($vehicle_status as $key => $vh_status)
                                                    <option value="{{$vh_status}}" <?= (isset($_GET['status']) && $vh_status == $_GET['status']) ? 'selected' : '' ?>>{{$vh_status == 1 ? "Mobilized" : ($vh_status == 2 ? "Immobilized" : "")}}</option>
                                                    @endforeach
                                                </select>
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
                                <a href="#" class="btn btn-link" onclick="refreshPage();">
                                    <img src="{{asset('public/assets/images/icons/refresh.svg')}}" alt="">
                                </a>
                            </li>
                            <li>
                                <p>Total Record : <span>{{$count}}</span></p>
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
                        </ul>
                    </div>
                    <div class="table-rep-plugin">
                        @if (count($vehicles) > 0)
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table">
                                <thead>
                                    <tr>
                                        <th>EV NUMBER</th>
                                        <th>EV CATEGORY</th>
                                        <th>Profile Category</th>
                                        <th>Customer Id</th>
                                        <th>Contact No.</th>
                                        <th>Hub Id</th>
                                        <th>PAYMENT STATUS</th>
                                        <th>Veicle STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehicles as $key => $vehicle)
                                    <tr>
                                        <td>{{$vehicle->ev_number}}</td>
                                        <td>{{$vehicle->ev_category_name}}</td>
                                        <td>{{$vehicle->profile_category_name}}</td>
                                        <td><a href="{{ route('customer-view', $vehicle->slug) }}" title="View Customer" style="cursor: pointer;margin-right: 5px;" target="_blank">CUS{{$vehicle->customer_id}}</a>
                                        </td>
                                        <td>{{$vehicle->phone}}</td>
                                        <td>{{$vehicle->hubid}}</td>
                                        <td>
                                            @if ($vehicle->payment_status == 1)
                                            <label class="text-success">Paid</label>
                                            @elseif($vehicle->payment_status == 2)
                                            <label class="text-warning">Pending</label>
                                            @elseif($vehicle->payment_status == 3)
                                            <label class="text-danger">Failed</label>
                                            @elseif($vehicle->payment_status == 4)
                                            <label class="text-danger">Reject</label>
                                            @endif
                                        </td>
                                        <td>Mobilized</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $vehicles->withQueryString()->links('pagination::bootstrap-4') }}
                        @else
                        <div>
                            @include('admin.common.no_record')
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
@endsection
@section('js')

@endsection