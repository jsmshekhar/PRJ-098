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
                    @if(Auth::user()->role_id == 0)
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
                                <img class="card-img img-fluid" src="{{ asset('public/assets/images/users/CorporateEmployee.PNG') }}" alt="Card image">
                                <span>Corporate</span>
                            </a>
                        </li>
                        <li class="{{ request()->route('param') == 'individual' ? 'active' : '' }}">
                            <a href="{{ route('products', 'individual') }}" title="Individual's Product">
                                <img class="card-img img-fluid" src="{{ asset('public/assets/images/users/Individual.PNG') }}" alt="product image">
                                <span>Individual</span>
                            </a>
                        </li>
                        <li class="{{ request()->route('param') == 'student' ? 'active' : '' }}">
                            <a href="{{ route('products', 'student') }}" title="Student's Product">
                                <img class="card-img img-fluid" src="{{ asset('public/assets/images/users/Student.PNG') }}" alt="product image">
                                <span>Student</span>
                            </a>
                        </li>
                        <li class="{{ request()->route('param') == 'vendor' ? 'active' : '' }}">
                            <a href="{{ route('products', 'vendor') }}" title="Vendor's Product">
                                <img class="card-img img-fluid" src="{{ asset('public/assets/images/users/Vendor.PNG') }}" alt="product image">
                                <span>Vendor</span>
                            </a>
                        </li>


                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom bg-white py-3">
                    <h4>Products</h4>
                    <a class="btn btn-success waves-effect waves-light" href="{{ route('product-create', request()->route('param')) }}" title="Add Product">Add
                        Product</a>
                </div>
                <div class="cat_list cat_sub">
                    @if (count($products) > 0)
                    <ul>
                        @foreach($products as $key => $product)
                        <li class="">
                            <div class="cat_suv">{{$product->title}} <a href="{{ route('product-edit', ['slug' => $product->slug, 'param' => $product->profile_category]) }}" target="_blank">Edit</a></div>
                            <img class="card-img img-fluid" src="{{ asset('public/upload/product/'.$product->image) }}" alt="product image">
                            <span class="stock_in">In Stock</span>
                        </li>
                        @endforeach
                    </ul>
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
</div>
@endsection
@section('js')
<script type="text/javascript"></script>
@endsection