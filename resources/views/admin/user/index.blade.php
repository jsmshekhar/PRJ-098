@extends('admin.layouts.app')
@section('title', 'User Management')
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
                    @can('view_user', $permission)
                    <li><a href="{{route('users')}}" class="active" title="User Panel">User Panel</a></li>
                    @endcan
                    @can('view_role', $permission)
                    <li><a href="{{route('roles')}}" class="" title="Permission Panel">Role Permission Panel</a>
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
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Advance Search
                                </button>
                                <div class="collaps_btns">
                                    <button type="button" class="btn btn-outline-danger waves-effect waves-light" onclick="clearSearch('<?= url()->current() ?>');">Clear</button>
                                    <button type="button" onclick="submitSearchForm()" class="btn btn-outline-success waves-effect waves-light">Search</button>
                                    @can('add_user', $permission)
                                    <a class="btn btn-success waves-effect waves-light userModelForm" data-toggle="modal" title="Add Role">Add New User</a>
                                    @endcan
                                </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="searchForm" method="get" action="<?= url()->current() ?>">
                                        <input type="hidden" name="is_search" value="1" />
                                        <input type="hidden" name="per_page" id="perPageHidden" />
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">User ID</label>
                                                    <input type="text" class="form-control" name="emp_id" value="<?= isset($_GET['emp_id']) ? $_GET['emp_id'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" class="form-control" name="first_name" value="<?= isset($_GET['first_name']) ? $_GET['first_name'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" name="last_name" value="<?= isset($_GET['last_name']) ? $_GET['last_name'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="text" class="form-control" name="email" value="<?= isset($_GET['email']) ? $_GET['email'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" class="form-control" name="phone" value="<?= isset($_GET['phone']) ? $_GET['phone'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <label class="form-label">Role</label>
                                                <select class="form-control select2" name="role">
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $key => $role)
                                                    <option value="{{$role->role_id}}" <?= (isset($_GET['role']) && $role->role_id == $_GET['role']) ? 'selected' : '' ?>>{{$role->name}}</option>
                                                    @endforeach
                                                </select>
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
                    <div class="table-filter">
                        <ul>
                            <li>
                                <a href="#" class="btn btn-link" onclick="refreshPage();">
                                    <img src=" {{asset('public/assets/images/icons/refresh.svg')}}" alt="">
                                </a>
                            </li>
                            <li>
                                <p>Total Record : <span>255</span></p>
                            </li>
                            <li>
                                <p>Display up to :
                                <div class="form-group">
                                    @include('admin.layouts.per_page')
                                </div>
                                </p>
                            </li>
                            <li>
                                <button type="button" class="btn btn-success waves-effect waves-light">
                                    <img src="{{asset('public/assets/images/icons/download.svg')}}" alt=""> Export
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="table-rep-plugin">
                        @if(count($users) >0)
                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <div class="sticky-table-header">
                                <table class="table">
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
                                            <td>EVA{{$user->emp_id}}</td>
                                            <td>{{$user->first_name}} {{$user->last_name}}</td>
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
                                                <a class="userModelForm" data-toggle="modal" data-fname="{{ $user->first_name }}" data-lname="{{ $user->last_name }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone }}" data-slug="{{ $user->slug }}" data-roleid="{{ $user->role_id }}" data-hubid="{{ $user->hub_id }}" data-rolename="{{ $user->rolename }}" title="Edit User" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
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
                    <div class="form-group mb-2">
                        <label for="role-name" class="col-form-label">First Name <sup class="compulsayField">*</sup> <span class="spanColor name_error"></span></label>
                        <input type="text" name="first_name" class="form-control" id="first_name">
                    </div>
                    <div class="form-group mb-2">
                        <label for="role-name" class="col-form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" id="last_name">
                    </div>
                    <div class="form-group mb-2">
                        <label for="role-name" class="col-form-label">Email <sup class="compulsayField">*</sup> <span class="spanColor email_error"></span></label>
                        <input type="text" name="email" class="form-control" id="email">
                    </div>
                    <div class="form-group mb-2">
                        <label for="role-name" class="col-form-label">Phone No.</label>
                        <input type="text" name="phone" class="form-control" id="phone">
                    </div>
                    <div class="form-group mb-2">
                        <label for="choices-single-no-search" class="form-label font-size-13 text-muted">Role</label>
                        <select class="form-control select2" name="role_id" id="role_id">
                            @foreach($roles as $role)
                            <option value="{{$role->role_id}}">{{ucfirst($role->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="choices-single-no-search" class="form-label font-size-13 text-muted">Hub</label>
                        <select class="form-control select2" name="hub_id" id="hub_id">
                            @foreach($hubs as $hub)
                            <option value="{{$hub->hub_id}}">{{$hub->city}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class="text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitUser" class="btn btn-success waves-effect waves-light">Add
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
            var hubid = $(this).data('hubid');

            $("#first_name").val(first_name);
            $("#last_name").val(last_name);
            $("#email").val(email);
            $("#phone").val(phone);
            $("#slug").val(slug);
            $('#role_id').val(roleid).trigger('change');
            $("#rolename").val(rolename);
            $('#hub_id').val(hubid).trigger('change');

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