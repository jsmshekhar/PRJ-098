@extends('admin.layouts.app')
@section('title', 'User Management')
@section('css')
<style>

</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    @can('view_user', $permission)
                    <a href="{{route('users')}}" class="btn btn-info btn-sm active" title="User Panel">User Panel</a>
                    @endcan
                    @can('view_role', $permission)
                    <a href="{{route('roles')}}" class="btn btn-info btn-sm" title="Permission Panel">Permission Panel</a>
                    @endcan
                </div>
                <h4 class="mb-sm-0 font-size-18">User Management</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Advance Search
                                </button>
                                <div class="collaps_btns">
                                    <button type="button" class="btn btn-outline-danger waves-effect waves-light">Clear</button>
                                    <button type="button" class="btn btn-outline-success waves-effect waves-light">Search</button>
                                    @can('add_user', $permission)
                                    <a class="btn btn-success waves-effect waves-light userModelForm" data-toggle="modal" title="Add Role">Add New User</a>
                                    @endcan
                                </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="" method="post">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">User Id</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">User Name</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Email Id</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Email Address</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Role</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- end accordion -->
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-rep-plugin">
                        @if(count($users) >0)
                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <div class="sticky-table-header">
                                <table class="table table-striped ">
                                    <thead>
                                        <tr>
                                            <th>User Id</th>
                                            <th>User Name</th>
                                            <th>Email Id</th>
                                            <th>Phone Number</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $key => $user)
                                        <tr>
                                            <th>EVA{{$user->emp_id}}</th>
                                            <th>{{$user->first_name}} {{$user->last_name}}</th>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->phone}}</td>
                                            <td>
                                                <span class="badge bg-success text-white p-1"> {{ucfirst($user->role_name)}}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <input type="checkbox" id="switch3{{$key}}" onclick="toggleStatus('switch3{{$key}}')" switch="bool" {{ $user->status_id == 1 ? 'checked' : '' }} value="{{$user->slug}}">
                                                    <label for="switch3{{$key}}" data-on-label="Active" data-off-label="Inactive"></label>
                                                </div>

                                            </td>
                                            <td>
                                                @can('edit_user', $permission)
                                                <a class="userModelForm" data-toggle="modal" data-fname="{{ $user->first_name }}" data-lname="{{ $user->last_name }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone }}" data-slug="{{ $user->slug }}" data-roleid="{{ $user->role_id }}" data-rolename="{{ $user->rolename }}" title="Edit User" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
                                                </a> @endcan @can('delete_user', $permission) | <form id="delete-form-{{ $user->slug }}" method="post" action="{{route('user-delete',$user->slug)}}" style="display: none;">
                                                    @csrf
                                                    {{method_field('POST')}} <!-- delete query -->
                                                </form>
                                                <a href="" class="shadow btn-xs sharp" onclick="
                                                        if (confirm('Are you sure, You want to delete?')) 
                                                        {
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{ $user->slug }}').submit();
                                                        }else {
                                                            event.preventDefault();
                                                        }
                                                        " title="delete">
                                                    <i class="fa fa-trash" style="color:#d74b4b;"></i>
                                                </a> @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                        @else
                        <p>No reords found</p>
                        @endif
                    </div>

                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->

</div>
<!-- Add role model -->
<div class="modal fade" id="userModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateUser">
                    @csrf
                    <input type="hidden" class="form-control" name="slug" id="slug">
                    <div class="mb-3">
                        <label for="role-name" class="col-form-label">First Name <sup class="compulsayField">*</sup> <span class="spanColor name_error"></span></label>
                        <input type="text" name="first_name" class="form-control" id="first_name">
                    </div>
                    <div class="mb-3">
                        <label for="role-name" class="col-form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" id="last_name">
                    </div>
                    <div class="mb-3">
                        <label for="role-name" class="col-form-label">Email <sup class="compulsayField">*</sup> <span class="spanColor email_error"></span></label>
                        <input type="text" name="email" class="form-control" id="email">
                    </div>
                    <div class="mb-3">
                        <label for="role-name" class="col-form-label">Phone No.</label>
                        <input type="text" name="phone" class="form-control" id="phone">
                    </div>
                    <div class="mb-3">
                        <label for="role-name" class="col-form-label">Role</label>
                        <select class="form-control" name="role_id" id="role_id">
                            @foreach($roles as $role)
                            <option value="{{$role->role_id}}">{{ucfirst($role->name)}}</option>
                            @endforeach
                        </select>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span class="text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitUser" class="btn btn-primary">Add
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
        $('.userModelForm').click(function() {
            $('#userModelForm').modal('show');
            var first_name = $(this).data('fname');
            var last_name = $(this).data('lname');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var slug = $(this).data('slug');
            var roleid = $(this).data('roleid');
            var rolename = $(this).data('rolename');

            $("#first_name").val(first_name);
            $("#last_name").val(last_name);
            $("#email").val(email);
            $("#phone").val(phone);
            $("#slug").val(slug);
            //$("#role_id").find(':selected').attr('data-roleid')
            $("#role_id").val(roleid);
            $("#rolename").val(rolename);

            if (slug) {
                $('#submitUser').html('Update')
            }
            if (slug) {
                $('#userModalLabel').html('Edit User')
            }
        });
        $('#submitUser').click(function(e) {
            e.preventDefault();
            var name = $('#first_name').val();
            if (name == "") {
                $(".name_error").html('This field is required!');
                $("input#first_name").focus();
                return false;
            }
            var name = $('#email').val();
            if (name == "") {
                $(".email_error").html('This field is required!');
                $("input#email").focus();
                return false;
            }
            $('#submitUser').prop('disabled', true);
            $('#submitUser').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUpdateUser'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-update-user') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitUser').prop('disabled', false);
                    $('#submitUser').html('Update');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                },
                errors: function() {
                    $('#message').html("<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });
    });

    // Active inactive status toggle
    function toggleStatus(toggleId) {
        var slug = $("#" + toggleId).val();
        var newStatus = $(this).prop("checked");
        var token = "{{ csrf_token() }}";
        if (slug) {
            $.ajax({
                url: "{{route('user-status-changed')}}",
                type: 'POST',
                data: {
                    "slug": slug,
                    "_token": token,
                },
                success: function(data) {
                    //window.location.reload();
                }
            });
        }
    }
</script>
@endsection