@extends('admin.layout.master')

@section('title','Admin :: Add Device')

@push('links')

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

<style type="text/css">

    #results { border:1px solid; background:#ccc; }

</style>

@endpush

@section('content')

<section class="wrapper main-wrapper">

    <!--breadcrumbs start-->

    <div id="breadcrumbs-wrapper" class="grey lighten-3">

      <div class="container">

        <div class="row">

          <div class="col s12 m12 l12">

            <h5 class="breadcrumbs-title">Add Device</h5>

            <ol class="breadcrumb">

              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

              </li>

              <li><a>Device</a>

              </li>

            </ol>

          </div>

        </div>

      </div>

    </div>

    <!--breadcrumbs end-->

     <div class="row" style="padding: 20px;">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-primary">
					             <div class="panel-body">

                        <div class="col-md-12">

                            {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','class'=>'validate cmxform','files'=>true]) !!}

                            <div class="form-group col s12">
              								<div class="login-box col-md-4">
              									<div>
              										<label for="device_name">Device Name: </label>
              										<input type="text" name="device_name" value="{{old('device_name')}}">
              										@if($errors->has('device_name'))<p class="text-danger">{{ $errors->first('device_name') }}</p> @endif
              									</div>
              								</div>
								<div class="login-box col-md-4">
              									<div>
              										<label for="office_name">Office Name: </label>
              										<input type="text" name="office_name" value="{{old('office_name')}}">
              										@if($errors->has('office_name'))<p class="text-danger">{{ $errors->first('office_name') }}</p> @endif
              									</div>
              								</div>
                              <div class="login-box col-md-4">
              									<div>
              										<label for="status">Status: </label>
              										<select class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="2">DeActive</option>
                                  </select>
              										@if($errors->has('status'))<p class="text-danger">{{ $errors->first('status') }}</p> @endif
              									</div>
              								</div>
              								<div class="form-group clearfix" style="margin-top:25px;">

                                  <button class="btn btn-primary pull-right">Create</button>

                                  <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>

                              </div>

                              {!! Form::close() !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>
	</div>

</section>

@endsection

@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/jstree.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script language="JavaScript">
	$('#country_id').on('change', function() {
		var country_id = this.value;
		$("#city_id").html('');
		$("#state_id").html('');
			$.ajax({
				url:"{{route('web.get.state')}}",
				type: "POST",
				data: {
				country_id: country_id,
				_token: '{{csrf_token()}}'
				},
				dataType : 'json',
				success: function(result){
					$('#state_iddiv').html('<label for="state_id">Select State: </label>'+
					'<select name="state_id" id="state_id" class="form-control nowarn" required style="display:block" onchange="getCity();">'+
					'<option value="{{old('state_id')}}">Select State Name</option>'+
					'</select>');
					$.each(result.states,function(key,value){
						$("#state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
					});
				}
			});
	});
	/*function getCity() {
		var state_id = $('#state_id').val();
		$("#city_id").html('');
		$.ajax({
			url:"{{route('web.get.city')}}",
			type: "POST",
			data: {
			state_id: state_id,
			_token: '{{csrf_token()}}'
			},
			dataType : 'json',
			success: function(result){
				$('#city_iddiv').html('<label for="city_id">Select City: </label>'+
				'<select name="city_id" id="city_id" class="form-control nowarn" required style="display:block">'+
				'<option value="{{old('city_id')}}">Select City Name</option>'+
				'</select>');
				$.each(result.city,function(key,value){
					$("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
				});
			}
		});
	}*/
</script>
<script>
        var map;
        var input = document.getElementById('pac-input');
        var latitude = document.getElementById('latitude');
        var longitude = document.getElementById('longitude');
        var address = document.getElementById('address');

        function initMap() {

            var userLocation = new google.maps.LatLng(
                13.0796758,
                80.2696968
            );

            map = new google.maps.Map(document.getElementById('map'), {
                center: userLocation,
                zoom: 15
            });

            var service = new google.maps.places.PlacesService(map);
            var autocomplete = new google.maps.places.Autocomplete(input);
            var infowindow = new google.maps.InfoWindow();

            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow({
                content: "Location",
            });

            var marker = new google.maps.Marker({
                map: map,
                draggable: true,
                anchorPoint: new google.maps.Point(0, -29)
            });

            marker.setVisible(true);
            marker.setPosition(userLocation);
            infowindow.open(map, marker);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (location) {
                    var userLocation = new google.maps.LatLng(
                        location.coords.latitude,
                        location.coords.longitude
                    );
                    marker.setPosition(userLocation);
                    map.setCenter(userLocation);
                    map.setZoom(13);
                });
            }

            google.maps.event.addListener(map, 'click', updateMarker);
            google.maps.event.addListener(marker, 'dragend', updateMarker);

            function updateMarker(event) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            input.value = results[0].formatted_address;
                            updateForm(event.latLng.lat(), event.latLng.lng(), results[0].formatted_address);
                        } else {
                            alert('No Address Found');
                        }
                    } else {
                        alert('Geocoder failed due to: ' + status);
                    }
                });

                marker.setPosition(event.latLng);
                map.setCenter(event.latLng);
            }

            autocomplete.addListener('place_changed', function (event) {
                marker.setVisible(false);
                var place = autocomplete.getPlace();

                if (place.hasOwnProperty('place_id')) {
                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }
                    updateLocation(place.geometry.location);
                } else {
                    service.textSearch({
                        query: place.name
                    }, function (results, status) {
                        if (status == google.maps.places.PlacesServiceStatus.OK) {
                            updateLocation(results[0].geometry.location, results[0].formatted_address);
                            input.value = results[0].formatted_address;
                        }
                    });
                }
            });

            function updateLocation(location) {
                map.setCenter(location);
                marker.setPosition(location);
                marker.setVisible(true);
                infowindow.open(map, marker);
                updateForm(location.lat(), location.lng(), input.value);
            }

            function updateForm(lat, lng, addr) {

				console.log(lat, lng, addr);
                latitude.value = lat;
                longitude.value = lng;
                address.value = addr;
				showVendor(lat, lng);

			}


        }

	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWkcpQRhap1jcn-e1egDd1T2JZogDsmdU&libraries=places&callback=initMap" async defer></script>
<script type="text/javascript">

 $("#back").click(function(){

    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"

  });

</script>
@endpush
