@extends('admin.layouts.app')
@section('title', 'Rider`s Order Management')
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
                                                        <label class="form-label">Customer Name</label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Order Date</label>
                                                        <input type="text" class="form-control" name="date"
                                                            value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Order Id</label>
                                                        <input type="text" class="form-control" name="order_id"
                                                            value="<?= isset($_GET['order_id']) ? $_GET['order_id'] : '' ?>" />
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
                                    <p>Total Record : <span>{{ count($orders) }}</span></p>
                                </li>
                                <li>
                                    <p>Display up to :
                                    <div class="form-group">
                                        @include('admin.layouts.per_page')
                                    </div>
                                    </p>
                                </li>
                                @if (count($orders) > 0)
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
                            @if (count($orders) > 0)
                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table">
                                        <thead>
                                            <tr>
                                                <th>Customer Id</th>
                                                <th>Customer Name</th>
                                                <th>Order Date</th>
                                                <th>Order Id</th>
                                                <th>Order Items</th>
                                                <th>Order Quantity</th>
                                                <th>Order Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $key => $order)
                                                <tr>
                                                    <td>
                                                        {{ $order->rider->slug }}
                                                    </td>
                                                    <td>{{ $order->rider->name }}</td>
                                                    <td>{{ dateFormat($order->order_date) }}</td>
                                                    <td>{{ $order->slug }}</td>
                                                    <td>{{ $order->phone }}</td>
                                                    <td>{{ dateFormat($order->created_at) }}</td>
                                                    <td>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $orders->withQueryString()->links('pagination::bootstrap-4') }}
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
    <script type="text/javascript"></script>
@endsection
