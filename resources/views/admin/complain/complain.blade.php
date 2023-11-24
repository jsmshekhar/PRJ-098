@extends('admin.layouts.app')
@section('title', 'Complain & Queries')
@section('css')
<style>
    input[switch]+label {
        width: 75px !important;
    }

    input[switch]:checked+label:after {
        left: 54px !important;
    }

    .changeAssignment {
        margin-top: 10px;
        transform: translate(10%, 35%);
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="nav_cust_menu">
                <ul>
                    @can('view_complaint', $permission)
                    <li><a href="{{route('complain-queries')}}" class="active" title="User Panel">Complain & Queries</a></li>
                    @endcan
                    <li><a href="{{route('complain-categories')}}" class="" title="Permission Panel">Complain Categories</a>
                    </li>
                </ul>
            </div>
        </div>
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
                                    <button type="button" class="btn btn-success waves-effect waves-light">Search</button>
                                </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="" method="post">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Complain Id</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Complain Category</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Complainer Name</label>
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
                                                    <label class="form-label">Complain Date</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Complain
                                                        Status</label>
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
                    <div class="table-filter">
                        <ul>
                            <li>
                                <a href="#" class="btn btn-link">
                                    <img src="{{asset('public/assets/images/icons/refresh.svg')}}" alt="">
                                </a>
                            </li>
                            <li>
                                <p>Total Record : <span>255</span></p>
                            </li>
                            <li>
                                <p>Display up to :
                                <div class="form-group">
                                    <select class="form-control" name="choices-single-no-sorting" id="choices-single-no-sorting">
                                        <option value="Madrid">50</option>
                                        <option value="Toronto">25</option>
                                    </select>
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
                        @if(count($complains) >0)
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table">
                                <thead>
                                    <tr>
                                        <th>Complain Id</th>
                                        <th>Complain Category</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Complain Date</th>
                                        <th>Complain Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complains as $key => $complain)
                                    <tr>
                                        <td>
                                            <a class="complainModelForm" data-toggle="modal" data-description="{{ $complain->description }}" data-name="{{ $complain->name }}" data-email="{{ $complain->email }}" data-phone="{{ $complain->phone }}" data-date="{{ date('d M, Y', strtotime($complain->created_at)) }}" data-category="{{ $complain->complain_category }}" data-cnumber="{{ $complain->complain_number }}" data-slug="{{ $complain->slug }}" data-cname="{{ $complain->category_name }}" data-userid="{{ $complain->user_id }}" title="View Description" style="cursor: pointer;margin-right: 5px;">{{$complain->complain_number}}
                                            </a>
                                        </td>
                                        <td>{{$complain->category_name}}
                                        </td>
                                        <td>{{$complain->name}}
                                        </td>
                                        <td>{{$complain->email}}</td>
                                        <td>{{$complain->phone}}</td>
                                        <td>{{date('d M, Y', strtotime($complain->created_at))}}</td>
                                        <td>
                                            @can('change_complaint_status', $permission)
                                            <div class="d-flex flex-wrap gap-2">
                                                <input type="checkbox" id="switch3{{$key}}" onclick="toggleStatus('switch3{{$key}}')" switch="bool" {{ $complain->status_id == 1 ? 'checked' : '' }} value="{{$complain->slug}}">
                                                <label for="switch3{{$key}}" data-on-label="Resolved" data-off-label="Pending"></label>
                                            </div>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $complains->withQueryString()->links('pagination::bootstrap-4') }}
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
<div class="modal fade" id="complainModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">View Complain & Query</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Complain ID: </h5>
                        <p id="cid"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Complain Date: </h5>
                        <p id="date"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Complain Category: </h5>
                        <p id="category"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Name: </h5>
                        <p id="name"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Email: </h5>
                        <p id="email"></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Phone: </h5>
                        <p id="phone"></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h5>Description: </h5>
                        <p id="desc"></p>
                    </div>
                </div>
                @can('change_assignment', $permission)
                <div>
                    <form method="POST" action="{{ route('change-complain-assignment') }}" id="changeAssignmentForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" name="slug" id="slug">
                                    <label for="users" class="col-form-label">Change Assignment </label>
                                    <select class="form-control" name="user_id" id="user_id" required>
                                        <option value="">Select User</option>
                                        @foreach($users as $key => $user)
                                        <option value="{{$user->user_id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="users" class="col-form-label">Change Category </label>
                                    <select class="form-control" name="category_slug" id="category_slug">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key => $category)
                                        <option value="{{$category->slug}}">{{$category->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-success mb-3" id="changeAssignment">Change</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        // Model data
        $('.complainModelForm').click(function() {
            $('#complainModelForm').modal('show');
            var cid = $(this).data('cnumber');
            var category_slug = $(this).data('category');
            var cname = $(this).data('cname');
            var description = $(this).data('description');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var date = $(this).data('date');
            var slug = $(this).data('slug');
            var user_id = $(this).data('userid');
            var user_id = $(this).data('userid');

            $("#cid").html(cid);
            $("#category").html(cname);
            $("#desc").html(description);
            $("#name").html(name);
            $("#email").html(email);
            $("#phone").html(phone);
            $("#date").html(date);
            $("#slug").val(slug);
            $("#user_id").val(user_id);
            $("#category_slug").val(category_slug);
        });
    });
    // Active inactive status toggle
    function toggleStatus(toggleId) {
        var slug = $("#" + toggleId).val();

        var newStatus = $(this).prop("checked");
        var token = "{{ csrf_token() }}";
        if (slug) {
            $.ajax({
                url: "{{route('complain-status-changed')}}",
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