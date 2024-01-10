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

    .modelWidth {
        max-width: 50%;
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
                                    @can('add_hub', $permission)
                                    <a class="btn btn-success waves-effect waves-light hubModelForm" data-toggle="modal" title="Add Hub">Add New Hub</a>
                                    @endcan
                                </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="searchForm" method="get" action="<?= url()->current() ?>">
                                        <input type="hidden" name="is_search" id="isSearchHidden" value="0" />
                                        <input type="hidden" name="per_page" id="perPageHidden" />
                                        <input type="hidden" name="is_export" id="isExportHidden" />
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
                                                        <label class="form-label">Hub Location</label>
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
                                <p>Total Record : <span> {{ count($hubs) }}</span></p>
                            </li>
                            <li>
                                <p>Display up to :
                                <div class="form-group">
                                    @include('admin.layouts.per_page')
                                </div>
                                </p>
                            </li>
                            @if (count($hubs) > 0)
                            <li>
                                <button type="button" class="btn btn-success waves-effect waves-light" onclick="exportData('<?= config('table.REF_TABLE.RIDER') ?>');">
                                    <img src="{{ asset('public/assets/images/icons/download.svg') }}" alt="">
                                    Export
                                </button>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="table-rep-plugin">
                        @if (count($hubs) > 0)
                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="tech-companies-1" class="table">
                                    <thead>
                                        <tr>
                                            <th>Hub Id</th>
                                            <th>City</th>
                                            <th>Hub Location</th>
                                            <th>Hub Capacity</th>
                                            <th>Vehicles</th>
                                            @can('delete_hub', $permission)
                                            <th>Status</th>
                                            @endcan
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hubs as $key => $hub)
                                        <tr>
                                            <td>
                                                @can('hub_view', $permission)
                                                <a href="{{ route('hub-view', ['slug' => $hub->slug, 'param' => 'vehicle']) }}" title="View Hub" style="cursor: pointer;margin-right: 5px;" target="_blank">{{ $hub->hubId }}
                                                </a>
                                                @endcan
                                            </td>
                                            <td>{{ $hub->city }}</td>
                                            <td>{{ $hub->address_1 }}{{ $hub->address_2 ? ', ' . $hub->address_2 : '' }}
                                            </td>
                                            <td>{{ $hub->hub_limit }}</td>
                                            <td>{{ $hub->vehicle_count }}</td>
                                            @can('delete_hub', $permission)
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <input type="checkbox" id="switch3{{ $key }}" onclick="toggleStatus('switch3{{ $key }}')" switch="bool" {{ $hub->status_id == 1 ? 'checked' : '' }} value="{{ $hub->slug }}">
                                                    <label for="switch3{{ $key }}" data-on-label="Active" data-off-label="Inactive"></label>
                                                </div>
                                            </td>
                                            @endcan
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="btn btn-link p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu">
                                                        @can('edit_hub', $permission)
                                                        <a class="dropdown-item hubModelForm" data-toggle="modal" data-hub_id="{{ $hub->hub_Id }}" data-hubid="{{ $hub->hubId }}" data-city="{{ $hub->city }}" data-state="{{ $hub->state }}" data-country="{{ $hub->country }}" data-slug="{{ $hub->slug }}" data-address1="{{ $hub->address_1 }}" data-address2="{{ $hub->address_2 }}" data-fulladdress="{{ $hub->full_address }}" data-zipcode="{{ $hub->zip_code }}" data-hublimit="{{ $hub->hub_limit }}" data-latitude="{{ $hub->latitude }}" data-longitude="{{ $hub->longitude }}" title="Edit Hub" style="cursor: pointer;margin-right: 5px;"><i class="fa fa-edit"> Edit</i>
                                                        </a>
                                                        @endcan
                                                        @can('hub_view', $permission)
                                                        <a class="dropdown-item" href="{{ route('hub-view', ['slug' => $hub->slug, 'param' => 'vehicle']) }}" title="View Hub" style="cursor: pointer;margin-right: 5px;" target="_blank"><i class="fa fa-eye"> View</i>
                                                        </a>
                                                        @endcan
                                                        @can('delete_hub', $permission)
                                                        <form id="delete-form-{{ $hub->slug }}" method="post" action="{{ route('hub-delete', $hub->slug) }}" style="display: none;">
                                                            @csrf
                                                            {{ method_field('POST') }} <!-- delete query -->
                                                        </form>
                                                        <a href="" class="dropdown-item" onclick="
                                                        if (confirm('Are you sure, You want to delete?'))
                                                        {
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{ $hub->slug }}').submit();
                                                        }else {
                                                            event.preventDefault();
                                                        }
                                                        " title="delete">
                                                            <i class="fa fa-trash" style="color:#d74b4b;"> Delete</i>
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

@endsection
@section('js')
<!-- Add role model -->
@include('admin.models.hub_model')
<script type="text/javascript">

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
@endsection