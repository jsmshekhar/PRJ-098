@extends('admin.layouts.app')
@section('title', 'Products')
@section('css')
<style>
    input[switch]+label {
        width: 75px !important;
    }

    input[switch]:checked+label:after {
        left: 54px !important;
    }

    .card-img.img-fluid {
        width: 73%;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="nav_cust_menu">
                <ul>
                    <li><a href="{{route('products','corporate')}}" class="active" title="Products">Products</a></li>
                    {{-- @can('view_inventry', $permission)
                    <li><a href="" class="active" title="Products">Products</a></li>
                    @endcan --}}
                    <li><a href="{{route('product-categories')}}" class="" title="Product Categories">Product Categories</a></li>
                    <li><a href="{{route('product-ev-types')}}" class="" title="Ev Types">Ev Types</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="row g-0 align-items-center">
                    <a href="{{route('products','corporate')}}" class="{{request()->route('evtype')=='corporate' ? 'active' : ''}}" title="Corporate's Product">
                        <div class="col-md-4">
                            <img class="card-img img-fluid" src="{{asset('public/assets/images/users/avatar-1.jpg')}}" alt="Card image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Corporate</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- end col -->
        <div class="col-lg-3">
            <div class="card">
                <div class="row g-0 align-items-center">
                    <a href="{{route('products','individual')}}" class="{{request()->route('evtype')=='individual' ? 'active' : ''}}" title="Individual's Product">
                        <div class="col-md-4">
                            <img class="card-img img-fluid" src="{{asset('public/assets/images/users/avatar-1.jpg')}}" alt="Card image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Individual</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- end col -->
        <div class="col-lg-3">
            <div class="card">
                <div class="row g-0 align-items-center">
                    <a href="{{route('products','student')}}" class="{{request()->route('evtype')=='student' ? 'active' : ''}}" title="Student's Product">
                        <div class="col-md-4">
                            <img class="card-img img-fluid" src="{{asset('public/assets/images/users/avatar-1.jpg')}}" alt="Card image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Student</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- end col -->
        <div class="col-lg-3">
            <div class="card">
                <div class="row g-0 align-items-center">
                    <a href="{{route('products','vendor')}}" class="{{request()->route('evtype')=='vendor' ? 'active' : ''}}" title="Vendor's Product">
                        <div class="col-md-4">
                            <img class="card-img img-fluid" src="{{asset('public/assets/images/users/avatar-1.jpg')}}" alt="Card image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Vendor</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- end col -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom bg-white">
                    <h4>Product Categories List</h4>
                    <div class="btn-card-header">
                        <a class="btn btn-success waves-effect waves-light" href="{{route('product-create',request()->route('evtype'))}}" title="Add Product">Add New Product</a>
                    </div>
                </div>
                <div class="table-rep-plugin">
                    @if (count($products) > 0)
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="tech-companies-1" class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>EV Category</th>
                                    <th>EV Type</th>
                                    <th>Device</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{--@foreach($vehicles as $key => $vehicle)
                                <tr>
                                    <td>{{$vehicle->ev_number}}</td>
                                <td>{{$vehicle->ev_type_name}}</td>
                                <td>{{$vehicle->ev_category_name}}</td>
                                <td>{{$vehicle->profile_category_name}}</td>
                                <td>454646464</td>
                                <td>Paid</td>

                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <input type="checkbox" id="switch3{{$key}}" onclick="toggleStatus('switch3{{$key}}', 'vehicle')" switch="bool" {{ $vehicle->status_id == 1 ? 'checked' : '' }} value=" {{$vehicle->slug}}">
                                        <label for="switch3{{$key}}" data-on-label="Active" data-off-label="Inactive"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-link p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item EvVehicleModelForm" data-toggle="modal" data-product_Id="{{ $vehicle->product_Id }}" data-slug="{{ $vehicle->slug }}" data-hub_id="{{ $vehicle->hub_id }}" data-product_category_id="{{ $vehicle->product_category_id }}" data-ev_number="{{ $vehicle->ev_number }}" data-ev_type_id="{{ $vehicle->ev_type_id }}" data-ev_category="{{ $vehicle->ev_category }}" data-profile_category="{{ $vehicle->profile_category }}" title="Edit Vehicle" style="cursor: pointer;margin-right: 5px;"></i><i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form id="delete-form-{{$vehicle->slug}}" method="post" action="{{ route('product-delete', $vehicle->slug) }}" style="display: none;">
                                                @csrf
                                                {{ method_field('POST') }} <!-- delete query -->
                                            </form>
                                            <a href="" class="dropdown-item" onclick="
                                if (confirm('Are you sure, You want to delete?'))
                                {
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$vehicle->slug}}').submit();
                                }else {
                                    event.preventDefault();
                                }
                                " title="delete">
                                                <i class="fa fa-trash" style="color:#d74b4b;"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                        {{-- {{ $products->withQueryString()->links('pagination::bootstrap-4') }} --}}
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
@endsection
@section('js')
<script type="text/javascript">
    // $(document).ready(function() {
    //     $('#submitCategory').click(function(e) {
    //         e.preventDefault();
    //         var product_category_name = $('#product_category_name').val();
    //         if (product_category_name == "") {
    //             $(".product_category_name_error").html('This field is required!');
    //             $("input#product_category_name").focus();
    //             return false;
    //         }
    //         $('#submitCategory').prop('disabled', true);
    //         $('#submitCategory').html('Please wait...')
    //         var formDatas = new FormData(document.getElementById('addUpdateCategory'));
    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             method: 'POST',
    //             url: "{{ route('add-update-product-category') }}",
    //             data: formDatas,
    //             contentType: false,
    //             processData: false,
    //             success: function(data) {
    //                 $('#message').html("<span class='sussecmsg'>" + data.message +
    //                     "</span>");
    //                 $('#submitCategory').prop('disabled', false);
    //                 $('#submitCategory').html('Update');
    //                 setTimeout(function() {
    //                     window.location.reload();
    //                 }, 1000);

    //             },
    //             errors: function() {
    //                 $('#message').html(
    //                     "<span class='sussecmsg'>Somthing went wrong!</span>");
    //             }
    //         });
    //     });
    // });
</script>
@endsection