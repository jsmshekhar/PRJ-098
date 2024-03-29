@extends('admin.layouts.app')
@section('title', 'Notification Management')
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
            <div class="card">
                <div class="card-header border-bottom bg-white">
                    <h4 class="card-title">Notification Setting</h4>
                    <div class="page-title-left">
                        @can('set_automatic_notification', $permission)
                        <a href="{{route('create-notification','automatic')}}" class="btn btn-outline-success waves-effect waves-light" title="Add New Notification">Add New Notification</a>
                        @endcan
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-rep-plugin">
                        @if(count($notifications) >0)
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Notification</th>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $key => $notification)
                                    <tr>
                                        <td style="width: 20%;">{{$notification->title}}</td>
                                        <td style="width: 40%;">{{$notification->description}}</td>
                                        <td>{{$notification->notification_parameter}}</td>
                                        <td>{{$notification->notification_type}}</td>
                                        <td>@if($notification->notification_status == 2) <label class="text-danger m-0"> Pending </label>
                                            @else <label class="text-success m-0"> Sent </label>
                                            @endif
                                            {{-- @if($notification->status_id == 1 && $notification->notification_parameter_value !== 3)
                                            <label class="text-success m-0">Active</label>
                                            @elseif($notification->notification_parameter_value == 3 && ($notification->status_id == 3 || $notification->schedule_date < date('Y-m-d'))) <label class="text-danger m-0">Expired</label>
                                                @elseif($notification->status_id == 1 || $notification->schedule_date >= date('Y-m-d')) <label class="text-success m-0">Active</label>
                                                @elseif($notification->status_id == 2) <label class="text-warning m-0">Inactive</label>
                                                @endif--}}

                                        </td>
                                        <td>
                                            @if($notification->notification_status == 2)
                                            <a href="{{ route('edit-notification', [$notification->param, $notification->slug]) }}" class="notificationEditForm" title="Edit Notification" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
                                            </a> | <form id="delete-form-{{ $notification->slug }}" method="post" action="{{route('notification-delete',$notification->slug)}}" style="display: none;">
                                                @csrf
                                                {{method_field('POST')}} <!-- delete query -->
                                            </form>
                                            <a href="" class="shadow btn-xs sharp" onclick="
                                                        if (confirm('Are you sure, You want to delete?')) 
                                                        {
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{ $notification->slug }}').submit();
                                                        }else {
                                                            event.preventDefault();
                                                        }
                                                        " title="delete">
                                                <i class="fa fa-trash" style="color:#d74b4b;"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $notifications->withQueryString()->links('pagination::bootstrap-4') }}
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
<div class="modal fade" id="userBaseModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hubModalLabel">Add Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUserBase" autocomplete="off">
                    @csrf
                    <div class="mb-2">
                        <label for="address1" class="col-form-label">User Base Name <span class="spanColor user_base_error"></span></label>
                        <input type="text" name="user_base" class="form-control" id="user_base">
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class="text-success d-block" id="message" style="margin-right: 10px"></span>
                <button type="button" id="submitUserBase" class="btn btn-success waves-effect waves-light">Add User Base
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        // Model data
        $('.userBaseModelForm').click(function() {
            $('#userBaseModelForm').modal('show');
        });
        $('#submitUserBase').click(function(e) {
            e.preventDefault();
            var user_base = $('#user_base').val();
            if (user_base == "") {
                $(".user_base_error").html('This field is required!');
                $("input#user_base").focus();
                return false;
            }
            $('#submitUserBase').prop('disabled', true);
            $('#submitUserBase').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUserBase'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-user-base') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitUserBase').prop('disabled', false);
                    $('#submitUserBase').html('Update');
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
                url: "{{route('notification-status-changed')}}",
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