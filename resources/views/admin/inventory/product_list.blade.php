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
                        <li><a href="{{ route('product-categories') }}" class="" title="Accessories">Accessories</a>
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
                            <li class="{{ request()->route('param') == 'individual' ? 'active' : '' }}">
                                <a href="{{ route('products', 'individual') }}" title="Individual's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Individual.PNG') }}" alt="Card image">
                                    <span>Individual</span>
                                </a>
                            </li>
                            <li class="{{ request()->route('param') == 'vendor' ? 'active' : '' }}">
                                <a href="{{ route('products', 'vendor') }}" title="Vendor's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Vendor.PNG') }}" alt="Card image">
                                    <span>Vendor</span>
                                </a>
                            </li>
                            <li class="{{ request()->route('param') == 'student' ? 'active' : '' }}">
                                <a href="{{ route('products', 'student') }}" title="Student's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Student.PNG') }}" alt="Card image">
                                    <span>Student</span>
                                </a>
                            </li>
                            <li class="{{ request()->route('param') == 'corporate' ? 'active' : '' }}">
                                <a href="{{ route('products', 'corporate') }}" title="Corporate's Product">
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/CorporateEmployee.PNG') }}"
                                        alt="Card image">
                                    <span>Corporate Employee</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-bottom bg-white py-3">
                        <h4>Products</h4>
                        <a class="btn btn-success waves-effect waves-light"
                            href="{{ route('product-create', request()->route('param')) }}" title="Add Product">Add
                            Product</a>
                    </div>
                    <div class="cat_list cat_sub">
                        @if (count($products) > 0)
                            <ul>
                                <li class="active">
                                    <div class="cat_suv">Individual <a href="">Edit</a></div>
                                    <img class="card-img img-fluid" src="{{ asset('public/assets/images/users/TVS.PNG') }}"
                                        alt="Card image">
                                    <span class="stock_in">In Stock</span>
                                </li>
                                <li>
                                    <div class="cat_suv">Hero Electrics <a href="">Edit</a></div>
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/HeroElectrics.PNG') }}" alt="Card image">
                                    <span class="stock_in">In Stock</span>
                                </li>
                                <li>
                                    <div class="cat_suv">Shema <a href="">Edit</a></div>
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Shema.PNG') }}" alt="Card image">
                                </li>
                                <li>
                                    <div class="cat_suv">Quantum <a href="">Edit</a></div>
                                    <img class="card-img img-fluid"
                                        src="{{ asset('public/assets/images/users/Quantum.PNG') }}" alt="Card image">
                                </li>
                                <li>
                                    <div class="cat_suv">Individual <a href="">Edit</a></div>
                                    <img class="card-img img-fluid" src="{{ asset('public/assets/images/users/TVS.PNG') }}"
                                        alt="Card image">

                                </li>
                                <li>
                                    <div class="cat_suv">Individual <a href="">Edit</a></div>
                                    <img class="card-img img-fluid" src="{{ asset('public/assets/images/users/TVS.PNG') }}"
                                        alt="Card image">

                                </li>

                            </ul>
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
