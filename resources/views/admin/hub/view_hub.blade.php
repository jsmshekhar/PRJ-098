@extends('admin.layouts.app')
@section('title', 'Distributed Hub View')
@section('css')
<style>

</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Hub Overview : @if($hub->status_id == 1)<span class="text-success">Active</span> @else <span class="text-success">Inactive</span> @endif</h4>
                    <div class="page-title-right">
                        @can('edit_hub', $permission)
                        <a class="btn btn-info btn-sm hubModelForm" data-toggle="modal" data-hub_id="{{ $hub->hub_Id }}" data-hubid="{{ $hub->hubId }}" data-city="{{ $hub->city }}" data-state="{{ $hub->state }}" data-country="{{ $hub->country }}" data-slug="{{ $hub->slug }}" data-address1="{{ $hub->address_1 }}" data-address2="{{ $hub->address_2 }}" data-zipcode="{{ $hub->zip_code }}" data-hublimit="{{ $hub->hub_limit }}" title="Edit Hub" style="cursor: pointer;margin-right: 5px;">Edit Hub</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <span><b>Hub Id:</b> {{$hub->hubId}}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span><b>Hub Limit:</b> {{$hub->hub_limit}}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span><b>Vehicle Count in Hub:</b> {{$hub->city}}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span><b>City:</b> {{$hub->city}}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span><b>State:</b> {{$hub->state}}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span><b>Country:</b> {{$hub->country}}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span><b>Address:</b> {{$hub->address_1}}{{$hub->address_2 ? ', '.$hub->address_2: ''}}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{--<div class="card">
                <div class="card-header">
                    <h4 class="card-title">Hub List</h4>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-wrapper">
                            @if(count($hubs) >0)
                            <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                                <div class="sticky-table-header">
                                    <table class="table table-striped ">
                                        <thead>
                                            <tr>
                                                <th>Hub Id</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Country</th>
                                                <th>Hub Location</th>
                                                <th>Hub Capacity</th>
                                                <th>Vehicles</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($hubs as $key => $hub)
                                            <tr>
                                                <td>{{$hub->hubId}}</td>
                                                <td>{{$hub->city}}</td>
                                                <td>{{$hub->state}}</td>
                                                <td>{{$hub->country}}</td>
                                                <td>{{$hub->address_1}}{{$hub->address_2 ? ', ' . $hub->address_2 : ''}}</td>
                                                <td>{{$hub->hub_limit}}</td>
                                                <td>###</td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <input type="checkbox" id="switch3{{$key}}" onclick="toggleStatus('switch3{{$key}}')" switch="bool" {{ $hub->status_id == 1 ? 'checked' : '' }} value="{{$hub->slug}}">
                                                        <label for="switch3{{$key}}" data-on-label="Active" data-off-label="Inactive"></label>
                                                    </div>

                                                </td>
                                                <td>
                                                    @can('edit_hub', $permission)
                                                    <a class="hubModelForm" data-toggle="modal" data-hub_id="{{ $hub->hub_Id }}" data-hubid="{{ $hub->hubId }}" data-city="{{ $hub->city }}" data-state="{{ $hub->state }}" data-country="{{ $hub->country }}" data-slug="{{ $hub->slug }}" data-address1="{{ $hub->address_1 }}" data-address2="{{ $hub->address_2 }}" data-zipcode="{{ $hub->zip_code }}" data-hublimit="{{ $hub->hub_limit }}" title="Edit Hub" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
                                                    </a> @endcan @can('delete_hub', $permission) | <form id="delete-form-{{ $hub->slug }}" method="post" action="{{route('hub-delete',$hub->slug)}}" style="display: none;">
                                                        @csrf
                                                        {{method_field('POST')}} <!-- delete query -->
                                                    </form>
                                                    <a href="" class="shadow btn-xs sharp" onclick="
                                                                                            if (confirm('Are you sure, You want to delete?')) 
                                                                                            {
                                                                                                event.preventDefault();
                                                                                                document.getElementById('delete-form-{{ $hub->slug }}').submit();
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
                                {{ $hubs->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>  
                            @else
                            <p>No reords found</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}
        <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- Add role model -->
<div class="modal fade" id="hubModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hubModalLabel">Add Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateHub">
                    @csrf
                    <input type="hidden" class="form-control" name="slug" id="slug">
                    <div class="mb-2">
                        <label for="hubid" class="col-form-label">Hub Id <sup class="compulsayField">*</sup> <span class="spanColor hubid_error"></span></label>
                        <input type="text" name="hubId" class="form-control" id="hubId" value="{{$hub->hubId}}" readonly>
                    </div>
                    <div class="mb-2">
                        <label for="address1" class="col-form-label">Search Address</label>
                        <input id="autocomplete" name="street_address" placeholder="Enter your address" type="text" class="floating-input form-control">
                    </div>
                    <div class="mb-2">
                        <label for="address1" class="col-form-label">Address Line 1</label>
                        <input type="text" name="address1" class="form-control" id="address1">
                    </div>
                    <div class="mb-2">
                        <label for="address2" class="col-form-label">Address Line 2</label>
                        <input type="text" name="address2" class="form-control" id="address2">
                    </div>
                    <div class="mb-2">
                        <label for="city" class="col-form-label">City</label>
                        <input type="text" name="city" class="form-control" id="city">
                    </div>
                    <div class="mb-2">
                        <label for="state" class="col-form-label">State </label>
                        <input type="text" name="state" class="form-control" id="state">
                    </div>
                    <div class="mb-2">
                        <label for="country" class="col-form-label">Country</label>
                        <input type="text" name="country" class="form-control" id="country">
                    </div>
                    <div class="mb-2">
                        <label for="pincode" class="col-form-label">Pin Code</label>
                        <input type="text" name="zip_code" class="form-control" id="zip_code">
                    </div>
                    <div class="mb-2">
                        <label for="hublimit" class="col-form-label">Hub Limit</label>
                        <input type="text" name="hub_limit" class="form-control" id="hub_limit">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span class="text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitHub" class="btn btn-primary">Add
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
        $('.hubModelForm').click(function() {
            $('#hubModelForm').modal('show');
            var slug = $(this).data('slug');
            if (slug) {
                var hub_id = $(this).data('hub_Id');
                var hubId = $(this).data('hubid');
                var city = $(this).data('city');
                var state = $(this).data('state');
                var country = $(this).data('country');
                var address1 = $(this).data('address1');
                var address2 = $(this).data('address2');
                var zip_code = $(this).data('zipcode');
                var hub_limit = $(this).data('hublimit');

                $("#slug").val(slug);
                $("#hubId").val(hubId);
                $("#city").val(city);
                $("#state").val(state);
                $("#country").val(country);
                $("#address1").val(address1);
                $("#address2").val(address2);
                $("#zip_code").val(zip_code);
                $("#hub_limit").val(hub_limit);
            }

            if (slug) {
                $('#submitHub').html('Update')
            }
            if (slug) {
                $('#hubModalLabel').html('Edit User')
            }
        });
        $('#submitHub').click(function(e) {
            e.preventDefault();
            var name = $('#hubId').val();
            if (name == "") {
                $(".hubid_error").html('This field is required!');
                $("input#hubId").focus();
                return false;
            }
            $('#submitHub').prop('disabled', true);
            $('#submitHub').html('Please wait...')
            var formDatas = new FormData(document.getElementById('addUpdateHub'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('add-update-hub') }}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#message').html("<span class='sussecmsg'>" + data.message + "</span>");
                    $('#submitHub').prop('disabled', false);
                    $('#submitHub').html('Update');
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
</script>
@endsection