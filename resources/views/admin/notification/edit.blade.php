@extends('admin.layouts.app')
@section('title', 'Notification Management - Edit')
@section('css')
<style>
    #description {
        height: 140px;
    }

    .distanceHideShow {
        display: none;
    }

    .dayHideShow {
        display: none;
    }

    .scheduleDateShowHide {
        display: none;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom bg-white">
                    <h4>Edit Notification</h4>
                    <div class="btn-card-header">
                        <a href="!" class="btn btn-success waves-effect waves-light categoryModelForm invisible">1</a>
                    </div>
                </div>
                <div class="card-body border-0">
                    <div class="table-rep-plugin">

                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <form method="post" enctype="multipart/form-data" id="createNotificationForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="basicpill-address-input" class="form-label">Notification Title &nbsp; <span class="spanColor title_error"></span></label>
                                            <input id="title" name="title" class="form-control" value="{{$notification->title}}" placeholder="notification title">
                                        </div>
                                        <div class="mb-3">
                                            <label for="basicpill-address-input" class="form-label">Notification Message &nbsp; <span class="spanColor description_error"></span></label>
                                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Type notification message here.">{{$notification->description}}</textarea>
                                        </div>

                                        @if(request()->route('param')=="automatic")
                                        <div class="mb-3">
                                            <label for="notification-parameter" class="col-form-label">Notification Parameter</label>
                                            <select class="form-control selectBasic" name="notification_parameter" id="notification_parameter">
                                                @foreach($parameters as $key => $parameter)
                                                @if($parameter == 1 || $parameter == 2)
                                                <option value="{{$parameter}}" @if($parameter==$notification->notification_parameter) selected @endif>{{str_replace("_", " ", $key) }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 {{$notification->distance_remaining == null ? 'distanceHideShow' : ''}}" id="distanceHideShow">
                                            <label for="role-name" class="col-form-label">Disatance Remaining to Notify</label>
                                            <select class="form-control select2" name="distance_remaining" id="distance_remaining">
                                                <option value="">Select Distance</option>
                                                @foreach($distance as $key => $dist)
                                                <option value="{{$dist}}" @if($dist==$notification->distance_remaining) selected @endif>{{str_replace("_", " ", $key) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 {{$notification->days_remaining == null ? 'dayHideShow' : ''}}" id="dayHideShow">
                                            <label for="role-name" class="col-form-label">Days Remaining to Notify</label>
                                            <select class="form-control select2" name="days_remaining" id="days_remaining">
                                                <option value="">Select Days</option>
                                                @foreach($days as $key => $day)
                                                <option value="{{$day}}" @if($day==$notification->days_remaining) selected @endif>{{str_replace("_", " ", $key) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="example-url-input" class="form-label">Penalty Charges</label>
                                            <input class="form-control" id="penalty_charge_text" type="text" name="penalty_charge_text" value="{{$notification->penalty_charge_text}}">
                                            <input class="form-control" id="penalty_charge_value" type="hidden" name="penalty_charge" value="{{$notification->penalty_charge}}">
                                        </div>
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" name="is_send_charge" id="is_send_charge" @if($notification->is_send_charge == 1) checked @endif>
                                            <label class="form-check-label pt-1 px-2" for="formCheck1">
                                                Display penalty charges on notification panel with message
                                            </label>
                                        </div>
                                        @elseif(request()->route('param')=="manual")
                                        <div class="mb-3">
                                            <label for="role-name" class="col-form-label">Notification User Base</label>
                                            <select class="form-control selectBasic" name="notification_user_based" id="notification_user_based">
                                                @foreach($user_base as $key => $uBased)
                                                <option value="{{$uBased}}" @if($uBased==$notification->notification_user_based) selected @endif>{{$uBased == 1 ? "Newly Onboarded" : ($uBased == 2 ? "Mobilized" : ($uBased == 3 ? "Immmobilized" : ($uBased == 4 ? "EV Return Request" : ($uBased == 5 ? "EV Service Request" :  ($uBased == 6 ? "Due Payment" : "All")))))}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role-name" class="col-form-label">Notification Parameter</label>
                                            <select class="form-control selectBasic" name="notification_parameter" id="notification_parameter">
                                                @foreach($parameters as $key => $parameter)
                                                @if($parameter == 3 || $parameter == 4)
                                                <option value="{{$parameter}}" @if($parameter==$notification->notification_parameter) selected @endif>{{str_replace("_", " ", $key) }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 {{$notification->schedule_date == null ? 'scheduleDateShowHide' : ''}}" id="scheduleDateShowHide">
                                            <label for="example-date-input" class="form-label">Schedule Date</label>
                                            <input class="form-control" type="date" name="schedule_date" id="schedule_date" value="{{date('Y-m-d', strtotime($notification->schedule_date == null ? date('Y-m-d') : $notification->schedule_date))}}">
                                        </div>
                                        @endif
                                        <div class="mb-4">
                                            <label for="NotificatostatusId" class="form-label">Status</label>
                                            <select class="form-control selectBasic" name="status_id" id="NotificatostatusId">
                                                <option value="1" @if($notification->status_id==1) selected @endif>Active</option>
                                                <option value="2" @if($notification->status_id==2) selected @endif>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="mt-4">
                                            <button type="button" class="btn btn-success " id="submitForm">{{ request()->route('param')=="automatic" ? 'Update Notification' : 'Update Notification'}}</button>
                                            <span class="text-success d-block" id="message" style="margin-right: 10px"></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('#submitForm').click(function(e) {
            e.preventDefault();
            var name = $('#description').val();
            if (name == "") {
                $(".description_error").html('This field is required!');
                $("textarea#description").focus();
                return false;
            }
            $('#submitForm').prop('disabled', true);
            $('#submitForm').html('Please wait...')
            var formDatas = new FormData(document.getElementById('createNotificationForm'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('update-notification', $notification->slug) }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitForm').prop('disabled', false);
                    $('#submitForm').html('Update Notification')
                    setTimeout(function() {
                        window.location = data.url;
                    }, 1000);

                },
                errors: function() {
                    $('#message').html("<span class='sussecmsg'>Somthing went wrong!</span>");
                }
            });
        });

        // based notification type set div
        $('#notification_parameter').change(function() {
            var npValue = $(this).val();
            if (npValue == 1) {
                $("#distanceHideShow").addClass('distanceHideShow');
                $("#dayHideShow").removeClass('dayHideShow');
                $('#penalty_charge_value').val(50);
                $('#penalty_charge_text').val('Base Price = 50Rs/per km')
            } else if (npValue == 2) {
                $("#dayHideShow").addClass('dayHideShow');
                $("#distanceHideShow").removeClass('distanceHideShow');
                $('#penalty_charge_value').val(2);
                $('#penalty_charge_text').val('Base Price = 2Rs/per km')
            } else if (npValue == 3) {
                $("#scheduleDateShowHide").removeClass('scheduleDateShowHide');
            } else if (npValue == 4) {
                $("#scheduleDateShowHide").addClass('scheduleDateShowHide');
            }
        });

        // Set the minimum date of the input
        var today = new Date().toISOString().split('T')[0];
        $('#schedule_date').attr('min', today);
    });
</script>
@endsection