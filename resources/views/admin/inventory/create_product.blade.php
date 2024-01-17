@extends('admin.layouts.app')
@section('title', 'Add Product')
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

                    <h4> Add Product <span class="d-flex heading_label"> {{ucfirst(request()->route('param'))}} </span> </h4>
                    <div class="nav_cust_menu">
                        <ul>
                            <li><a href="{{ route('products', request()->route('param')) }}" class="active" title="Products">Go Back</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body border-0">
                    <div class="table-rep-plugin">
                        <form method="post" enctype="multipart/form-data" id="createProductForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="example-title-input" class="form-label">Title* &nbsp; <span class="spanColor title_error"></span></label>
                                    <input class="form-control" type="text" name="title" id="title" value="">
                                    <input class="form-control" type="hidden" name="profile_category" id="profile_category" value="{{request()->route('param') == 'corporate' ? 1 :(request()->route('param') == 'individual' ? 2 : (request()->route('param') == 'student' ? 3 : 4))}}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="example-title-input" class="form-label">EV Number* &nbsp; <span class="spanColor ev_number_error"></span></label>
                                    <input class="form-control" type="text" name="ev_number" id="ev_number" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="example-title-input" class="form-label">Chassis Number* &nbsp; <span class="spanColor chassis_number_error"></span></label>
                                    <input class="form-control" type="text" name="chassis_number" id="chassis_number" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="example-title-input" class="form-label">GPS IMEI</label>
                                    <input class="form-control" type="text" name="gps_emei_number" id="gps_emei_number" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ev_category" class="form-label">EV Category</label>
                                    <select class="form-control selectBasic" name="ev_category" id="ev_category">
                                        @foreach($ev_categories as $key => $ev_category)
                                        <option value="{{$ev_category}}">{{$ev_category == 1 ? "Two Wheeler" : "Three Wheeler"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ev_type_id" class="form-label">EV Type* &nbsp;<span class="spanColor ev_type_id_error"></span></label>
                                    <select class="form-control selectBasic" name="ev_type_id" id="ev_type_id">
                                        <option value=""> Select EV Type </option>
                                        @foreach($ev_types as $key => $ev_type)
                                        <option value="{{$ev_type->range}}" data-value1="{{$ev_type->speed}}" data-value2="{{$ev_type->range}}" data-value3="{{$ev_type->rs_perday}}" data-value4="{{$ev_type->total_range}}">{{$ev_type->ev_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="speed" class="form-label">Speed* (km/h) &nbsp;<span class="spanColor onlyDigitSpeed_error speed_error" id="speed_error"></span></label>
                                    <input class="form-control onlyDigitSpeed" type="text" name="speed" id="speed" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="range" class="form-label ">Single charge Run Time* (km) &nbsp;<span class="spanColor onlyDigit_error range_error" id="range_error"></span></label>
                                    <input class="form-control onlyDigit" type="text" name="km_per_charge" id="km_per_charge" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="example-title-input" class="form-label">Rent per Day(Rs) &nbsp; <span class="spanColor onlyDigitRent_error rent_error" id="rent_error"> </span></label>
                                    <input class="form-control onlyDigitRent" type="text" name="per_day_rent" id="per_day_rent" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="range" class="form-label ">Monthly Range (km) &nbsp;<span class="spanColor onlyDigitMonthly_error monthly_range_error" id="monthly_range_error"></span></label>
                                    <input class="form-control onlyDigitMonthly" type="text" name="total_range" id="total_range" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="rent_cycle" class="form-label">Rent Cycle</label>
                                    <select class="form-control selectBasic" name="rent_cycle" id="rent_cycle">
                                        @foreach($rent_cycles as $key => $rent_cycle)
                                        <option value="{{$rent_cycle}}">{{$rent_cycle == 15 ? "15 Days" : "30 Days"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bike_type" class="form-label">Bike Type</label>
                                    <select class="form-control selectBasic" name="bike_type" id="bike_type">
                                        @foreach($bike_types as $key => $bike_type)
                                        <option value="{{$bike_type}}">{{$bike_type == 1 ? "Cargo Bike" : "Normal Bike"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="battery_type" class="form-label">Battery Type</label>
                                    <select class="form-control selectBasic" name="battery_type" id="battery_type">
                                        @foreach($battery_types as $key => $battery_type)
                                        <option value="{{$battery_type}}">{{$battery_type == 1 ? "Swappable" : "Fixed"}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="hub_id" class="form-label">Hub</label>
                                    <select class="form-control selectBasic" name="hub_id" id="hub_id">
                                        @foreach($hubs as $key => $hub)
                                        <option value="{{$hub->hub_id}}">{{$hub->city}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status_id" class="form-label">Status</label>
                                    <select class="form-control selectBasic" name="status_id" id="status_id">
                                        @foreach($evStatus as $key => $status_id)
                                        <option value="{{$status_id}}">{{$status_id == 1 ? "Active" : ($status_id == 2 ? 'Inactive' : ($status_id == 3 ? 'Non Functional' : ($status_id == 4 ? 'Assigned' : ($status_id == 6 ? 'RFD' : ''))))}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Description &nbsp; <span class="spanColor description_error"></span></label>
                                    <textarea id="description" name="description" class="form-control" rows="5" placeholder="Write here."></textarea>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="title" class="form-label">Image Upload</label>
                                    <div class="">
                                        <label for="productFile" class="selectImageRemove">
                                            <img class="upload_des_preview clickable selectedImage " src="{{asset('public/assets/images/uploadimg.png')}}" alt="example placeholder" />
                                        </label>
                                        <input type="file" class="form-control d-none customFile" name="image" id="productFile" />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember-check" name="is_display_on_app">
                                        <label class="form-check-label mt-1" for="remember-check">
                                            &nbsp; The product is displayed on the app.
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <button type="button" class="btn btn-success " id="submitForm">Add </button>
                                    <span class="text-success d-block" id="message" style="margin-right: 10px"></span>
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
    $(document).ready(function() {

        $('#ev_type_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            var value1 = selectedOption.data('value1');
            var value2 = selectedOption.data('value2');
            var value3 = selectedOption.data('value3');
            var value4 = selectedOption.data('value4');

            $('#km_per_charge').val(value1);
            $('#speed').val(value2);
            $('#per_day_rent').val(value3);
            $('#total_range').val(value4);
        });

        $('#submitForm').click(function(e) {
            e.preventDefault();
            var name = $('#title').val();
            if (name == "") {
                $(".title_error").html('This field is required!');
                $("input#title").focus();
                return false;
            }
            var name = $('#ev_number').val();
            if (name == "") {
                $(".ev_number_error").html('This field is required!');
                $("input#ev_number").focus();
                return false;
            }
            var name = $('#chassis_number').val();
            if (name == "") {
                $(".chassis_number_error").html('This field is required!');
                $("input#chassis_number").focus();
                return false;
            }
            var ev_type_id = $('#ev_type_id').val();
            if (ev_type_id == "") {
                $(".ev_type_id_error").html('This field is required!');
                $("select#ev_type_id").focus();
                return false;
            }
            var speed = $('#speed').val();
            if (speed == "") {
                $(".speed_error").css("display", "");
                $(".speed_error").html('This field is required!');
                $(".ev_type_name_error").html('');
                $(".range_error").html('');
                $("input#speed").focus();
                return false;
            }
            var range = $('#range').val();
            if (range == "") {
                $(".range_error").css("display", "");
                $(".range_error").html('This field is required!');
                $(".ev_type_name_error").html('');
                $("input#km_per_charge").focus();
                return false;
            }

            $('#submitForm').prop('disabled', true);
            $('#submitForm').html('Please wait...')
            //var imageData = $('#customFile')[0].files[0]; // Get the image file

            var formDatas = new FormData(document.getElementById('createProductForm'));
            // formDatas.append('image', imageData);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-product') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitForm').prop('disabled', false);
                    setTimeout(function() {
                        window.location = data.url;
                    }, 1000);

                },
                errors: function() {
                    $('#message').html("<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });
    });
</script>
@endsection