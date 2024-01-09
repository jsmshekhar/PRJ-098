@extends('admin.layouts.app')
@section('title', 'Customer (Rider) Management')
@section('css')
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
                                    <button class="accordion-button " type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Advance Search
                                    </button>
                                    <div class="collaps_btns">
                                        <button type="button" class="btn btn-outline-danger waves-effect waves-light"
                                            onclick="clearSearch('<?= url()->current() ?>');">Clear</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light"
                                            onclick="submitSearchForm();">Search</button>
                                    </div>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <form id="searchForm" method="get" action="<?= url()->current() ?>">
                                            <input type="hidden" name="is_search" id="isSearchHidden" value="0" />
                                            <input type="hidden" name="per_page" id="perPageHidden" />
                                            <input type="hidden" name="is_export" id="isExportHidden" />
                                            <div class="row">
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Customer Id</label>
                                                        <input type="text" class="form-control" name="customer_id"
                                                            value="<?= isset($_GET['customer_id']) ? $_GET['customer_id'] : '' ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Email Address</label>
                                                        <input type="text" class="form-control" name="email"
                                                            value="<?= isset($_GET['email']) ? $_GET['email'] : '' ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="text" class="form-control" name="phone"
                                                            value="<?= isset($_GET['phone']) ? $_GET['phone'] : '' ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Joining Date</label>
                                                        <input type="date" class="form-control" name="joining_date"
                                                            value="<?= isset($_GET['joining_date']) ? $_GET['joining_date'] : '' ?>" />
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
                                        <img src="{{ asset('public/assets/images/icons/refresh.svg') }}" alt=""
                                            onclick="refreshPage();">
                                    </a>
                                </li>
                                <li>
                                    <p>Total Record : <span>{{ count($records) }}</span></p>
                                </li>
                                <li>
                                    <p>Display up to :
                                    <div class="form-group">
                                        @include('admin.layouts.per_page')
                                    </div>
                                    </p>
                                </li>
                                @if (count($records) > 0)
                                    <li>
                                        <button type="button" class="btn btn-success waves-effect waves-light"
                                            onclick="exportData('<?= config('table.REF_TABLE.RIDER') ?>');">
                                            <img src="{{ asset('public/assets/images/icons/download.svg') }}"
                                                alt="">
                                            Export
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="table-rep-plugin">
                            @if (count($records) > 0)
                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table">
                                        <thead>
                                            <tr>
                                                <th>Customer Id</th>
                                                <th>EV Number</th>
                                                <th>Chassis Number</th>
                                                <th>Subscription Date</th>
                                                <th>Phone Number</th>
                                                <th>Hub Id</th>
                                                <th>User Request</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $key => $record)
                                                <tr>
                                                    <td>{{ $record->rider->customer_id }}</td>
                                                    <td>{{ $record->product->ev_number }}</td>
                                                    <td>{{ $record->product->chassis_number }}</td>
                                                    <td>{{ dateFormat($record->order->subscription_validity) }}</td>
                                                    <td>{{ $record->rider->phone }}</td>
                                                    <td>{{ $record->hub->hubId }}</td>
                                                    <td>{{ $record->request_for_name }}</td>
                                                    <td>{{ $record->status_display }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $records->withQueryString()->links('pagination::bootstrap-4') }}
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

@endsection
