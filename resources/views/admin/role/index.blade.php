@php
use Illuminate\Support\Facades\DB;
@endphp
@extends('admin.layouts.app')
@section('title', 'Roles')
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
                    @can('view_user', $permission)
                    <li><a href="{{ route('users') }}" class="" title="User Panel">User Panel</a></li>
                    @endcan
                    @can('view_role', $permission)
                    <li><a href="{{ route('roles') }}" class="active" title="Permission Panel">Role Permission Panel</a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="accordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header justify-content-end" id="headingOnes">
                                <div class="collaps_btns">
                                    @can('add_role', $permission)
                                    <a class="btn btn-success waves-effect waves-light roleModelForm" data-toggle="modal" title="Add User">Add New Role</a>
                                    @endcan
                                </div>
                            </h2>
                        </div>
                    </div><!-- end accordion -->
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-12">
            <div class="card">
                <div class="card-body permission_tbl pt-0">
                    <div class="table-rep-plugin">
                        <div class="col-md-12">
                            <div class="row permission_tbl_row">
                                <div class="col-md-4">
                                    <h5>Role Name</h5>
                                </div>
                                <div class="col-md-4">
                                    <h5>No of Users Mapped to the Role</h5>
                                </div>
                                <div class="col-md-4">
                                    <h5>Action</h5>
                                </div>
                            </div>
                            @foreach ($roles as $role)
                            <div class="row d-flex align-items-center border justify-content-between">
                                <div class="col-md-4">
                                    <p>{{ ucfirst($role->name) }} : Permissions</p>
                                </div>
                                <div class="col-md-4">
                                    <p>{{ $role->roleUsers }}</p>
                                </div>
                                <div class="col-md-4">
                                    <span>
                                        @can('edit_role', $permission)
                                        <a class="roleModelForm" data-toggle="modal" data-id="{{ $role->role_id }}" data-name="{{ $role->name }}" data-slug="{{ $role->slug }}" title="Edit Role" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
                                        </a>
                                        @endcan @can('edit_role', $permission)
                                        | <form id="delete-form-{{ $role->slug }}" method="post" action="{{ route('role-delete', $role->slug) }}" style="display: none;">
                                            @csrf
                                            {{ method_field('POST') }} <!-- delete query -->
                                        </form>
                                        <a href="" class="shadow btn-xs sharp" onclick="
                                                if (confirm('Are you sure, You want to delete?'))
                                                {
                                                    event.preventDefault();
                                                    document.getElementById('delete-form-{{ $role->slug }}').submit();
                                                }else {
                                                    event.preventDefault();
                                                }
                                                " title="Delete role">
                                            <i class="fa fa-trash" style="color:#d74b4b;"></i>
                                        </a>
                                        @endcan @can('allow_permission', $permission)
                                        |
                                        <button class="btn btn-blue btn-sm text-primary show_hide{{ $role->role_id }}">Show
                                            Permissions</button>
                                        @endcan
                                    </span>
                                </div>
                                <div class="col-md-12 p-0">
                                    <div class="slidingDiv{{ $role->role_id }}" style="display: none">
                                        <div class="card-body py-0">
                                            <form method="post" style="position:relative">
                                                @csrf
                                                @foreach ($permissions as $key => $row)
                                                <div class="row">
                                                    <div class="col-md-3 border-bottom py-2">
                                                        <h5 class="mb-1">{{ ucfirst($row->name) }}</h5>
                                                    </div>
                                                    <div class="col-md-9 border-bottom pt-2">
                                                        <ul class="list-inline" style="margin-left: 30px;">
                                                            @foreach ($row->sub_module as $value)
                                                            <li class="list-inline-item mr-3">
                                                                <?php $select = DB::table('permission_roles')
                                                                    ->where('role_id', $role->role_id)
                                                                    ->where('permission_id', $value->permission_id)
                                                                    ->get(); ?>
                                                                <label class="form-check-label">{{ ucfirst($value->name) }}</label>
                                                                <div class="d-flex flex-wrap gap-2">

                                                                    <input type="checkbox" id="switch{{ $role->role_id }}{{ $row->module_id }}{{ $value->permission_id }}" onclick="toggleStatus('switch{{ $role->role_id }}{{ $row->module_id }}{{ $value->permission_id }}', '{{ $value->permission_id }}', '{{ $role->role_id }}')" switch="bool" @foreach ($select as $selected) checked @endforeach value="{{ $value->permission_id }}">
                                                                    <label for="switch{{ $role->role_id }}{{ $row->module_id }}{{ $value->permission_id }}" data-on-label="Yes" data-off-label="No"></label>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->

</div>
<!-- Add role model -->
<div class="modal fade" id="roleModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateRole">
                    @csrf
                    <input type="hidden" class="form-control" name="slug" id="role_slug">
                    <div class="mb-3">
                        <label for="role-name" class="col-form-label">Role Name <sup class="compulsayField">*</sup>
                            <span class="spanColor name_error"></span></label>
                        <input type="text" name="role_name" class="form-control" id="role_name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span class="text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitRole" class="btn btn-primary">Add
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
        $('.roleModelForm').click(function() {
            $('#roleModelForm').modal('show');

            var slug = $(this).data('slug');
            if (slug) {
                var name = $(this).data('name');
                $("#role_name").val(name);
                $("#role_slug").val(slug);
                $('#submitRole').html('Update');
                $('#roleModalLabel').html('Edit Role');
            }
        });
        $('#submitRole').click(function(e) {
            e.preventDefault();
            var name = $('#role_name').val();
            if (name == "") {
                $(".name_error").html('This field is required!');
                $("input#role_name").focus();
                return false;
            }
            $('#submitRole').prop('disabled', true);
            $('#submitRole').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUpdateRole'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-update-role') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message +
                        "</span>");
                    $('#submitRole').prop('disabled', false);
                    $('#submitRole').html('Update');
                        window.location.reload();

                },
                errors: function() {
                    $('#message').html(
                        "<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });
    });

    // Active inactive status toggle
    function toggleStatus(toggleId, permissionId, roleId) {
        var newStatus = $(this).prop("checked");
        console.log(permissionId, 'roleid', roleId, 'statustype', newStatus)
        var token = "{{ csrf_token() }}";
        if (permissionId) {
            $.ajax({
                url: "{{ route('user-role-permission') }}",
                type: 'POST',
                data: {
                    "role_id": roleId,
                    "permission_id": permissionId,
                    "_token": token,
                },
                success: function(data) {
                    //window.location.reload();
                }
            });
        }
    }
</script>
<!-- Accordian show hide -->
@foreach ($roles as $row)
<script>
    $(document).ready(function() {
        $(".slidingDiv{{ $row->role_id }}").hide();
        $('.show_hide{{ $row->role_id }}').click(function(e) {
            e.preventDefault();
            $(".slidingDiv{{ $row->role_id }}").slideToggle("fast");
            var val = $(this).text() == "Hide Permissions" ? "Show Permissions" : "Hide Permissions";
            $(this).hide().text(val).fadeIn("fast");

        });
    });
</script>
@endforeach
@endsection