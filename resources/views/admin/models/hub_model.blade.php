<div class="modal fade" id="hubModelForm" role="dialog" aria-labelledby="modalLabel" data-keyboard="false"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modelWidth">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hubModalLabel">Add Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="addUpdateHub" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" class="form-control" name="slug" id="hub_slug">
                            <input type="hidden" class="form-control" name="latitude" id="latitude">
                            <input type="hidden" class="form-control" name="longitude" id="longitude">
                            <div class="form-group mb-2">
                                <label for="hubid" class="col-form-label">Hub Id <sup class="compulsayField">*</sup>
                                    <span class="spanColor hubid_error"></span></label>
                                <input type="text" name="hubId" class="form-control" id="hubId"
                                    value="{{ $hubId }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="hublimit" class="col-form-label">HUB Capacity &nbsp;<span
                                        class="spanColor onlyDigit_error" id="hub_errors"></span></label>
                                <input type="text" name="hub_limit" class="form-control onlyDigit" id="hub_limit">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="address serach" class="col-form-label">Search Address</label>
                                <input id="autocomplete" name="full_address" placeholder="Enter your address"
                                    type="text" class="floating-input form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="address1" class="col-form-label">Address Line 1</label>
                                <input type="text" name="address1" class="form-control" id="address1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="address2" class="col-form-label">Address Line 2</label>
                                <input type="text" name="address2" class="form-control" id="address2">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="city" class="col-form-label">City</label>
                                <input type="text" name="city" class="form-control" id="city">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="state" class="col-form-label">State </label>
                                <input type="text" name="state" class="form-control" id="state">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="country" class="col-form-label">Country</label>
                                <input type="text" name="country" class="form-control" id="country">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="pincode" class="col-form-label">Pin Code</label>
                                <input type="text" name="zip_code" class="form-control" id="zip_code">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <span class=" text-success d-block" id="hubeditmessage"></span>
                <button type="button" id="submitHub" class="btn btn-success waves-effect waves-light">Add
                </button>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.hubModelForm').click(function() {
            $('#hubModelForm').modal('show');
            var hub_slug = $(this).data('slug');
            if (hub_slug) {
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
                var latitude = $(this).data('latitude');
                var longitude = $(this).data('longitude');

                $("#hub_slug").val(hub_slug);
                $("#hubId").val(hubId);
                $("#city").val(city);
                $("#state").val(state);
                $("#country").val(country);
                $("#address1").val(address1);
                $("#address2").val(address2);
                $("#zip_code").val(zip_code);
                $("#hub_limit").val(hub_limit);
                $("#autocomplete").val(full_address);
                $("#latitude").val(latitude);
                $("#longitude").val(longitude);
                $('#submitHub').html('Update');
                $('#hubModalLabel').html('Edit User');
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
                    $('#hubeditmessage').html("<span class='sussecmsg'>" + data.message +
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
</script>
<!-- // Place search -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLR_PLACE_KEY') }}&libraries=places&language=en&callback=initialize"
    type="text/javascript"></script>

<script>
    $(document).ready(function() {
        window.addEventListener('load', initialize);
    });

    function initialize() {
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            console.log(place.geometry.location.lat());
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
                    let slg = $("#hub_slug").val();
                    if (!slg) {
                        $('#hubId').val(hubId);
                    }
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
            $('#latitude').val(place.geometry.location.lat());
            $('#longitude').val(place.geometry.location.lng());
        });
    }
</script>
