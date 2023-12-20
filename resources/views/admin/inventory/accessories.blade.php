@extends('admin.layouts.app')
@section('title', 'Accessories')
@section('css')
<style>

</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="nav_cust_menu">
                <ul>
                    <li><a href="{{ route('products', 'corporate') }}" class="" title="Products">Products</a></li>
                    {{-- @can('view_inventry', $permission)
                    <li><a href="" class="" title="Products">Products</a></li>
                    @endcan --}}
                    <li><a href="{{ route('product-ev-types') }}" class="" title="Ev Types">Ev Types</a></li>
                     @if(Auth::user()->role_id == 0)
                    <li><a href="{{ route('accessories') }}" class="active" title="Accessories">Accessories</a>
                    @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body border-0">
                    <div class="table-rep-plugin">
                        <form method="post" enctype="multipart/form-data" id="addAccessories">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="accessories_category" class="form-label">Accessories Category</label>
                                    <select class="form-control selectBasic" name="accessories_category" id="accessories_category1">
                                        @foreach($accessories_categories as $key => $accCat)
                                        <option value="{{$accCat}}">{{$accCat == 1 ? "Helmet" : ($accCat == 2 ? "T-Shirt" : "Mobile Holder")}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="example-title-input" class="form-label">Title </label>
                                    <input class="form-control" type="text" name="title" id="title1" value="">
                                    <input type="hidden" class="form-control" name="slug" value="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="no_of_item" class="form-label ">No. of Items &nbsp;<span class="spanColor onlyDigit_error"></span></label>
                                    <input type="text" name="no_of_item" class="form-control onlyDigit" id="no_of_item1">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="example-title-input" class="form-label">Per Accessories Amount</label>
                                    <input class="form-control" type="text" name="price" id="price1" value="">
                                </div>
                                <div class="col-md-2 mb-3 d-flex justify-content-center">
                                    <div class="btn btn-primary btn-rounded">
                                        <label class="form-label text-white m-1" for="customFile1">Choose Image</label>
                                        <input type="file" class="form-control d-none" name="image" id="customFile1" onchange="displaySelectedImage(event, 'selectedImage')" />
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3 d-flex justify-content-center">
                                    <img id="selectedImage" src="{{ asset('public/assets/images/no-image.bmp') }}" alt="example placeholder" style="width: 120px;" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button type="button" class="btn btn-success " id="submitForm">Add </button>
                                    <span class="text-success d-block" id="message" style="margin-right: 10px"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-header border-bottom bg-white">
                    <h4>Accessories List</h4>
                </div>
                <div class="table-rep-plugin">
                    @if (count($accessorieses) > 0)
                    <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                        <div class="sticky-table-header">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Accessories Category</th>
                                        <th>Title</th>
                                        <th>No of Items</th>
                                        <th>Price Per Item</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accessorieses as $key => $accessories)
                                    <tr>
                                        <td><img id="selectedImage" src="{{ asset('public/upload/accessories/'.$accessories->image) }}" alt="image" style="width: 30px;"></td>
                                        <td>{{$accessories->accessories_category}}</td>
                                        <td>{{$accessories->title}}</td>
                                        <td>{{$accessories->no_of_item}}</td>
                                        <td>{{$accessories->price}}</td>
                                        <td>{{ $accessories->price * $accessories->no_of_item }}</td>
                                        <td>
                                            <a class="accessoriesModelForm" data-toggle="modal" data-category="{{ $accessories->accessories_category_id }}" data-slug="{{ $accessories->slug }}" data-price="{{ $accessories->price }}" data-title="{{ $accessories->title }}" data-item="{{ $accessories->no_of_item }}" data-image="{{ $accessories->image }}" title="Edit Accessories" style="cursor: pointer;margin-right: 5px;">Edit
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
<!-- Add product category model -->
<div class="modal fade" id="accessoriesModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Add Product Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateAccessories" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" name="slug" id="slug">
                            <div class="form-group mb-2">
                                <label for="accessories_category" class="form-label">Accessories Category</label>
                                <select class="form-control selectBasic" name="accessories_category" id="accessories_category">
                                    @foreach($accessories_categories as $key => $accCat)
                                    <option value="{{$accCat}}">{{$accCat == 1 ? "Helmet" : ($accCat == 2 ? "T-Shirt" : "Mobile Holder")}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="title" class="col-form-label ">Title </label>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="no_of_item" class="form-label ">No. of Items &nbsp;<span class="spanColor onlyDigit_error"></span></label>
                                <input type="text" name="no_of_item" class="form-control onlyDigit" id="no_of_item">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="example-title-input" class="form-label">Amount Per Item</label>
                                <input class="form-control" type="text" name="price" id="price">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 d-flex justify-content-center">
                            <div class="btn btn-primary btn-rounded">
                                <label class="form-label text-white m-1" for="customFile2">Choose Image</label>
                                <input type="file" class="form-control d-none" name="image" id="customFile2" onchange="displaySelectedImage1(event, 'selectedImage1')" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="ImageID">
                            <img id="selectedImage1" src="{{ asset('public/assets/images/no-image.bmp') }}" alt="image" style="width: 120px;">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitAccessories" class="btn btn-success waves-effect waves-light">Add
                </button>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        // Model data
        $('.accessoriesModelForm').click(function() {
            $('#accessoriesModelForm').modal('show');
            var slug = $(this).data('slug');
            if (slug) {
                var category = $(this).data('category');
                var price = $(this).data('price');
                var title = $(this).data('title');
                var item = $(this).data('item');
                var image = $(this).data('image');
                if (image) {
                    var imageUrl = "{{ asset('public/upload/accessories') }}/" + image;
                    $("#ImageID").html('<img id="selectedImage1" src="' + imageUrl + '" alt="image" style="width: 120px;">');
                }
                $("#slug").val(slug);
                $("#title").val(title);
                $("#no_of_item").val(item);
                $("#price").val(price);
                $('#accessories_category').val(category).trigger('change');

            }

            if (slug) {
                $('#submitAccessories').html('Update')
                $('#categoryModalLabel').html('Edit Accessories')
            }
        });
        $('#submitAccessories').click(function(e) {
            e.preventDefault();

            $('#submitAccessories').prop('disabled', true);
            $('#submitAccessories').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUpdateAccessories'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('update-accessories') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message +
                        "</span>");
                    $('#submitAccessories').prop('disabled', false);
                    $('#submitAccessories').html('Update');
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
        $('#submitForm').click(function(e) {
            e.preventDefault();

            $('#submitForm').prop('disabled', true);
            $('#submitForm').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addAccessories'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-accessories') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message +
                        "</span>");
                    $('#submitForm').prop('disabled', false);
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

    function displaySelectedImage1(event, elementId) {
        const selectedImage1 = document.getElementById(elementId);
        const fileInput = event.target;
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                selectedImage1.src = e.target.result;
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>
@endsection