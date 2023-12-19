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
                    <h4>Add Product &nbsp; <b>{ {{ucfirst(request()->route('param'))}} }</b></h4>
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
                                    <label for="example-title-input" class="form-label">EV Number* &nbsp; <span class="spanColor ev_number_error"></label>
                                    <input class="form-control" type="text" name="ev_number" id="ev_number" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="example-title-input" class="form-label">Chassis Number* &nbsp; <span class="spanColor chassis_number_error"></label>
                                    <input class="form-control" type="text" name="chassis_number" id="chassis_number" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="example-title-input" class="form-label">GPS IMEI</label>
                                    <input class="form-control" type="text" name="gps_emei_number" id="gps_emei_number" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="example-title-input" class="form-label">Speed (km/h)</label>
                                    <input class="form-control" type="text" name="speed" id="speed" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="example-title-input" class="form-label">Single charge Run Time(km)</label>
                                    <input class="form-control" type="text" name="per_day_rent" id="per_day_rent" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="example-title-input" class="form-label">Rent per Day(Rs)</label>
                                    <input class="form-control" type="text" name="km_per_charge" id="km_per_charge" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="rent_cycle" class="form-label">Rent Cycle</label>
                                    <select class="form-control selectBasic" name="rent_cycle" id="rent_cycle">
                                        @foreach($rent_cycles as $key => $rent_cycle)
                                        <option value="{{$rent_cycle}}">{{$rent_cycle == 15 ? "15 Days" : "30 Days"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="battery_type" class="form-label">Battery Type</label>
                                    <select class="form-control selectBasic" name="battery_type" id="battery_type">
                                        @foreach($battery_types as $key => $battery_type)
                                        <option value="{{$battery_type}}">{{$battery_type == 1 ? "Swappable" : "Fixed"}}</option>
                                        @endforeach
                                    </select>
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
                                    <label for="ev_type_id" class="form-label">EV Type</label>
                                    <select class="form-control selectBasic" name="ev_type_id" id="ev_type_id">
                                        <option value="">Select EV Type</option>
                                        @foreach($ev_types as $key => $ev_type)
                                        <option value="{{$ev_type->ev_type_id}}">{{$ev_type->ev_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="hub_id" class="form-label">Hub</label>
                                    <select class="form-control selectBasic" name="hub_id" id="hub_id">
                                        <option value="">Select Hub</option>
                                        @foreach($hubs as $key => $hub)
                                        <option value="{{$hub->hub_id}}">{{$hub->city}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="hub_id" class="form-label">Product Visivility</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember-check" name="is_display_on_app" checked>
                                        <label class="form-check-label" for="remember-check">
                                            &nbsp; The product is displayed on the app.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Description &nbsp; <span class="spanColor description_error"></span></label>
                                    <textarea id="description" name="description" class="form-control" rows="5" placeholder="Write here."></textarea>
                                </div>
                                <div class="col-md-2 mb-3 d-flex justify-content-center">
                                    <div class="btn btn-primary btn-rounded">
                                        <label class="form-label text-white m-1" for="customFile1">Choose Image</label>
                                        <input type="file" class="form-control d-none" name="image" id="customFile1" onchange="displaySelectedImage(event, 'selectedImage')" />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 d-flex justify-content-center">
                                    <img id="selectedImage" src="{{ asset('public/assets/images/no-image.bmp') }}" alt="example placeholder" style="width: 200px;" />
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
            $('#submitForm').prop('disabled', true);
            $('#submitForm').html('Please wait...')
            var formDatas = new FormData(document.getElementById('createProductForm'));
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
                    }, 3000);

                },
                errors: function() {
                    $('#message').html("<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });
    });

    function displaySelectedImage(event, elementId) {
        const selectedImage = document.getElementById(elementId);
        const fileInput = event.target;
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                selectedImage.src = e.target.result;
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>
@endsection