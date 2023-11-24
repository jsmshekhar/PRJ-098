<div class="table-rep-plugin">
    <div class="table-responsive mb-0" data-pattern="priority-columns">
        <table id="tech-companies-1" class="table">
            <thead>
                <tr>
                    <th>EMP ID</th>
                    <th>EMP NAME</th>
                    <th>EMP EMAIL</th>
                    <th>EMP PHONE</th>
                    <th>EMP ROLE</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $key => $employee)
                <tr>
                    <td>EVA2Z{{$employee->emp_id}}</td>
                    <td>{{$employee->first_name}} {{$employee->last_name}}</td>
                    <td>{{$employee->email}}</td>
                    <td>{{$employee->phone}}</td>
                    <td>
                        <span class="badge bg-success text-white p-1"> {{ucfirst($employee->role_name)}}</span>
                    </td>
                    {{--<td>
                        @if($employee->status_id == 1)
                        <label class="text-success">Active</label>
                        @elseif($employee->status_id == 2)
                        <label class="text-danger">Inactive</label>
                        @endif
                    </td>--}}
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <input type="checkbox" id="switch3{{$key}}" onclick="toggleStatus('switch3{{$key}}', 'employee')" switch="bool" {{ $employee->status_id == 1 ? 'checked' : '' }} value=" {{$employee->slug}}">
                            <label for="switch3{{$key}}" data-on-label="Active" data-off-label="Inactive"></label>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown">
                            <a href="#" class="btn btn-link p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu">
                                @can('edit_user', $permission)
                                <a class="dropdown-item userModelForm" data-toggle="modal" data-fname="{{ $employee->first_name }}" data-lname="{{ $employee->last_name }}" data-email="{{ $employee->email }}" data-phone="{{ $employee->phone }}" data-slug="{{ $employee->slug }}" data-roleid="{{ $employee->role_id }}" data-hub_id="{{ $employee->hub_id }}" title="Edit Employee" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i> Edit
                                </a>
                                @endcan
                                @can('delete_user', $permission)
                                <form id="delete-form-{{$employee->slug}}" method="post" action="{{ route('user-delete', $employee->slug) }}" style="display: none;">
                                    @csrf
                                    {{ method_field('POST') }} <!-- delete query -->
                                </form>
                                <a href="" class="dropdown-item" onclick="
                                if (confirm('Are you sure, You want to delete?'))
                                {
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$employee->slug}}').submit();
                                }else {
                                    event.preventDefault();
                                }
                                " title="delete">
                                    <i class="fa fa-trash" style="color:#d74b4b;"></i> Delete
                                </a>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $employees->withQueryString()->links('pagination::bootstrap-4') }}
</div>

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
                    <input type="hidden" class="form-control" name="hub_id" id="hub_id">
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
                        <select class="form-control" name="role_id" id="role_id">
                            @foreach($roles as $role)
                            <option value="{{$role->role_id}}">{{ucfirst($role->name)}}</option>
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