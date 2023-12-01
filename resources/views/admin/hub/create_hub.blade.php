@extends('admin.layouts.app')
@section('title', 'Distributed Hub')
@section('css')
<style>
    .pac-container {
        /* display: block !important; */
        z-index: 999999;
    }

    span.spanColor {
        color: #e03e3e !important;
    }

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
                <div class="card-body p-0">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Advance Search
                                </button>
                                <div class="collaps_btns">
                                    <button type="button" class="btn btn-outline-danger waves-effect waves-light" onclick="clearSearch('<?= url()->current() ?>');">Clear</button>
                                    <button type="button" onclick="submitSearchForm();" class="btn btn-outline-success waves-effect waves-light">Search</button>
                                    @can('hub_list', $permission)
                                    <a class="btn btn-success waves-effect waves-light hubModelForm" data-toggle="modal" title="Add Hub">Add New Hub</a>
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
                                                    <label class="form-label">Hub Id</label>
                                                    <input type="text" class="form-control" name="hub_id" value="<?= isset($_GET['hub_id']) ? $_GET['hub_id'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">City</label>
                                                    <input type="text" class="form-control" name="city" value="<?= isset($_GET['city']) ? $_GET['city'] : '' ?>" />
                                                </div>
                                            </div>
                                            {{-- <div class="col-xl-3 col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Hub Locarion</label>
                                                        <input type="text" class="form-control" name="hub_location" />
                                                    </div>
                                                </div> --}}
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Hub Capacity</label>
                                                    <input type="text" class="form-control" name="hub_capacity" value="<?= isset($_GET['hub_capacity']) ? $_GET['hub_capacity'] : '' ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Vehicle</label>
                                                    <input type="text" class="form-control" name="vehicle" value="<?= isset($_GET['vehicle']) ? $_GET['vehicle'] : '' ?>" />
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
                                <a href="javascript:void(0);" class="btn btn-link" onclick="refreshPage();">
                                    <img src="{{ asset('public/assets/images/icons/refresh.svg') }}" alt="">
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
                                    <img src="{{ asset('public/assets/images/icons/download.svg') }}" alt="">
                                    Export
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="table-rep-plugin">
                        @if (count($hubs) > 0)
                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <div class="sticky-table-header">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Hub Id</th>
                                            <th>City</th>
                                            <th>State</th>
                                            {{-- <th>Country</th> --}}
                                            <th>Hub Location</th>
                                            <th>Hub Capacity</th>
                                            <th>Vehicles</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hubs as $key => $hub)
                                        <tr>
                                            <td>
                                                @can('hub_view', $permission)
                                                <a href="{{ route('hub-view',['slug' => $hub->slug, 'param' => 'vehicle']) }}" title="View Hub" style="cursor: pointer;margin-right: 5px;" target="_blank">{{ $hub->hubId }}
                                                </a>
                                                @endcan
                                            </td>
                                            <td>{{ $hub->city }}</td>
                                            <td>{{ $hub->state }}</td>
                                            {{-- <td>{{ $hub->country }}</td> --}}
                                            <td>{{ $hub->address_1 }}{{ $hub->address_2 ? ', ' . $hub->address_2 : '' }}
                                            </td>
                                            <td>{{ $hub->hub_limit }}</td>
                                            <td>###</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <input type="checkbox" id="switch3{{ $key }}" onclick="toggleStatus('switch3{{ $key }}')" switch="bool" {{ $hub->status_id == 1 ? 'checked' : '' }} value="{{ $hub->slug }}">
                                                    <label for="switch3{{ $key }}" data-on-label="Active" data-off-label="Inactive"></label>
                                                </div>

                                            </td>
                                            <td>
                                                @can('edit_hub', $permission)
                                                <a class="hubModelForm" data-toggle="modal" data-hub_id="{{ $hub->hub_Id }}" data-hubid="{{ $hub->hubId }}" data-city="{{ $hub->city }}" data-state="{{ $hub->state }}" data-country="{{ $hub->country }}" data-slug="{{ $hub->slug }}" data-address1="{{ $hub->address_1 }}" data-address2="{{ $hub->address_2 }}" data-fulladdress="{{ $hub->full_address }}" data-zipcode="{{ $hub->zip_code }}" data-hublimit="{{ $hub->hub_limit }}" title="Edit Hub" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('hub_view', $permission)
                                                | <a href="{{ route('hub-view',['slug' => $hub->slug, 'param' => 'vehicle']) }}" title="View Hub" style="cursor: pointer;margin-right: 5px;" target="_blank"><i class="fa fa-eye"></i>
                                                </a>
                                                @endcan
                                                @can('delete_hub', $permission)
                                                | <form id="delete-form-{{ $hub->slug }}" method="post" action="{{ route('hub-delete', $hub->slug) }}" style="display: none;">
                                                    @csrf
                                                    {{ method_field('POST') }} <!-- delete query -->
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
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $hubs->withQueryString()->links('pagination::bootstrap-4') }}
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
<div class="modal fade" id="hubModelForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hubModalLabel">Add Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateHub" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" name="slug" id="slug">
                            <div class="form-group mb-2">
                                <label for="hubid" class="col-form-label">Hub Id <sup class="compulsayField">*</sup> <span class="spanColor hubid_error"></span></label>
                                <input type="text" name="hubId" class="form-control" id="hubId" value="{{ $hubId }}" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="address serach" class="col-form-label">Search Address</label>
                                <input id="autocomplete" name="full_address" placeholder="Enter your address" type="text" class="floating-input form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="address1" class="col-form-label">Address Line 1</label>
                                <input type="text" name="address1" class="form-control" id="address1">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label for="address2" class="col-form-label">Address Line 2</label>
                            <input type="text" name="address2" class="form-control" id="address2">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label for="city" class="col-form-label">City</label>
                            <input type="text" name="city" class="form-control" id="city">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label for="state" class="col-form-label">State </label>
                            <input type="text" name="state" class="form-control" id="state">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label for="country" class="col-form-label">Country</label>
                            <input type="text" name="country" class="form-control" id="country">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label for="pincode" class="col-form-label">Pin Code</label>
                            <input type="text" name="zip_code" class="form-control" id="zip_code">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label for="hublimit" class="col-form-label">Hub Limit &nbsp;<span class="spanColor onlyDigit_error" id="hub_errors"></span></label>
                            <input type="text" name="hub_limit" class="form-control onlyDigit" id="hub_limit">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class=" text-success d-block" id="message" style="margin-right: 10px"></span>

                <button type="button" id="submitHub" class="btn btn-success waves-effect waves-light">Add
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
                var full_address = $(this).data('fulladdress');

                $("#slug").val(slug);
                $("#hubId").val(hubId);
                $("#city").val(city);
                $("#state").val(state);
                $("#country").val(country);
                $("#address1").val(address1);
                $("#address2").val(address2);
                $("#zip_code").val(zip_code);
                $("#hub_limit").val(hub_limit);
                $("#autocomplete").val(full_address);
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
                    $('#message').html("<span class='sussecmsg'>" + data.message +
                        "</span>");
                    $('#submitHub').prop('disabled', false);
                    $('#submitHub').html('Update');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);

                },
                errors: function() {
                    $('#message').html(
                        "<span class='sussecmsg'>Somthing went wrong!</span>");
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
                url: "{{ route('hub-status-changed') }}",
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

<!-- // Place search -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLR_PLACE_KEY') }}&libraries=places&language=en&callback=initialize" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        window.addEventListener('load', initialize);
    });

    function initialize() {
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            console.log(place);
            for (var i = 0; i < place.address_components.length; i++) {
                if (place.address_components[i].types[0] == 'sublocality_level_1') {
                    $('#address2').val(place.address_components[i].long_name);
                }
                if (place.address_components[i].types[0] == 'locality') {
                    $('#city').val(place.address_components[i].long_name);
                    let cty = place.address_components[i].long_name;
                    let splitArray = cty.split('', 2);
                    let mergedString = splitArray.join('').toUpperCase();
                    let hId = "<?php echo $hubId; ?>";
                    let hubId = mergedString + hId;
                    $('#hubId').val(hubId);
                }
                if (place.address_components[i].types[0] == 'administrative_area_level_1' || place
                    .address_components[i].types[0] == 'political') {
                    $('#state').val(place.address_components[i].long_name);
                }
                if (place.address_components[i].types[0] == 'country') {
                    $('#country').val(place.address_components[i].long_name);
                }
                if (place.address_components[i].types[0] == 'postal_code') {
                    $('#zip_code').val(place.address_components[i].short_name);
                }
            }
            $('#address1').val(place.name);
        });
    }
</script>
@endsection