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
                                    <button type="button" class="btn btn-outline-danger waves-effect waves-light" onclick="clearSearch('http://localhost/PRJ-098/admin/distributed-hubs');">Clear</button>
                                    <button type="button" onclick="submitSearchForm();" class="btn btn-outline-success waves-effect waves-light">Search</button>
                                                                    </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="searchForm" method="get" action="http://localhost/PRJ-098/admin/distributed-hubs">
                                        <input type="hidden" name="is_search" value="1">
                                        <input type="hidden" name="per_page" id="perPageHidden">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Transaction Id</label>
                                                    <input type="text" class="form-control" name="hub_id" value="">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Transaction Type</label>
                                                    <input type="text" class="form-control" name="city" value="">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Transaction To</label>
                                                    <input type="text" class="form-control" name="hub_capacity" value="">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Transaction Detail</label>
                                                    <input type="text" class="form-control" name="vehicle" value="">
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
                                <a href="javascript:void(0);" class="btn btn-link" onclick="refreshPage();">
                                    <img src="http://localhost/PRJ-098/public/assets/images/icons/refresh.svg" alt="">
                                </a>
                            </li>
                            <li>
                                <p>Total Record : <span>255</span></p>
                            </li>
                            <li>
                                <p>Display up to :
                                </p><div class="form-group">
                                    <select class="form-control perPage" name="choices-single-no-sorting" id="perPageDropdown" onchange="perPage(this);">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
    </select>
                                </div>
                                <p></p>
                            </li>
                            <li>
                                <button type="button" class="btn btn-success waves-effect waves-light">
                                    <img src="http://localhost/PRJ-098/public/assets/images/icons/download.svg" alt="">
                                    Export
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <div class="sticky-table-header">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>TRANSACTION ID</th>
                                            <th>TRANSACTION TYPE</th>
                                            <th>TRANSACTION TO</th>                                            
                                            <th>TRANSACTION DETAIL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>TN001</td>
                                            <td><label class="text-danger m-0">Debit</label></td>
                                            <td>Ec2z Account</td>
                                            <td>Subscription Charges</td>
                                        </tr>
                                        <tr>
                                            <td>TN001</td>
                                            <td><label class="text-success m-0">Credit</label></td>
                                            <td>Ec2z Account</td>
                                            <td>Subscription Charges</td>
                                        </tr>
                                        <tr>
                                            <td>TN001</td>
                                            <td><label class="text-success m-0">Credit</label></td>
                                            <td>Ec2z Account</td>
                                            <td>Subscription Charges</td>
                                        </tr>
                                        <tr>
                                            <td>TN001</td>
                                            <td><label class="text-success m-0">Credit</label></td>
                                            <td>Ec2z Account</td>
                                            <td>Subscription Charges</td>
                                        </tr>
                                        <tr>
                                            <td>TN001</td>
                                            <td><label class="text-success m-0">Credit</label></td>
                                            <td>Ec2z Account</td>
                                            <td>Subscription Charges</td>
                                        </tr>
                                        <tr>
                                            <td>TN001</td>
                                            <td><label class="text-danger m-0">Debit</label></td>
                                            <td>Ec2z Account</td>
                                            <td>Subscription Charges</td>
                                        </tr>                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
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
