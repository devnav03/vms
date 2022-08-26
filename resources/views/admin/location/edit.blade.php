@extends('admin.layout.master')

@section('title','Admin :: Edit Location')

@section('head')



<div class="page-head">

    <div class="row">

        <div class="col-md-4">

            <h3 class="m-b-less">

            Edit Location

            </h3>

        </div>

    </div>

</div>

@endsection

@section('content')

<section class="wrapper main-wrapper">

    <!--breadcrumbs start-->

    <div id="breadcrumbs-wrapper" class="grey lighten-3">

      <div class="container">

        <div class="row">

          <div class="col s12 m12 l12">

            <h5 class="breadcrumbs-title">Edit Location</h5>

            <ol class="breadcrumb">

              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

              </li>

              <li><a>Location</a>

              </li>

            </ol>

          </div>

        </div>

      </div>

    </div>

    {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update', @$location->id], 'method'=>'put','id'=>'menuForm','class'=>'col s12']) !!}


			<!-- <div class="login-box col-md-4" style="padding: 20px;">
				<div>
					<label for="country_id">Select Country: </label>
					<select name="country_id" id="country_id" class="form-control nowarn" required>
					  <option value="{{old('country_id')}}">Select Country name</option>
					  @foreach($get_country as $country)
					  <option value="{{$country->id}}" {{$location->country_id == $country->id ? 'selected':''}}>{{$country->name}}</option>
					  @endforeach
					</select>
					@if($errors->has('country_id'))<p class="text-danger">{{ $errors->first('country_id') }}</p> @endif
				</div>
			</div>
			<div class="login-box col-md-4" style="padding: 20px;">
				<div id="state_iddiv">
				</div>
			</div> -->
			<div class="form-group" style="padding: 20px;">

			   <h5 class="text-center mt-2rem mb-4"><span>Update Specific Location</span></h5>
				<div class="form-group">
					<div class="input-outer">
						<small><i class="fa fa-map-marker" aria-hidden="true"></i></small>
						<input type="text" id="pac-input" value="{{$location->name}}" name="location" required class="form-control" placeholder="Enter a City of Locality">
					</div>
				</div>
				{{--<div class="form-group mb-3rem">
					<div class="search-locality">
						<span>
							<i class="fa fa-crosshairs" aria-hidden="true"></i>
							Search Location Near Me
						</span>
					</div>
					<div class="row mb-3">
						<div class="col-12 ">
							<div id="map" style="height:200px;"></div>
						</div>
						<input type="hidden" maxlength="9" id="longitude" name="longitude"  readonly >
						<input type="hidden" maxlength="9" id="latitude" name="latitude"  readonly >
					</div>
				</div>--}}
			</div>

        <div class="form-group" style="padding-left: 20px;">

            <button class="btn btn-success pull-right">Edit</button>

            <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>

        </div>

    {!! Form::close() !!}

</section>

@endsection

@push('scripts')
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

		$('#country_id').on('change', function() {
                var country_id = this.value;
                $("#city_id").html('');
				$("#state_id").html('');
				getState(country_id);
            });
			function getState(country_id){
				$.ajax({
                    url:"{{route('web.get.state')}}",
                    type: "POST",
                    data: {
                    country_id: country_id,
                    _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result){
						var old_state='<?php echo $location->state_id;?>';
						$('#state_iddiv').html('<label for="state_id">Select State: </label>'+
						'<select name="state_id" id="state_id" class="form-control nowarn" required style="display:block" onchange="getCity();">'+
						'<option value="{{old('state_id')}}">Select State Name</option>'+
						'</select>');
						$.each(result.states,function(key,value){
							if(old_state==value.id){
								$("#state_id").append('<option value="'+value.id+'" selected >'+value.name+'</option>');
							}else{
								$("#state_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
							}

                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
			}
			$('document').ready(function(){
				var old_country='<?php echo $location->country_id;?>';
				getState(old_country);
			});

</script>

@endpush
