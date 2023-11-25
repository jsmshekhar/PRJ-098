@extends('admin.layouts.app')
@section('title', 'Customer Management Details')
@section('css')
<style>
  
</style>
@endsection
@section('content')
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4>Personal Details</h4>
                    {{--<div class="btn-card-header">
                        <a href="#" class="btn btn-link">
                            <img src="{{asset('public/assets/images/icons/edit-pen.svg')}}" alt="">
                        </a>
                    </div>--}}
                </div>
                <div class="card-body">
                    <div class="detail_cnt">
                        <h5>User Id : <span>700786</span></h5>
                        <h5>Name : <span>Aman Verma</span></h5>
                        <h5>Email Id : <span>Aman123@gmail.com</span></h5>
                        <h5>Mobile number: <span> +91 789 456 7786</span></h5>
                        <h5>Alternate Mobile number : <span>700786</span></h5>
                        <h5>Current Address : <span>A block, phase 2, Kohli vihar, Noida ,202344</span>
                        </h5>
                        <h5>Current Address : <span>A block, phase 2, Kohli vihar, Noida ,202344</span>
                        </h5>
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
                                    <img src="./assets/images/icons/edit-pen.svg" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="detail_cnt">
                                <h5>Account Holder Name : <span>Aman Verma</span></h5>
                                <h5>Account Number : <span>2548756324489</span></h5>
                                <h5>IFSC Code : <span>SBIN0034628493</span></h5>
                                <h5>Branch Name : <span> Noida</span></h5>

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
                                    <img src="./assets/images/icons/edit-pen.svg" alt="">
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
            </div>

        </div><!-- end col -->


        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4>Wallet Balance</h4>
                    <div class="btn-card-header">
                        <a href="#" class="btn btn-link">
                            <img src="./assets/images/icons/edit-pen.svg" alt="">
                        </a>
                    </div>
                </div>
                <div class="card-body card_wall_bal">
                    <div class="detail_cnt">

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
                                        <th>Transaction ID</th>
                                        <th>Payment Status</th>
                                        <th>Payment Amount</th>
                                        <th>Transaction Type</th>
                                        <th>Payment Mode</th>
                                        <th>Transaction Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>TNS001</td>
                                        <td><span>Success</span></td>
                                        <td>$5,000</td>
                                        <td>Wallet</td>
                                        <td>Online</td>
                                        <td>20/10/2023</td>
                                    </tr>
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

@endsection