@extends('admin.layouts.app')
@section('title', 'Customer (Rider) Details')
@section('css')
    <style>
        input[switch]+label {
            width: 75px !important;
        }

        input[switch]:checked+label:after {
            left: 54px !important;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4>Personal Details</h4>
                        <div class="btn-card-header">
                            <a href="#" class="btn btn-link">
                                <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="detail_cnt">
                            <div class="profil_img">
                                <img class="img-thumbnail rounded-circle avatar-xl" alt="200x200"
                                    src="{{ asset('public/assets/images/users/avatar-3.jpg') }}"
                                    data-holder-rendered="true">
                                <span class="active"></span>
                            </div>
                            <h5>User Id : <span>{{ $rider->slug }}</span></h5>
                            <h5>Name : <span>{{ $rider->name }}</span></h5>
                            <h5>Email Id : <span>{{ $rider->email }}</span></h5>
                            <h5>Mobile number: <span> {{ $rider->phone }}</span></h5>
                            <h5>Alternate Mobile number : <span>{{ $rider->alternate_phone }}</span></h5>
                            <h5>Father Mobile number : <span>{{ $rider->parent_phone }}</span></h5>
                            <h5>Brother Mobile number : <span>{{ $rider->sibling_phone }}</span></h5>
                            <h5>Owner Mobile number : <span>{{ $rider->owner_phone }}</span></h5>
                            <h5>Current Address : <span>{{ $rider->current_address }}</span></h5>
                            <h5>Permanent Address : <span>{{ $rider->permanent_address }}</span></h5>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-md-8">
                <div class="row">

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4>Banking Details</h4>
                                <div class="btn-card-header">
                                    <a href="#" class="btn btn-link">
                                        <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="detail_cnt">
                                    <h5>Account Holder Name :
                                        <span>{{ $rider->bankDetail ? $rider->bankDetail->account_name : '' }}</span>
                                    </h5>
                                    <h5>Account Number :
                                        <span>{{ $rider->bankDetail ? $rider->bankDetail->account_no : '' }}</span>
                                    </h5>
                                    <h5>IFSC Code :
                                        <span>{{ $rider->bankDetail ? $rider->bankDetail->ifsc_code : '' }}</span>
                                    </h5>
                                    <h5>Branch Name : <span>
                                            {{ $rider->bankDetail ? $rider->bankDetail->branch_name : '' }}</span></h5>

                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4>Ev Details</h4>
                                <div class="btn-card-header">
                                    <a href="#" class="btn btn-link">
                                        <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="detail_cnt line-bt-16">
                                    <h5>EV No. : <span>EV007</span></h5>
                                    <h5>HUB ID : <span>HUB001</span></h5>
                                    <h5>Plan Type : <span>Monthly</span></h5>
                                    <h5>Subscription Validity : <span>20/10/2023</span></h5>
                                    <a href="#" class="btn btn-success waves-effect waves-light">Immobilized
                                        Now</a>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4>Updated Documents</h4>
                                <div class="btn-card-header">
                                    <a href="#" class="btn btn-link">
                                        <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="detail_cnt">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                                            <table class="table mb-0">
                                                <tbody>
                                                    @if ($rider->documents && !empty($rider->documents))
                                                        @foreach ($rider->documents as $document)
                                                            <tr>
                                                                <td>{{ $document->document_type_name }}</td>
                                                                <td>{{ dateFormat($document->created_at) }}</td>
                                                                <td>
                                                                    <a href="#">
                                                                        <img src="{{ asset('public/assets/images/icons/setting-icon.svg') }}"
                                                                            alt="">
                                                                    </a>
                                                                    <a href="#">
                                                                        <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}"
                                                                            alt="">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4>Complain and Status</h4>
                                <div class="btn-card-header">
                                    <a href="#" class="btn btn-link">
                                        <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="detail_cnt">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Complain</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($rider->complaints && !empty($rider->complaints))
                                                        @foreach ($rider->complaints as $complaint)
                                                            <tr>
                                                                <td>{{ $complaint->category ? $complaint->category->category_name : '' }}
                                                                </td>
                                                                <td>{{ dateFormat($complaint->created_at) }}</td>
                                                                <td><span>{{ $complaint->display_status }}</span></td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>

            </div><!-- end col -->


            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4>Wallet Balance</h4>
                        <div class="btn-card-header">
                            <a href="#" class="btn btn-link">
                                <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="card-body card_wall_bal d-grid align-items-center">
                        <div class="detail_cnt round-spa">
                            <div class="text-center" dir="ltr">
                                <span class="rond_cntr">{{ $walletBalence }}</span>
                                <input class="knob" data-linecap=round data-fgColor="#7F56D9" value="10"
                                    data-skin="tron" data-angleOffset="1000" data-readOnly=true data-thickness=".1" />
                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4>Transaction History</h4>
                        <div class="btn-card-header">
                            <a href="#" class="btn btn-link-under">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0 card_wall_bal">
                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0 mt-2" data-pattern="priority-columns">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">Transaction ID</th>
                                            <th data-priority="2">Payment Status</th>
                                            <th data-priority="3">Payment Amount</th>
                                            <th data-priority="4">Transaction Type</th>
                                            <th data-priority="5">Payment Mode</th>
                                            <th data-priority="6">Transaction Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($rider->transactions && !empty($rider->transactions))
                                            @foreach ($rider->transactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->transaction_id }}</td>
                                                    <td>{{ $transaction->payment_status_display }}</td>
                                                    <td>{{ $transaction->transaction_ammount }}</td>
                                                    <td>{{ $transaction->transaction_type_name }}</td>
                                                    <td>{{ $transaction->transaction_mode_name }}</td>
                                                    <td>{{ dateFormat($transaction->created_at) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
@endsection
@section('js')
<script src="{{ asset('public/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>
<script src="{{ asset('public/assets/js/pages/jquery-knob.init.js') }}"></script>
@endsection
