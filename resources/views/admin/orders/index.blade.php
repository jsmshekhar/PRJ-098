@extends('admin.layouts.app')
@section('title', 'Rider`s Order Management')
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
                                                <th>Order Id</th>
                                                <th>Order Date</th>
                                                <th>Order Items</th>
                                                <th>Order Quantity</th>
                                                <th>Order Amount</th>
                                                <th>Payment Status</th>
                                                <th>Assign Ev</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($orders as $key => $order)
                                                @php
                                                    $accessoriesItems = [];
                                                @endphp
                                                @if (!is_null($order->accessories_items))
                                                    @php
                                                        $qty = 0;
                                                        foreach (json_decode($order->accessories_items) as $items) {
                                                            $accessoriesItems[] = $items->quantity . '-' . ucwords($items->title);
                                                            $qty = $qty + $items->quantity;
                                                        }
                                                        sort($accessoriesItems);
                                                        $items = implode(', ', $accessoriesItems);
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td><a class="orderOverviewModelForm" data-toggle="modal"
                                                            data-customer_id="CUS{{ $order->rider->customer_id }}"
                                                            data-customer_name="{{ $order->rider->name }}"
                                                            data-order_date="{{ dateFormat($order->order_date) }}"
                                                            data-qty="{{ $qty + 1 }}" data-items="{{ $items }}"
                                                            data-order_amount="{{ $order->ordered_ammount }}"
                                                            data-images="{{ json_encode($order->mediaFiles) }}"
                                                            title="Customer Overview"
                                                            style="cursor: pointer;margin-right: 5px;">
                                                            {{ 'CUS' . $order->rider->customer_id }}
                                                            ({{ $order->rider->profile_type_name }})
                                                        </a>

                                                    </td>
                                                    <td>{{ $order->rider->name }}</td>
                                                    <td>{{ $order->slug }}</td>
                                                    <td>{{ dateFormat($order->order_date) }}</td>
                                                    <td>
                                                        {{ $order->product_name . ',' }}

                                                        @php
                                                            $accessoriesItems = [];
                                                        @endphp
                                                        @if (!is_null($order->accessories_items))
                                                            @php
                                                                $qty = 0;
                                                                foreach (json_decode($order->accessories_items) as $items) {
                                                                    $accessoriesItems[] = $items->quantity . '-' . ucwords($items->title);
                                                                    $qty = $qty + $items->quantity;
                                                                }
                                                                sort($accessoriesItems);
                                                                echo implode(', ', $accessoriesItems);
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td>{{ $qty + 1 }}</td>
                                                    <td>{{ $order->ordered_ammount }}</td>
                                                    <td>{{ $order->payment_status_display }}</td>
                                                    <td>
                                                        @if ($order->payment_status == 1)
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-success waves-effect waves-light"
                                                                onclick="showModal('{{ $order->rider->customer_id }}', '{{ $order->rider->profile_type }}', '{{ $order->slug }}');">Assign</a>
                                                        @else
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-secondary disabled waves-effect waves-light">Assign</a>
                                                        @endif
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
    <div class="modal fade" id="targetModal" role="dialog" aria-labelledby="modalLabel" data-keyboard="false"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modelWidth" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Assign an EV
                        <span class="d-flex heading_label" id="customerSlug"></span>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="assignEvsForm" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="mapped_ev" class="col-form-label">Map EV</label>
                                    {{ Form::select('mapped_ev', $evList, null, ['class' => 'form-control selectBasic', 'placeholder' => 'Select Ev', 'id' => 'mapped_ev']) }}
                                    <span class="spanColor mapped_ev_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php
                                $currentDate = date("Y-m-d");
                                $date = date('Y-m-d', strtotime($currentDate . ' +15 days'));
                                ?>
                                <div class="form-group mb-2">
                                    <label for="subscription_validity" class="col-form-label">Subscription
                                        Validity</label>
                                    <input name="subscription_validity" type="date"
                                        class="floating-input form-control" autocomplete="off"
                                        id="subscription_validity" min="<?php echo $date; ?>">
                                    <span class="spanColor subscription_validity_error"></span>
                                </div>
                            </div>
                            <div class="isVendor">
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="cluster_manager" class="col-form-label">Cluster Manager</label>
                                        <span class="spanColor cluster_manager_error"></span>
                                        <input name="cluster_manager" type="text" class="floating-input form-control"
                                            autocomplete="off" id="cluster_manager">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="tl_name" class="col-form-label">TL Name</label>
                                        <input name="tl_name" type="text" class="floating-input form-control"
                                            autocomplete="off" id="tl_name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="client_name" class="col-form-label">Client Name</label>
                                        <input name="client_name" type="text" class="floating-input form-control"
                                            autocomplete="off" id="client_name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="client_address" class="col-form-label">Client Address</label>
                                        <textarea id="client_address" name="client_address" class="form-control" rows="5" id="client_address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2 mt-3 uploadImg">
                                <label for="title" class="form-label">Image Upload</label>

                                <div class="uploadBtnBox form-check p-0">
                                    <label class="uploadBtn">
                                        <img class="upload_des_preview clickable selectedImage"
                                            src="{{ asset('public/assets/images/uploadimg.png') }}"
                                            alt="example placeholder" />
                                        <input type="hidden" name="evImages" id="json_img">
                                        <input type="file" name="images[]" multiple="" class="uploadInputfile"
                                            id="uploadInputfile">
                                    </label>
                                    <div class="uploadImgWrap"></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="order_slug" id="orderSlug">
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger waves-effect waves-light"
                        data-bs-dismiss="modal">Close</button>
                    <span class=" text-success d-block" id="message"></span>
                    <button type="button" id="submitAssignEvForm" class="btn btn-success waves-effect waves-light">Save
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderOverviewModelForm" role="dialog" aria-labelledby="modalLabel"
        data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Overview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Id</label>
                                <input type="hidden" class="form-control" name="orderSlug" id="orderSlug">
                                <input class="form-control readOnlyClass" type="text" id="customerIds" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Name</label>
                                <input class="form-control readOnlyClass" type="text" id="customerNames" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Date</label>
                                <input class="form-control readOnlyClass" type="text" id="orderDates" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Items</label>
                                <input class="form-control readOnlyClass" type="text" id="orderItems" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Quantity</label>
                                <input class="form-control readOnlyClass" type="text" id="orderQntys" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">AOrder Amount</label>
                                <input class="form-control readOnlyClass" type="text" id="orderAmounts" readonly>
                            </div>
                            <div class="col-md-12 mb-2 uploadImg">
                                <div class="uploadBtnBox form-check">
                                    <label class="uploadBtn">
                                        <p>Images</p>
                                    </label>
                                </div>
                                <div class="uploadImgWrap" id="imageContainer">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $('.isVendor').hide();

        function showModal(customerId = "", customerType = "", slug = "") {
            if (customerType == 1) {
                $('.isVendor').show();
            }
            $('#customerSlug').text("Customer Id - CUS" + customerId);
            $('#orderSlug').val(slug);
            $("#targetModal").modal('show');
        }

        $(document).ready(function() {
            $('#submitAssignEvForm').click(function(e) {
                e.preventDefault();
                var mappedEv = $('#mapped_ev').val();
                if (mappedEv == "") {
                    $(".mapped_ev_error").html('This field is required!');
                    $("input#mapped_ev").focus();
                    return false;
                }
                var subscriptionValidity = $('#subscription_validity').val();
                if (subscriptionValidity == "") {
                    $(".subscription_validity_error").html('This field is required!');
                    $("input#subscription_validity").focus();
                    return false;
                }
                $('#submitAssignEvForm').prop('disabled', true);
                $('#submitAssignEvForm').html('Please wait...')
                var formDatas = new FormData(document.getElementById('assignEvsForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('assign-ev-customer') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#message').html("<span class='sussecmsg'>" + data.message +
                            "</span>");

                        $('#submitCompanyForm').prop('disabled', false);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);

                    },
                    errors: function() {
                        $('#message').html(
                            "<span class='sussecmsg'>Somthing went wrong!</span>");
                    }
                });
            });
        });

        $('.orderOverviewModelForm').on('click', function(event) {
            event.preventDefault();
            $('#orderOverviewModelForm').modal('show');
            var customer_id = $(this).data('customer_id');
            var customer_name = $(this).data('customer_name');
            var order_date = $(this).data('order_date');
            var qty = $(this).data('qty');
            var items = $(this).data('items');
            var order_amount = $(this).data('order_amount');
            var images = $(this).data('images');
            console.log(images);
            $("#customerIds").val(customer_id);
            $("#customerNames").val(customer_name);
            $("#orderDates").val(order_date);
            $("#orderQntys").val(qty);
            $("#orderAmounts").val(order_amount)
            $("#orderItems").val(items);
            var imageContainer = $('#imageContainer');
            imageContainer.empty();
            images.forEach(function(imageUrl) {
                var url = "{{ asset('public/upload/mediafiles') }}/" + imageUrl;
                var imgElement = $('<img>').attr('src', url).addClass('modal-image');
                imageContainer.append(imgElement);
            });
        });
    </script>
@endsection
