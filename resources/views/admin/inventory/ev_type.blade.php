@extends('admin.layouts.app')
@section('title', 'EV Types')
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
                        <li><a href="{{ route('products', 'corporate') }}" class="" title="Products">Products</a></li>
                        {{-- @can('view_inventry', $permission)
                    <li><a href="" class="active" title="Products">Products</a></li>
                    @endcan --}}
                        <li><a href="{{ route('product-ev-types') }}" class="active" title="Ev Types">Ev Types</a></li>
                        @if (Auth::user()->role_id == 0)
                            <li><a href="{{ route('accessories') }}" class="" title="Accessories">Accessories</a>
                        @endif
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom bg-white">
                        <h4>EV Types List</h4>
                        <div class="btn-card-header">
                            <a class="btn btn-success waves-effect waves-light typeModelForm" data-toggle="modal"
                                title="Add Hub">Add New EV Type</a>
                        </div>
                    </div>
                    <div class="table-rep-plugin">
                        @if (count($ev_types) > 0)
                            <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                                <div class="sticky-table-header">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Ev Type</th>
                                                <th>Ev Category</th>
                                                <th>Rent/Day</th>
                                                <th>Range</th>
                                                <th>Speed</th>
                                                <th>Monthly Range</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ev_types as $key => $type)
                                                <tr>
                                                    <td>{{ $type->ev_type_name }}</td>
                                                    <td>{{ $type->ev_category == 1 ? 'Two Wheeler' : ($type->ev_category == 2 ? 'Three Wheeler' : '') }}
                                                    </td>
                                                    <td>Rs{{ $type->rs_perday }}</td>
                                                    <td>Up to {{ $type->range }} km</td>
                                                    <td>{{ $type->speed }} km/h</td>
                                                    <td>{{ $type->total_range }} km</td>
                                                    <td>
                                                        <a class="typeModelForm" data-toggle="modal"
                                                            data-type="{{ $type->ev_type_name }}"
                                                            data-slug="{{ $type->slug }}"
                                                            data-rs_perday="{{ $type->rs_perday }}"
                                                            data-range="{{ $type->range }}"
                                                            data-speed="{{ $type->speed }}"
                                                            data-ev_category="{{ $type->ev_category }}"
                                                            data-total_range="{{ $type->total_range }}"
                                                            title="Edit Ev Type"
                                                            style="cursor: pointer;margin-right: 5px;"><i
                                                                class="fa fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
    <!-- Add ev type model -->
    <div class="modal fade" id="typeModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="typeModalLabel">Add EV Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="addUpdateEvType" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <label for="ev-category" class="col-form-label">Select EV Category </label>
                                <div class="form-group mb-2">
                                    <select class="form-control selectBasic" name="ev_category" id="ev_category">
                                        @foreach ($ev_categories as $key => $ev_category)
                                            <option value="{{ $ev_category }}">
                                                {{ $ev_category == 1 ? 'Two Wheeler' : 'Three Wheeler' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" name="slug" id="evslug">
                                <div class="form-group mb-2">
                                    <label for="ev_type_name" class="col-form-label">EV Type Name <sup
                                            class="compulsayField">*</sup> <span
                                            class="spanColor ev_type_name_error"></span></label>
                                    <input type="text" name="ev_type_name" class="form-control" id="ev_type_name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="example-title-input" class="form-label">Rent per Day(Rs) &nbsp; <span
                                            class="spanColor onlyDigitRent_error rent_error" id="rent_error">
                                        </span></label>
                                    <input class="form-control onlyDigitRent" type="text" name="rs_perday" id="rs_perday"
                                        value="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="range" class="col-form-label ">Single charge Run Time* (km) &nbsp;<span
                                            class="spanColor onlyDigit_error range_error" id="range_error"></span></label>
                                    <input type="text" name="range" class="form-control onlyDigit" id="range">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="speed" class="col-form-label">Speed* (km/h) &nbsp;<span
                                            class="spanColor onlyDigitSpeed_error speed_error"
                                            id="speed_error"></span></label>
                                    <input type="text" name="speed" class="form-control onlyDigitSpeed"
                                        id="speed">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="range" class="form-label ">Monthly Range (km) &nbsp;<span
                                            class="spanColor onlyDigitMonthly_error monthly_range_error"
                                            id="monthly_range_error"></span></label>
                                    <input class="form-control onlyDigitMonthly" type="text" name="total_range"
                                        id="total_range" value="2500">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <span class=" text-success d-block" id="messageEvType" style="margin-right: 10px"></span>
                    <button type="button" id="submitEvType" class="btn btn-success waves-effect waves-light">Add
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
            //Ev Types
            $('.typeModelForm').click(function() {
                $('#typeModelForm').modal('show');
                var slug = $(this).data('slug');
                if (slug) {
                    var type = $(this).data('type');
                    var speed = $(this).data('speed');
                    var slug = $(this).data('slug');
                    var range = $(this).data('range');
                    var ev_category = $(this).data('ev_category');
                    var rs_perday = $(this).data('rs_perday');
                    var total_range = $(this).data('total_range');

                    $("#evslug").val(slug);
                    $("#ev_type_name").val(type);
                    $("#rs_perday").val(rs_perday);
                    $("#speed").val(speed);
                    $("#range").val(range);
                    $("#total_range").val(total_range);
                    $('#ev_category').val(ev_category).trigger('change');
                }

                if (slug) {
                    $('#submitEvType').html('Update')
                    $('#typeModalLabel').html('Edit EV Type')
                }
            });
            $('#submitEvType').click(function(e) {
                e.preventDefault();
                var ev_type_name = $('#ev_type_name').val();
                if (ev_type_name == "") {
                    $(".ev_type_name_error").html('This field is required!');
                    $("input#ev_type_name").focus();
                    return false;
                }
                var range = $('#range').val();
                if (range == "") {
                    $(".range_error").css("display", "");
                    $(".range_error").html('This field is required!');
                    $(".ev_type_name_error").html('');
                    $("input#range").focus();
                    return false;
                }
                var speed = $('#speed').val();
                if (speed == "") {
                    $(".speed_error").css("display", "");
                    $(".speed_error").html('This field is required!');
                    $(".ev_type_name_error").html('');
                    $(".range_error").html('');
                    $("input#speed").focus();
                    return false;
                }
                $('#submitEvType').prop('disabled', true);
                $('#submitEvType').html('Please wait...')
                var formDatas = new FormData(document.getElementById('addUpdateEvType'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('add-update-ev-type') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#messageEvType').html("<span class='sussecmsg'>" + data.message +
                            "</span>");
                        $('#submitEvType').prop('disabled', false);
                        $('#submitEvType').html('Success!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);

                    },
                    errors: function() {
                        $('#messageEvType').html(
                            "<span class='sussecmsg'>Somthing went wrong!</span>");
                    }
                });
            });
        });
    </script>
@endsection
