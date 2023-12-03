@extends('admin.layouts.app')
@section('title', 'Distributed Hub')
@section('css')
<style>
    input[switch]+label {
        width: 75px !important;
    }

    input[switch]:checked+label:after {
        left: 54px !important;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="nav_cust_menu">
                <ul>
                    <li><a href="{{route('products','corporate')}}" class="" title="Products">Products</a></li>
                    {{-- @can('view_inventry', $permission)
                    <li><a href="" class="active" title="Products">Products</a></li>
                    @endcan --}}
                    <li><a href="{{route('product-categories')}}" class="active" title="Product Categories">Product Categories</a></li>
                    <li><a href="{{route('product-ev-types')}}" class="" title="Ev Types">Ev Types</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom bg-white">
                    <h4>Product Categories List</h4>
                    <div class="btn-card-header">
                        @can('add_product_type', $permission)
                        <a class="btn btn-success waves-effect waves-light categoryModelForm" data-toggle="modal" title="Add Hub">Add New Category</a>
                        @endcan
                    </div>
                </div>
                <div class="table-rep-plugin">
                    @if (count($product_categories) > 0)
                    <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                        <div class="sticky-table-header">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Serial No</th>
                                        <th>Number of Unit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_categories as $key => $pc)
                                    <tr>
                                        <td>{{ $pc->product_category_name }}</td>
                                        <td>{{ $pc->serial_number }}</td>
                                        <td>{{ $pc->item_in_stock }}</td>
                                        <td>
                                            @can('edit_product_type', $permission)
                                            <a class="categoryModelForm" data-toggle="modal" data-category_name="{{ $pc->product_category_name }}" data-slug="{{ $pc->slug }}" data-serial="{{ $pc->serial_number }}" data-item="{{ $pc->item_in_stock }}" title="Edit Product Category" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <p>No category found</p>
                    @endif
                </div>
            </div>
        </div>
        <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
<!-- Add product category model -->
<div class="modal fade" id="categoryModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Add Product Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateCategory" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" name="slug" id="slug">
                            <div class="form-group mb-2">
                                <label for="product_category_name" class="col-form-label">Product Category Name <sup class="compulsayField">*</sup> <span class="spanColor product_category_name_error"></span></label>
                                <input type="text" name="product_category_name" class="form-control" id="product_category_name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="item_in_stock" class="col-form-label ">Item In Stock &nbsp;<span class="spanColor onlyDigit_error" id="item_in_stock_errors"></span></label>
                                <input type="text" name="item_in_stock" class="form-control onlyDigit" id="item_in_stock">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="serial_number" class="col-form-label">Starting Serial Number</label>
                                <input type="text" name="serial_number" class="form-control" id="serial_number">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitCategory" class="btn btn-success waves-effect waves-light">Add
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
        $('.categoryModelForm').click(function() {
            $('#categoryModelForm').modal('show');
            var slug = $(this).data('slug');
            if (slug) {
                var category_name = $(this).data('category_name');
                var serial = $(this).data('serial');
                var slug = $(this).data('slug');
                var item = $(this).data('item');

                $("#slug").val(slug);
                $("#product_category_name").val(category_name);
                $("#serial_number").val(serial);
                $("#item_in_stock").val(item);
            }

            if (slug) {
                $('#submitCategory').html('Update')
                $('#categoryModalLabel').html('Edit Product Category')
            }
        });
        $('#submitCategory').click(function(e) {
            e.preventDefault();
            var product_category_name = $('#product_category_name').val();
            if (product_category_name == "") {
                $(".product_category_name_error").html('This field is required!');
                $("input#product_category_name").focus();
                return false;
            }
            $('#submitCategory').prop('disabled', true);
            $('#submitCategory').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUpdateCategory'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-update-product-category') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message +
                        "</span>");
                    $('#submitCategory').prop('disabled', false);
                    $('#submitCategory').html('Update');
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