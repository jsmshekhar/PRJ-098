@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="row card_dash">
        <div class="col-12">
            <h3>EV Vehicles</h3>
        </div>
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Total
                                Vehicles</span>
                            <h4 class="mb-3 d-flex justify-content-between align-items-center">
                                150
                                <div>
                                    <h5>100%</h5>
                                </div>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar bg-success" role="progressbar" style="width: 75%"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Functional
                                Vehicles</span>
                            <h4 class="mb-3 d-flex justify-content-between align-items-center">
                                <div> 105 <span>/150</span></div>
                                <div>
                                    <h5>75%</h5>
                                </div>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar bg-blue" role="progressbar" style="width: 75%"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col-->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Immobilized
                                Vehicles</span>
                            <h4 class="mb-3">
                                <h4 class="mb-3 d-flex justify-content-between align-items-center">
                                    <div> 120<span>/150</span></div>
                                    <div>
                                        <h5>85%</h5>
                                    </div>
                                </h4>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar bg-pruple" role="progressbar" style="width: 85%"
                                aria-valuenow="85" aria-valuemin="0" aria-valuemax="85">
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Non-Operational
                                Vehicles</span>
                            <h4 class="mb-3">
                                <h4 class="mb-3 d-flex justify-content-between align-items-center">
                                    <div> 45<span>/150</span></div>
                                    <div>
                                        <h5>30%</h5>
                                    </div>
                                </h4>
                            </h4>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar bg-danger" role="progressbar" style="width: 75%"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row-->
@endsection
@section('js')
    <script></script>
@endsection
