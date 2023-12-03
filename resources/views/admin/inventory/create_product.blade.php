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
                    <h4>Add Product &nbsp; <b>{ {{ucfirst(request()->route('evtype'))}} }</b></h4>
                    <div class="btn-card-header">
                    </div>
                </div>
                <div class="card-body border-0">
                    <div class="table-rep-plugin">

                        <form method="post" enctype="multipart/form-data" id="createProductForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="example-title-input" class="form-label">Title &nbsp; <span class="spanColor title_error"></span></label>
                                    <input class="form-control" type="text" name="title" id="title" value="">
                                    <input class="form-control" type="hidden" name="profile_category" id="profile_category" value="{{request()->route('evtype') == 'corporate' ? 1 :(request()->route('evtype') == 'individual' ? 2 : (request()->route('evtype') == 'student' ? 3 : 4))}}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="example-title-input" class="form-label">EV Number</label>
                                    <input class="form-control" type="text" name="ev_number" id="ev_number" value="">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="example-title-input" class="form-label">Speed</label>
                                    <input class="form-control" type="text" name="speed" id="speed" value="">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="example-title-input" class="form-label">Rent per Day</label>
                                    <input class="form-control" type="text" name="rent_per_day" id="rent_per_day" value="">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="product_category_id" class="col-form-label">Product Category</label>
                                    <select class="form-control selectBasic" name="product_category_id" id="product_category_id">
                                        <option value="">Select Product Category</option>
                                        @foreach($product_categories as $key => $product_category)
                                        <option value="{{$product_category->product_category_id}}">{{$product_category->product_category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="ev_category" class="col-form-label">EV Category</label>
                                    <select class="form-control selectBasic" name="ev_category" id="ev_category">
                                        <option value="">Select EV Category</option>
                                        @foreach($ev_categories as $key => $ev_category)
                                        <option value="{{$ev_category}}">{{$ev_category == 1 ? "Two Wheeler" : "Three Wheeler"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="ev_type_id" class="col-form-label">EV Type</label>
                                    <select class="form-control selectBasic" name="ev_type_id" id="ev_type_id">
                                        <option value="">Select EV Type</option>
                                        @foreach($ev_types as $key => $ev_type)
                                        <option value="{{$ev_type->ev_type_id}}">{{$ev_type->ev_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="hub_id" class="col-form-label">Hub</label>
                                    <select class="form-control selectBasic" name="hub_id" id="hub_id">
                                        <option value="">Select Hub</option>
                                        @foreach($hubs as $key => $hub)
                                        <option value="{{$hub->hub_id}}">{{$hub->city}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="title" class="form-label">Description &nbsp; <span class="spanColor description_error"></span></label>
                                    <textarea id="description" name="description" class="form-control" rows="5" placeholder="Write here.">ghgfjh</textarea>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="button" class="btn btn-success " id="submitForm">Add Product</button>
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
</script>
@endsection