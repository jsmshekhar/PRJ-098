@extends('admin.layouts.app')
@section('title', 'Exchange EV')
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

                        <h4> Exchange Ev - <span class="text-success"> {{ 'CUS' . $records->rider->customer_id }}</span>
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
                                action="{{ route('exchange-evs', $records->slug) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Date<span
                                                class="spanColor date"></span></label>
                                        <input class="form-control" type="text" name="Date" id="date"
                                            value="{{ date('m/d/Y') }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Old EV Number<span
                                                class="spanColor old_ev"></span></label>
                                        <input class="form-control" type="text" name="old_ev" id="old_ev"
                                            value="{{ $records->product->ev_number }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">New EV Number<span
                                                class="spanColor new_ev"></span></label>
                                        {{ Form::select('new_ev', $evList, null, ['class' => 'form-control selectBasic', 'id' => 'new_ev', 'placeholder' => 'Select']) }}
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Payment Status</label>
                                        <input class="form-control" type="text" name="payment_status" id="payment_status"
                                            value="{{ $records->order->payment_status_display }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Security Amount <span
                                                class="spanColor security_amount_error"></span></label>
                                        <input class="form-control" type="text" name="security_amount"
                                            id="security_amount" value="{{ $records->order->security_ammount }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Damage <span
                                                class="spanColor damage"></span></label>
                                        {{ Form::select('damage', ['1' => 'Paid', '2' => 'Unpaid'], null, ['class' => 'form-control selectBasic', 'id' => 'damage', 'placeholder' => 'Select']) }}
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Damage Cost</label>
                                        <input class="form-control" type="text" name="damage_cost" id="damage_cost">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="example-title-input" class="form-label">Damage Desription</label>
                                        <input class="form-control" type="text" name="damage_desription"
                                            id="damage_desription">
                                    </div>

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
                                <div class="col-md-12 mb-2 mt-3 uploadImg">
                                    <label for="title" class="form-label">New Images</label>
                                    <div class="uploadBtnBox form-check p-0">
                                        <label class="uploadBtn">
                                            <img class="upload_des_preview1 clickable selectedImage"
                                                src="{{ asset('public/assets/images/uploadimg.png') }}"
                                                alt="example placeholder" width="140" height="140" />
                                            <input type="hidden" name="evImages" id="json_img">
                                            <input type="file" name="images[]" multiple="" class="uploadInputfile"
                                                id="uploadInputfile">
                                        </label>
                                        <div class="uploadImgWrap"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if (!empty($images))
                                        <div class="col-md-12 mb-3">
                                            <label for="Image" class="form-label">Old Images</label><br />
                                            @foreach ($images as $image)
                                                <img src="{{ $image['path'] }}" height="150" width="150">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-success" id="submitForm">Exchange</button>
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

@endsection
