@extends('admin.layouts.app')
@section('title', 'Refund Management')
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
                                    <button type="button" onclick="submitSearchForm();" class="btn btn-outline-success waves-effect waves-light">Search</button>
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
                                                    <input type="text" class="form-control" name="hub_id" value="<?= isset($_GET['hub_id']) ? $_GET['hub_id'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Hub Manager Name</label>
                                                    <input type="text" class="form-control" name="mng_name" value="<?= isset($_GET['mng_name']) ? $_GET['mng_name'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Rider ID</label>
                                                    <input type="text" class="form-control" name="rider_id" value="<?= isset($_GET['rider_id']) ? $_GET['rider_id'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Rider Name</label>
                                                    <input type="text" class="form-control" name="rd_name" value="<?= isset($_GET['rd_name']) ? $_GET['rd_name'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Refund Date</label>
                                                    <input type="date" class="form-control" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Refund Status</label>
                                                    <select class="form-control selectBasic" name="status">
                                                        <option value="">Select Status </option>
                                                        @foreach($refundStatus as $key => $st)
                                                        <option value="{{$key}}" <?= (isset($_GET['status']) && $key == $_GET['status']) ? 'selected' : '' ?>>{{$st}}</option>
                                                        @endforeach
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
                                <a href="#" class="btn btn-link" onclick="refreshPage();">
                                    <img src="{{asset('public/assets/images/icons/refresh.svg')}}" alt="">
                                </a>
                            </li>
                            <li>
                                <p>Total Record : <span>{{$count}}</span></p>
                            </li>
                            <li>
                                <p>Display up to :
                                </p>
                                <div class="form-group">
                                    @include('admin.layouts.per_page')
                                </div>
                                <p></p>
                            </li>
                            <li>
                                <button type="button" class="btn btn-success waves-effect waves-light">
                                    <img src="{{asset('public/assets/images/icons/download.svg')}}" alt="">
                                    Export
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="table-rep-plugin">
                        @if(count($refunds) >0)
                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <div class="sticky-table-header">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Hub Id</th>
                                            <th>MANAGER NAME</th>
                                            <th>MANAGER PHONE</th>
                                            <th>RIDER ID</th>
                                            <th>RIDER PHONE</th>
                                            <th>RFD AMOUNT</th>
                                            <th>REFUND DATE</th>
                                            <th>NOTE</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($refunds as $key => $refund)
                                        <tr>
                                            <td>{{$refund->hubId}}</td>
                                            <td>{{$refund->mng_name}}</td>
                                            <td>{{$refund->mng_phone}}</td>
                                            <td>CUS{{$refund->customer_id}}<br><i><span class="d-flex heading_label"> {{$refund->rider_name}} </span></i></td>
                                            <td>{{$refund->rider_phone}}</td>
                                            <td>Rs {{$refund->refund_ammount}}</td>
                                            <td>{{ dateFormat($refund->refund_date) }}</td>
                                            <td>{{$refund->note}}</td>
                                            <td>@if($refund->status == 1)<label class="text-success m-0">Resolved</label> @else <label class="text-danger m-0">Pending</label> @endif</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $refunds->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
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
<script></script>
@endsection