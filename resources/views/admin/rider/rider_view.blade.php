@extends('admin.layouts.app')
@section('title', 'Customer (Rider) Details')
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
                        <div class="btn-card-header">
                            <a href="javascript:void(0);" class="btn btn-link" onclick="showModal('detailModal');">
                                <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                            </a>
                            <!-- <a href="javascript:void(0);" class="btn btn-link" onclick="showModal('targetModal');">
                                                    <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                                                </a> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="detail_cnt">
                            <div class="profil_img mb-3 new_active_profile">
                                <img class="img-thumbnail rounded-circle avatar-xl" alt="200x200"
                                    src="{{ asset('public/assets/images/users/avatar-3.jpg') }}"
                                    data-holder-rendered="true">
                                <span class="active"></span>
                            </div>
                            <p class="text-center text-dot-new">Profile Type :
                                <span>{{ $rider->profile_type_name }}</span>
                            </p>
                            <p class="text-center text-dot-new">KYC Status :
                                <span class="dot_btn"
                                    onclick="showModal('targetModal');">{{ $rider->kyc_status_name }}</span>
                            </p>
                            <h5 class="mt-4">User Id : <span>{{ $rider->slug }}</span></h5>
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
                                {{-- <div class="btn-card-header">
                                    <a href="#" class="btn btn-link">
                                        <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                            </a>
                        </div> --}}
                            </div>
                            <div class="card-body">
                                @if (!is_null($riderEv))
                                    <div class="detail_cnt line-bt-16">
                                        <h5>Current EV No. : <span>{{ $riderEv->ev_number }}</span></h5>
                                        <h5>HUB ID : <span>{{ $riderEv->hubId }}</span></h5>
                                        <h5>Plan Type : <span>{{ $riderEv->rent_cycle }}</span></h5>
                                        <h5>Subscription Status : <span>{{ $riderEv->subscriptionStatus }}</span></h5>
                                        <h5>Last EV Number : <span>{{ $riderEv->last_ev }}</span></h5>
                                        <?php
                                        $productId = $riderEv->product_id ?? "";
                                        $gpsEmeiNumber = $riderEv->gps_emei_number ?? "";
                                        $riderId = $riderEv->rider_id ?? "";
                                        ?>
                                        <button
                                            onclick="mobilizedEv(<?= $productId ?>, <?= $gpsEmeiNumber ?>, <?= $riderId ?>, 'm')"
                                            class="btn btn-success waves-effect waves-light mobilizedEvMap">Mobilized
                                            Now</button>
                                        <button
                                            onclick="imMobilizedEv(<?= $productId ?>, <?= $gpsEmeiNumber ?>, <?= $riderId ?>, 'im')"
                                            class=" btn btn-danger waves-effect waves-light imMobilizedEvMap">Immobilized
                                            Now</button>
                                        <p class="text-success d-block" id="mobiImmobiMap"></p>
                                    </div>
                                @endif
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4>Updated Documents</h4>
                                <div class="btn-card-header text-view">
                                    Action
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
                                                                    <a href="{{ asset('public/upload/documents/' . $document->front_pic) }}"
                                                                        download>
                                                                        <img src="{{ asset('public/assets/images/icons/light_download.svg') }}"
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
                                {{-- <div class="btn-card-header">
                                    <a href="#" class="btn btn-link">
                                        <img src="{{ asset('public/assets/images/icons/edit-pen.svg') }}" alt="">
                        </a>
                    </div> --}}
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
                    </div>
                    <div class="card-body card_wall_bal d-grid align-items-center">
                        <div class="detail_cnt round-spa">
                            <div class="text-center" dir="ltr">
                                <span class="rond_cntr">{{ $walletBalence }}</span>
                                <input class="knob" data-linecap=round data-fgColor="#01992366" value="100"
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
                                        @if (isset($transactions) && count($transactions) > 0 && !empty($rider->transactions))
                                            @foreach ($transactions as $transaction)
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
                            @if (isset($transactions) && count($transactions) > 0)
                                {{ $transactions->withQueryString()->links('pagination::bootstrap-4') }}
                            @endif
                        </div>

                    </div>
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->

    <div class="modal fade" id="targetModal" role="dialog" aria-labelledby="modalLabel" data-keyboard="false"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modelWidth" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Change KYC status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="kycStatusForm" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">Status</label>
                                    {{ Form::select('kyc_status', $kycStatus, $rider->kyc_status, ['class' => 'form-control selectBasic', 'id' => 'kyc_status']) }}
                                    <span class="spanColor kyc_status_error"></span>
                                </div>
                                <input type="hidden" name="rider_slug" value="{{ $rider->slug }}">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger waves-effect waves-light"
                        data-bs-dismiss="modal">Close</button>
                    <span class=" text-success d-block" id="message"></span>
                    <button type="button" id="submitKycStatusForm" class="btn btn-success waves-effect waves-light">Save
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" role="dialog" aria-labelledby="modalLabel" data-keyboard="false"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modelWidth" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Edit Personal Details </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="riderDetailForm" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">User Id</label>
                                    <input id="autocomplete" type="text" class="floating-input form-control"
                                        autocomplete="off" value="{{ 'CUS' . $rider->customer_id }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">Name</label>
                                    <input id="autocomplete" type="text" class="floating-input form-control"
                                        autocomplete="off" value="{{ $rider->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">Email Id</label>
                                    <input id="autocomplete" type="text" class="floating-input form-control"
                                        autocomplete="off" value="{{ $rider->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">Mobile Number</label>
                                    <input id="autocomplete" type="text" class="floating-input form-control"
                                        autocomplete="off" value="{{ $rider->phone }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">Alternate Mobile Number</label>
                                    <input id="autocomplete" type="text" class="floating-input form-control"
                                        autocomplete="off" value="{{ $rider->alternate_phone }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">Current Address</label>
                                    <input id="autocomplete" type="text" class="floating-input form-control"
                                        autocomplete="off" value="{{ $rider->current_address }}" name="current_address">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="address serach" class="col-form-label">Permanent Address</label>
                                    <input id="autocomplete" type="text" class="floating-input form-control"
                                        autocomplete="off" value="{{ $rider->permanent_address }}"
                                        name="permanent_address">
                                </div>
                            </div>
                            <input type="hidden" name="rider_slug" value="{{ $rider->slug }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger waves-effect waves-light"
                        data-bs-dismiss="modal">Close</button>
                    <span class=" text-success d-block" id="message"></span>
                    <button type="button" id="submitRiderForm" class="btn btn-success waves-effect waves-light">Save
                    </button>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('public/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/jquery-knob.init.js') }}"></script>

    <script type="text/javascript">
        function showModal(modelId) {
            $("#" + modelId).modal('show');
        }

        $(document).ready(function() {
            $('#submitKycStatusForm').click(function(e) {
                e.preventDefault();
                $('#submitKycStatusForm').prop('disabled', true);
                $('#submitKycStatusForm').html('Please wait...')
                var formDatas = new FormData(document.getElementById('kycStatusForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('change-kyc-status') }}",
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

            $('#submitRiderForm').click(function(e) {
                e.preventDefault();
                $('#submitRiderForm').prop('disabled', true);
                $('#submitRiderForm').html('Please wait...')
                var formDatas = new FormData(document.getElementById('riderDetailForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('update-rider-details') }}",
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
    </script>
@endsection
