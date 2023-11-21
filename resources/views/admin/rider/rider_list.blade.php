@extends('admin.layouts.app')
@section('title', 'Customer (Rider) Management')
@section('css')
<style>

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
                                                    <label class="form-label">Customer Id</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Mapped EV</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Rider Name</label>
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
                                                    <label class="form-label">Wallet Balance</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Subscription
                                                        Validity</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Joining Date</label>
                                                    <input type="text" required class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Verification
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
                        @if(count($riders) >0)
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table">
                                <thead>
                                    <tr>
                                        <th>Rider Id</th>
                                        <th>Mapped EV</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subscription Validity</th>
                                        <th>Joining Date</th>
                                        <th>Verification Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riders as $key => $rider)
                                    <tr>
                                        <td>{{$rider->rider_id}}
                                            {{--<a href="{{route('customer-view',$rider->slug)}}" title="View rider" style="cursor: pointer;margin-right: 5px;" target="_blank">{{$rider->rider_id}}
                                            </a>--}}
                                        </td>
                                        <td>{{$rider->name}}
                                            {{-- <a href="{{route('customer-view',$rider->slug)}}" title="View rider" style="cursor: pointer;margin-right: 5px;" target="_blank">{{$rider->name}}
                                            </a> --}}
                                        </td>
                                        <td>{{$rider->email}}</td>
                                        <td>{{$rider->phone}}</td>
                                        <td>{{$rider->subscription_validity}}</td>
                                        <td>{{date('d M, Y', strtotime($rider->joining_date))}}</td>
                                        <td>ERF567GB</td>
                                        <td>
                                            @can('enable_disable_customer', $permission)
                                            <div class="d-flex flex-wrap gap-2">
                                                <input type="checkbox" id="switch3{{$key}}" onclick="toggleStatus('switch3{{$key}}')" switch="bool" {{ $rider->status_id == 1 ? 'checked' : '' }} value="{{$rider->slug}}">
                                                <label for="switch3{{$key}}" data-on-label="" data-off-label=""></label>
                                            </div>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $riders->withQueryString()->links('pagination::bootstrap-4') }}
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
@endsection
@section('js')
<script type="text/javascript">
    // Active inactive status toggle
    function toggleStatus(toggleId) {
        var slug = $("#" + toggleId).val();

        var newStatus = $(this).prop("checked");
        var token = "{{ csrf_token() }}";
        if (slug) {
            $.ajax({
                url: "{{route('rider-status-changed')}}",
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