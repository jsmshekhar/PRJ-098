@extends('admin.layouts.app')
@section('title', 'Live Tracking')
@section('css')
<style>

</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body ">
                    <p class="text-success d-block" id="mobiImmobiMap"></p>
                    <div class="detail_cnt live_track">

                        <div class="position-relative">
                            <img src="http://localhost/PRJ-098/public/assets/images/icons/Search.svg" alt="" class="search_inpot">
                            <input type="text" class="form-control w-100 searchInput" placeholder="Search Rider">
                        </div>
                        @if(count($riders) > 0)
                        <ul id="searchResults">
                            @foreach ($riders as $key => $value)
                            <li>
                                <div class="form-check my-2">
                                    <input class="form-check-input ChassisNumber" type="radio" name="chassis_no" id="chassis_no_{{$key}}" onclick="updateMap('{{ $value->chassis_number }}')" value="{{ $value->chassis_number }}">
                                    <label for="chassis_no_{{$key}}" class="form-check-label pt-1 px-2 w-100">
                                        {{$value->name}} <span class="text-success m-0 float-end">Active</span>
                                    </label>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-md-8">
            <div class="card">
                <div class="card-body personal-info-h detail_cnt" id="map">
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->

    </div> <!-- end row -->

</div>
@endsection
@section('js')
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAY0EeD3Qdxs10gIg1RgOZP04tGcAbwdDM&language=en&callback=initMap&libraries=journeySharing" type="text/javascript"></script>
<script type="text/javascript">
    var marker; // Declare marker globally to update its position later
    function updateMap(value) {
        var chassisNumber = value;
        console.log(chassisNumber);
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            type: 'GET',
            url: '{{ route("get_gps_ev_details") }}',
            data: {
                chassis_number: chassisNumber
            },
            success: function(data) {
                console.log('data');
                console.log(data);
                // Update marker position
                if (marker) {
                    marker.setPosition(new google.maps.LatLng(parseInt(data.lat), parseInt(data.long)));
                } else {
                    var cen = {
                        lat: parseInt(data.lat),
                        lng: parseInt(data.long)
                    };
                    initMap(data, cen);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                setTimeout(function() {
                    updateMap(chassisNumber);
                }, 2000);
            },
            complete: function() {
                setTimeout(function() {
                    updateMap(chassisNumber);
                }, 2000);
            }
        });
    }
    //onlaad run
    $(document).ready(function() {
        var mapOptions = {
            zoom: 15,
            center: new google.maps.LatLng(28.6068, 77.3597),
            mapTypeId: 'roadmap'
        };
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var markerPosition = {
            lat: 28.6068,
            lng: 77.3597
        };
        var twoWheelerIcon = {
            url: "{{asset('public/assets/images/marker.png')}}",
            scaledSize: new google.maps.Size(32, 32),
        };
        var marker = new google.maps.Marker({
            position: markerPosition,
            map: map,
            icon: twoWheelerIcon,
        });
    });

    function initMap(data, cen) {
        var center = cen;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: center
        });

        var twoWheelerIcon = {
            url: "{{asset('public/assets/images/marker.png')}}",
            scaledSize: new google.maps.Size(32, 32),
        };
        var infowindow = new google.maps.InfoWindow({});
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(parseInt(data.lat), parseInt(data.long)),
            map: map,
            title: data.name,
            userid: data.customer_id,
            ev: data.ev_number,
            phone: data.phone,
            tdate: data.to_date,
            fdate: data.from_date,
            icon: twoWheelerIcon,
        });

        google.maps.event.addListener(marker, 'click', (function(marker) {
            return function() {
                var content = '<div class="infoWindow"><strong>Name:</strong> ' + data.name + '<br>' +
                    '<strong>Phone:</strong> ' + data.phone + '<br>' +
                    '<strong>User ID:</strong> ' + data.customer_id + '<br>' +
                    '<strong>EV Number:</strong> ' + data.ev_number + '<br>' +
                    '<strong>From Date:</strong> ' + data.from_date + '<br>' +
                    '<strong>To Date:</strong> ' + data.to_date + '<br>' +
                    `<button class="btn btn-success mobilizedEvMap" onclick="mobilizedEv('${data.product_id}', '${data.gps_emei_number}', '${data.rider_id}', 'm')">Mobilized</button> &nbsp;
                    <button class="btn btn-danger imMobilizedEvMap" onclick="imMobilizedEv('${data.product_id}', '${data.gps_emei_number}','${data.rider_id}', 'im')">Immobilized</button>` +
                    '</div>';

                infowindow.setContent(content);
                infowindow.open(map, marker);
            };
        })(marker));
    }
    //updateMap(initialChassisNumber);
</script>
<script>
    $(document).ready(function() {
        $('.searchInput').on('input', function() {
            var query = $(this).val();
            if (query.length >= 2) {
                $.ajax({
                    url: '{{ route("rider-auto-search") }}',
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        displayResults(response);
                    }
                });
            } else {
                $('#searchResults').empty();
            }
        });

        function displayResults(users) {
            var resultContainer = $('#searchResults');
            resultContainer.empty();

            if (users.length > 0) {
                $.each(users, function(index, value) {
                    resultContainer.append('<li><div class="form-check my-2"><input class="form-check-input ChassisNumber" type="radio" name="chassis_no" id="chassis_no_' + index + '" onclick="updateMap(' + value.chassis_number + ')" value="' + value.chassis_number + '"><label for="chassis_no_' + index + '" class="form-check-label pt-1 px-2 w-100">' + value.name + '<span class="text-success m-0 float-end">Active</span></label></div></li>');
                });
            } else {
                resultContainer.append('<li><div class="form-check my-2"><label class="form-check-label pt-1 px-2 w-100">No Result Found</label></div></li>');
            }
        }
    });
</script>
@endsection