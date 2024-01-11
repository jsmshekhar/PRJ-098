@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('css')
<style>

</style>
@endsection
@section('content')
<div class="row card_dash dashborad_sec m-0">

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100 m-0 ">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Total Revenue</span>
                        <h4 class="">
                            $2,50,000
                        </h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100 m-0">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Received Revenue</span>
                        <h4 class="">
                            $2,00,000
                        </h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100 m-0">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Outstanding Revenue</span>
                        <h4 class="">
                            $50,000
                        </h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-h-100 m-0">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Total Expenses</span>
                        <h4 class="">
                            $1,50,000
                        </h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row-->

<div class="row card_dash card_dash_two p-0">

    <div class="col-xl-6 col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-h-100 m-0 ">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <span class="text-muted text-center mb-3 lh-1 d-block text-truncate totally_info">CO2 Emission Saving</span>
                                <h4 class="text-center text-primary">
                                    0.82 KGs
                                </h4>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-h-100 m-0 ">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12 text-center">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Total KMs</span>
                                <h4 class="text-center text-blue-dash">
                                    21.00 KMs
                                </h4>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-xl-12">
                <!-- card -->
                <div class="card-body card mt-4">
                    <div class="col-12">
                        <h3 class="pt-0">Vehicles Classification Data</h3>
                    </div>
                    <!-- card body -->
                    <div class="dash_borad_body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="data_dash_grp">
                                    <div class="dash_grp_head">
                                        <h4>Two Wheelers</h4>                    
                                    </div>
                                    <div class="dash_body_grp">
                                        <div id="donut_chart" data-colors='["#2ab57d", "#5156be", "#fd625e", "#4ba6ef", "#ffbf53"]' class="apex-charts"  dir="ltr"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data_dash_grp">
                                    <div class="dash_grp_head">
                                        <h4>Two Wheelers</h4>                    
                                    </div>
                                    <div class="dash_body_grp">
                                        hello
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div><!-- end card -->
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-6 col-md-6">

        <!-- card -->
        <div class="card-body card m-0-cust">
            <div class="col-12">
                <h3 class="pt-0">Users Classification Data</h3>
            </div>
            <!-- card body -->
            <div>

            </div>
            <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
</div><!-- end row-->


@endsection
@section('js')
<script></script>
@endsection