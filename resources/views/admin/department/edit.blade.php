@extends('admin.layout.master')
@section('title','Admin :: Edit Department')
@section('head')

<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Edit Department
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
            <h5 class="breadcrumbs-title">Edit Department</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a>Department</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update', @$menus->id], 'method'=>'put','id'=>'menuForm','class'=>'col s12']) !!}


        <div class="login-box col-md-4" style="padding: 20px;">
		<div>
		<label for="location_id">Select Location: </label>
		<select name="location_id" id="location_id" class="form-control nowarn" required>
		<option value="{{old('location_id')}}">Select Location name</option>
		@foreach($locations as $location)
		<option value="{{$location->id}}"  {{$location->id == $menus->location_id ? 'selected':''}}>{{$location->name}}</option>
		@endforeach
		</select>
		@if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
		</div>
		</div>
		<div id="building_iddiv" style="padding: 20px;">
		</div>
		<div class="form-group" style="padding: 20px;">
		<label for="">Name</label>
		{!! Form::text('name', @$menus->name , ['class'=>'form-control']) !!}
		<b class="text-danger">{{$errors->first('name')}}</b>
			
		<div class="login-box col-md-4" style="padding: 20px;">
    			<div>
				
          <label for="location_id">Select Device: </label>
					@php
					 $all_device=[];
						foreach($menus->getDeviceDepartment as $device){
							array_push($all_device,$device->device_id);
						}
					@endphp
					
            @foreach($devices as $device)
					@if(in_array($device->id,$all_device))
            <ul class="list-unstyled list-group-horizontal" style="margin-left: 20px">
              <li>
                <input type="checkbox" id="{{$device->name}}" name="device_id[]" checked class="filled-in the-permission" value="{{$device->id}}">
                <label for="{{$device->name}}">{{$device->name}}</label>
              </li>
            </ul>
					@else
					<ul class="list-unstyled list-group-horizontal" style="margin-left: 20px">
              <li>
                <input type="checkbox" id="{{$device->name}}" name="device_id[]" class="filled-in the-permission" value="{{$device->id}}">
                <label for="{{$device->name}}">{{$device->name}}</label>
              </li>
            </ul>
					@endif
            @endforeach
    				@if($errors->has('device_id'))<p class="text-danger">{{ $errors->first('device_id') }}</p> @endif
    			</div>
    		</div>
			
			
        <div class="form-group" style="padding-left: 20px;">
            <button class="btn btn-success pull-right">Edit</button>
            <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
        </div>
    {!! Form::close() !!}
</section>
@endsection
@push('scripts')
 <script type="text/javascript">
 $("#back").click(function(){
    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
  });  $('#location_id').on('change', function() {
  var location_id = this.value;
  $("#building_id").html('');
  getBuilding(location_id);	});
  function getBuilding(location_id){
		$.ajax({
		  url:"{{url('web-get-building')}}",
		  type: "POST",
		  data: {
		  location_id: location_id,
		  _token: '{{csrf_token()}}'
			},
			dataType : 'json',
			success: function(result){
				$('#building_iddiv').html('<label for="state_id">Select Building: </label>'+
				'<select name="building_id" id="building_id" class="form-control nowarn" required style="display:block;">'+
				'<option value="{{old('building_id')}}">Select Building Name</option>'+
				'</select>');
				var old_building='<?php echo $menus->building_id;?>';
				$.each(result,function(key,value){
					if(old_building==value.id){
						$("#building_id").append('<option value="'+value.id+'" selected >'+value.name+'</option>');
					}else{
						$("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
					}
				});
			}
		 });
	}
	$('document').ready(function(){
		var old_location='<?php echo $menus->location_id;?>';
		getBuilding(old_location);
	});
</script>
@endpush
