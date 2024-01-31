@extends('admin.layouts.app')
@section('title', 'Return EV')
@section('css')
    <style>
        #description {
            height: 140px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom bg-white">

                        <h4> Return Ev - <span class="text-success"> {{ 'CUS' . $records->rider->customer_id }}</span>
                        </h4>
                        <div class="nav_cust_menu">
                            <ul>
                                <li><a href="{{ route('return-exchange') }}" class="active" title="Return Exchange">Go Back</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body border-0">
                        <div class="table-rep-plugin">
                            <form method="post" enctype="multipart/form-data" id="createProductForm"
                                action="{{ route('return-evs', $records->slug) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Return Date<span
                                                class="spanColor return_date"></span></label>
                                        <input class="form-control" type="text" name="Return Date" id="return_date"
                                            value="{{ date('m/d/Y') }}" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Damage <span
                                                class="spanColor damage"></span></label>
                                        {{ Form::select('damage', ['1' => 'Paid', '2' => 'Unpaid'], null, ['class' => 'form-control selectBasic', 'id' => 'damage', 'placeholder' => 'Select']) }}
                                        <span class="text-danger">
                                            @error('damage')
                                                {{ $message }}
                                            @enderror
                                        </span>

                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Security Amount <span
                                                class="spanColor security_amount_error"></span></label>
                                        <input class="form-control" type="text" name="security_amount"
                                            id="security_amount" value="{{ $records->order->security_ammount }}" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Refund Date<span
                                                class="spanColor refund_date"></span></label>
                                        <input class="form-control" type="date" id="refund_date" name="refund_date">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Damage Cost</label>
                                        <input class="form-control" type="number" name="damage_cost" id="damage_cost"
                                            oninput="updateAmount()">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="refund_amount" class="form-label">Refund Amount
                                            <span class="spanColor" id="orefund_amount_error"></span></label>
                                        <input class="form-control" type="text" name="refund_amount" id="refund_amount"
                                            value="{{ $records->order->security_ammount }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="title" class="form-label">Extra Distance</label>
                                        <input class="form-control" type="text"
                                            value="{{ $records->extra_distance }}" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="title" class="form-label">Extra Distance Cost <small>(₹2/km)</small></label>
                                        <input class="form-control" type="text"
                                            value="{{ $records->extra_distance_cost }}" readonly>
                                    </div>
                                </div>

                                @if (!empty($images))
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="title" class="form-label">Description &nbsp; <span
                                                    class="spanColor description_error"></span></label>
                                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Write here."></textarea>
                                        </div>
                                        <div class="col-md-8 mb-3 mt-4">
                                            <label for="title" class="form-label"></label>
                                            @foreach ($images as $image)
                                                <img src="{{ $image['path'] }}" height="150" width="150">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-success" id="submitForm">Return</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@section('js')
    <script>
        function updateAmount() {
            var totalAmount = parseFloat("<?php echo $records->order->security_ammount; ?>");
            var enteredAmount = parseFloat($("#damage_cost").val());
            console.log('v', enteredAmount);
            var remainingAmount = isNaN(enteredAmount) ? totalAmount : totalAmount - enteredAmount;
            $("#refund_amount").val(remainingAmount);
        }
    </script>
@endsection
