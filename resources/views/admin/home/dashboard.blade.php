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
                            ₹{{ $totalRevenue }}
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
                        <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">This Month Revenue</span>
                        <h4 class="">
                            ₹{{ $thisMonthTotal }}
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
                        <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">This Month Recieved</span>
                        <h4 class="">
                            ₹{{ $upcommingPayment ? $upcommingPayment : 0 }}
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
                        <span class="text-muted mb-3 lh-1 d-block text-truncate totally_info">Total Outstanding</span>
                        <h4 class="">
                            ₹{{ $thisMonthTotal - $upcommingPayment }}
                        </h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row-->

<div class="row card_dash card_dash_two p-0">

    <div class="col-xl-6 col-md-6 dash_box_gp">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-h-100 m-0 ">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <span class="text-muted text-center mb-3 lh-1 d-block text-truncate totally_info">CO2
                                    Emission Saving</span>
                                <h4 class="text-center text-primary">
                                    {{ $co2Saving }} KGs
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
                                    {{ $evDistance }} KMs
                                </h4>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
            <div class="col-xl-12 dash_box_gp">
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
                                        <div id="donutChart2W" class="position-relative"></div>
                                        <div class="position-relative">
                                            <ul class="grp_list">
                                                <li><span class="grp_clr_box grp_yellow"></span>Mobilized Vehicles</li>
                                                <li><span class="grp_clr_box grp_red"></span>Immobilized Vehicles</li>
                                                <li><span class="grp_clr_box grp_blue"></span>Functional Vehicles</li>
                                                <li><span class="grp_clr_box grp_green"></span>Non-Functional Vehicles
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data_dash_grp">
                                    <div class="dash_grp_head">
                                        <h4>Three Wheelers <span style="font-size: 11px;;" class="Dummy3Data"></span>
                                        </h4>
                                    </div>
                                    <div class="dash_body_grp">
                                        <div id="donutChart3W" class="position-relative"></div>
                                        <ul class="grp_list">
                                            <li><span class="grp_clr_box grp_green"></span>Mobilized Vehicles</li>
                                            <li><span class="grp_clr_box grp_blue"></span>Immobilized Vehicles</li>
                                            <li><span class="grp_clr_box grp_yellow"></span>Functional Vehicles</li>
                                            <li><span class="grp_clr_box grp_red"></span>Non-Functional Vehicles</li>
                                        </ul>
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

    <div class="col-xl-6 col-md-6 dash_box_gp">

        <!-- card -->
        <div class="card-body card m-0-cust">
            <div class="col-12">
                <h3 class="pt-0">Users Classification Data</h3>
            </div>
            <div class="dash_body_grp">
                <div id="barchart_material"></div>
            </div>
            <div>

            </div>
            <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col-->
</div><!-- end row-->


@endsection
@section('js')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</script>
<script type="text/javascript">
    google.charts.load('current', {
        callback: function() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Two Wheeler'],
                ['Mobilized Vehicle', <?php echo $evstatus2W->mobilized; ?>],
                ['Immobilized Vehicle', <?php echo $evstatus2W->immobilized; ?>],
                ['Functional Vehicle', <?php echo $evstatus2W->functional; ?>],
                ['Non-Functional Vehicle', <?php echo $evstatus2W->non_functional; ?>],
            ]);
            var options = {
                pieHole: 0.5,
                pieSliceText: 'percentage',
                theme: 'maximized',
                colors: ['#F59E0B', '#EC4899', '#14B8A6', '#3B82F6'],
            };


            var pieChart = new google.visualization.PieChart(document.getElementById('donutChart2W'));
            pieChart.draw(data, options);

            // Calculate and display total value inside pie hole
            var totalValue = "<?php echo $evCount2W; ?>";

            var totalLabel = document.createElement('div');
            totalLabel.innerHTML = "Total EVs <br> <b style='text-align:center'>" + totalValue + "<b>";
            totalLabel.style.position = 'absolute';
            totalLabel.style.top = '50%';
            totalLabel.style.left = '50%';
            totalLabel.style.transform = 'translate(-50%, -50%)';
            totalLabel.style.fontSize = '12px';
            totalLabel.style.textAlign = 'center';
            document.getElementById('donutChart2W').appendChild(totalLabel);
        },
        packages: ['corechart']
    });
</script>
<script type="text/javascript">
    google.charts.load('current', {
        callback: function() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Three Wheeler'],
                ['Mobilized Vehicle', <?php echo isset($evstatus3W->mobilized) ? $evstatus3W->mobilized : 0; ?>],
                ['Immobilized Vehicle', <?php echo isset($evstatus3W->immobilized) ? $evstatus3W->immobilized : 0; ?>],
                ['Functional Vehicle', <?php echo isset($evstatus3W->functional) ? $evstatus3W->functional : 0; ?>],
                ['Non-Functional Vehicle', <?php echo isset($evstatus3W->non_functional) ? $evstatus3W->non_functional : 0; ?>],
            ]);

            var data1 = google.visualization.arrayToDataTable([
                ['Task', 'Three Wheeler'],
                ['Mobilized Vehicle', 5],
                ['Immobilized Vehicle', 9],
                ['Functional Vehicle', 11],
                ['Non-Functional Vehicle', 4],
            ]);
            var options = {
                pieHole: 0.5,
                pieSliceText: 'percentage',
                theme: 'maximized',
                colors: ['#3B82F6', '#14B8A6', '#F59E0B', '#EC4899'] // Set colors for each section
            };
            var evCount3W = "<?php echo $evCount3W; ?>";
            if (evCount3W > 0) {
                var datas = data;
            } else {
                $('.Dummy3Data').text('Dummy Data')
                var datas = data1;
            }
            var pieChart = new google.visualization.PieChart(document.getElementById('donutChart3W'));
            pieChart.draw(datas, options);

            // Calculate and display total value inside pie hole
            var totalValue = "<?php echo $evCount3W > 0 ? $evCount3W : 29; ?>"

            var totalLabel = document.createElement('div');
            totalLabel.innerHTML = "Total EVs <br> <b style='text-align:center'>" + totalValue + "<b>";
            totalLabel.style.position = 'absolute';
            totalLabel.style.top = '50%';
            totalLabel.style.left = '50%';
            totalLabel.style.transform = 'translate(-50%, -50%)';
            totalLabel.style.fontSize = '12px';
            totalLabel.style.textAlign = 'center';

            document.getElementById('donutChart3W').appendChild(totalLabel);
        },
        packages: ['corechart']
    });
</script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($ridersArray); ?>);

        var options = {
            legend: {
                position: 'top',
                maxLines: 3
            },
            hAxis: {
                minValue: 0,
                title: 'Value'
            },
            bars: 'verticle',
            hAxis: {
                format: 'decimal'
            },

            height: 510,
            colors: ['#5BF0CC', '#C81997', '#3B82F6', '#F59E0B'],
            axes: {
                y: {
                    0: {
                        side: 'left',
                        label: 'Number of Customers'
                    } // Top x-axis.
                }
            },
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
@endsection