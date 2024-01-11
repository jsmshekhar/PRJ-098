@extends('admin.layouts.app')
@section('title', 'Complain Categories')
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
                    @can('view_complaint', $permission)
                    <li><a href="{{route('complain-queries')}}" class="" title="User Panel">Complain & Queries</a></li>
                    @endcan
                    <li><a href="{{route('complain-categories')}}" class="active" title="Permission Panel">Complain Categories</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-filter">
                    <form method="post" enctype="multipart/form-data" action="{{route('add-update-complain-category')}}" id="updateCategory" autocomplete="off">
                        @csrf
                        <div class="row m-0 p-2">
                            <div class="col-md-4 AddCat">
                                <div class="form-group mb-2">
                                    <label for="category_name" class="col-form-label">Category Name</label>
                                    <input type="text" name="category_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="users" class="col-form-label">Default Assignment </label>
                                    <select class="form-control" name="role_id" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $key => $role)
                                        <option value="{{$role->role_id}}">{{$role->name}} ({{$role->city}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="modal-footer d-flex justify-content-between CategoryTop">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Add Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-rep-plugin">
                    @if(count($categories) >0)
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="tech-companies-1" class="table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Assigned To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $key => $category)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$category->category_name}}</td>
                                    <td>{{$category->role_name}}</td>
                                    <td>
                                        <a class="categoryModelForm" data-toggle="modal" data-category="{{ $category->category_name }}" data-slug="{{ $category->slug }}" data-role_id="{{ $category->role_id }}" title="Edit Category" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
                                        </a> | <form id="delete-form-{{ $category->slug }}" method="post" action="{{route('caomplain-category-delete',$category->slug)}}" style="display: none;">
                                            @csrf
                                            {{method_field('POST')}} <!-- delete query -->
                                        </form>
                                        <a href="" class="shadow btn-xs sharp" onclick="
                                                        if (confirm('Are you sure, You want to delete?')) 
                                                        {
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{ $category->slug }}').submit();
                                                        }else {
                                                            event.preventDefault();
                                                        }
                                                        " title="delete">
                                            <i class="fa fa-trash" style="color:#d74b4b;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <label class="p-3">No reords found</label>
                    @endif
                </div>

            </div>
        </div>
        <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
</div>
<div class="modal fade" id="categoryModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{route('add-update-complain-category')}}" id="updateCategory" autocomplete="off">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input type="hidden" class="form-control" name="slug" id="slug">
                            <div class="form-group mb-2">
                                <label for="category_name" class="col-form-label">Category Name</label>
                                <input type="text" name="category_name" class="form-control" id="category_name">
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="users" class="col-form-label">Change Assignment </label>
                            <select class="form-control select2" name="role_id" id="role_id" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $key => $role)
                                <option value="{{$role->role_id}}">{{$role->name}} ({{$role->city}})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <span class=" text-success d-block" id="message" style="margin-right: 10px"></span>
                        <button type="submit" class="btn btn-success waves-effect waves-light">Edit Category
                        </button>
                    </div>
            </form>
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
            var category = $(this).data('category');
            var slug = $(this).data('slug');
            var role_id = $(this).data('role_id');
            $("#category_name").val(category);
            $("#slug").val(slug);
            $('#role_id').val(role_id).trigger('change');
        });
    });
</script>
@endsection