@extends('admin.layouts.app')
@section('title', 'Transaction Management')
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
                                                    <label class="form-label">Transaction Id</label>
                                                    <input type="text" class="form-control" name="tr_id" value="<?= isset($_GET['tr_id']) ? $_GET['tr_id'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Customer ID</label>
                                                    <input type="text" class="form-control" name="cu_id" value="<?= isset($_GET['cu_id']) ? $_GET['cu_id'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Payment Type</label>
                                                    <select class="form-control selectBasic" name="p_type">
                                                        <option value="">Select Payment Type</option>
                                                        @foreach($payTypes as $key => $pay_type)
                                                        <option value="{{$key}}" <?= (isset($_GET['p_type']) && $key == $_GET['p_type']) ? 'selected' : '' ?>>{{$pay_type}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Payment Mode</label>
                                                    <select class="form-control selectBasic" name="p_mode">
                                                        <option value="">Select Payment Mode</option>
                                                        @foreach($payModes as $key => $pay_mode)
                                                        <option value="{{$key}}" <?= (isset($_GET['p_mode']) && $key == $_GET['p_mode']) ? 'selected' : '' ?>>{{$pay_mode}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Payment Status</label>
                                                    <select class="form-control selectBasic" name="p_status">
                                                        <option value="">Select Payment Status</option>
                                                        @foreach($payStatus as $key => $pay_status)
                                                        <option value="{{$key}}" <?= (isset($_GET['p_status']) && $key == $_GET['p_status']) ? 'selected' : '' ?>>{{$pay_status}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Transaction Date</label>
                                                    <input type="date" class="form-control" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" />
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
                        @if(count($transactions) >0)
                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <div class="sticky-table-header">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>TRANSACTION ID</th>
                                            <th>CUSTOMER ID</th>
                                            <th>AMOUNT</th>
                                            <th>TRANSACTION</th>
                                            <th>Payment Mode</th>
                                            <th>Status</th>
                                            <th>Trans. Date</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $key => $transaction)
                                        <tr>
                                            <td>{{$transaction->transaction_id}}</td>
                                            <td>CUS{{$transaction->customer_id}}<br>{{$transaction->name}}</td>
                                            <td>Rs {{$transaction->transaction_ammount}}</td>
                                            <td>@if($transaction->transaction_type == 1)<label class="text-success m-0">Credit</label> @else <label class="text-danger m-0">Debit</label> @endif</td>
                                            <td>{{$transaction->transaction_mode}}</td>
                                            <td>{{$transaction->payment_status}}</td>
                                            <td>{{ dateFormat($transaction->created_at) }}</td>
                                            <td>{{ $transaction->transaction_notes }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $transactions->withQueryString()->links('pagination::bootstrap-4') }}
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