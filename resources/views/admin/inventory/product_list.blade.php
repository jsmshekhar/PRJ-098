@extends('admin.layouts.app')
@section('title', 'Products')
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
                        <li><a href="{{ route('products', 'corporate') }}" class="active" title="Products">Products</a></li>
                        {{-- @can('view_inventry', $permission)
                    <li><a href="" class="active" title="Products">Products</a></li>
                    @endcan --}}
                        <li><a href="{{ route('product-ev-types') }}" class="" title="Ev Types">Ev Types</a></li>
                        @if (Auth::user()->role_id == 0)
                            <li><a href="{{ route('accessories') }}" class="" title="Accessories">Accessories</a>
                        @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card m-0 border-0">
                    <div class="card-header border-bottom bg-white py-3">
                        <h4>Choose profile</h4>
                    </div>
                    <div class="cat_list">
                        <ul>
                            <li class="{{ request()->route('param') == 'corporate' ? 'active' : '' }}">
                                <a href="{{ route('products', 'corporate') }}" title="Corporate's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/CorporateEmployee.png') }}"
                                        alt="Card image">
                                    <span>Corporate</span>
                                </a>
                            </li>
                            <li class="{{ request()->route('param') == 'individual' ? 'active' : '' }}">
                                <a href="{{ route('products', 'individual') }}" title="Individual's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Individual.png') }}" alt="product image">
                                    <span>Individual</span>
                                </a>
                            </li>
                            <li class="{{ request()->route('param') == 'student' ? 'active' : '' }}">
                                <a href="{{ route('products', 'student') }}" title="Student's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Student.png') }}" alt="product image">
                                    <span>Student</span>
                                </a>
                            </li>
                            <li class="{{ request()->route('param') == 'vendor' ? 'active' : '' }}">
                                <a href="{{ route('products', 'vendor') }}" title="Vendor's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Vendor.png') }}" alt="product image">
                                    <span>Vendor</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card nav_page">
                    <div class="card-header border-bottom bg-white py-3">
                        <h4>Products</h4>
                        <div class="btn-card-header">
                            <a class="btn btn-success waves-effect waves-light"
                                href="{{ route('product-create', request()->route('param')) }}" title="Add Product">Add
                                Product</a>
                        </div>
                    </div>
                    @if (count($products) > 0)
                        <div class="cat_list cat_sub">

                            <ul>
                                @foreach ($products as $key => $product)
                                    <li class="">
                                        <div class="cat_suv">{{ $product->title }} <span
                                                class="stock_in">{{ $product->status_id }}</span> </div>
                                        <img class="card-img img-fluid"
                                            src="{{ $product->image ? asset('public/upload/product/' . $product->image) : asset('public/assets/images/logo-sm.svg') }}"
                                            alt="product image">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ $product->bike_type }}</span>
                                            <div class="sub_btns">
                                                <a
                                                    href="{{ route('product-edit', ['slug' => $product->slug, 'param' => $product->profile_category]) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @can('delete_inventry', $permission)
                                                    <form id="delete-form-{{ $product->slug }}" method="post"
                                                        action="{{ route('product-delete', $product->slug) }}"
                                                        style="display: none;">
                                                        @csrf
                                                        {{ method_field('POST') }} <!-- delete query -->
                                                    </form>
                                                    <a href="" class=""
                                                        onclick="
                                    if (confirm('Are you sure, You want to delete?'))
                                    {
                                        event.preventDefault();
                                        document.getElementById('delete-form-{{ $product->slug }}').submit();
                                    }else {
                                        event.preventDefault();
                                    }
                                    "
                                                        title="delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endcan
                                                @if ($product->is_display_on_app == 1)
                                                    <i class="fa fa-mobile text-secondry"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                        {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
                    @else
                        <div>
                            @include('admin.common.no_record')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript"></script>
@endsection
