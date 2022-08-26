@extends('admin.layout.master')
@section('title','Admin :: Create Department')
@section('head')

    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Add Department
            </h3>
        </div>
    </div>
</div>
@endsection
@section('content')
<style>
  #loader-p{

z-index:999999;
display:block;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:url(https://sspl20.com/vivek/vms/public/loading-image.gif) 50% 50% no-repeat;
}
</style>

<section class="wrapper main-wrapper">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
      <div class="container">
      <div id="loader-p">
      </div>
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title">Add Department</h5>
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
    {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store']) !!}
		<div class="login-box col-md-4" style="padding: 20px;">
			<div>
				<label for="location_id">Select Location: </label>
				<select name="location_id" id="location_id" class="form-control nowarn" required>
				  <option value="{{old('location_id')}}">Select Location name</option>
				  @foreach($locations as $location)
				  <option value="{{$location->id}}">{{$location->name}}</option>
				  @endforeach
				</select>
				@if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
			</div>
		</div>
		<div id="building_iddiv" style="padding: 20px;"></div>

		<div class="form-group" style="padding: 20px;">
            <label for="">Name</label>
            {!! Form::text('name', '' , ['class'=>'form-control']) !!}
             <b class="text-danger">{{$errors->first('name')}}</b>
        </div>
        <div class="login-box col-md-4" style="padding: 20px;">
    			<div>
          <label for="location_id">Select Device: </label>
            @foreach($devices as $device)
            <ul class="list-unstyled list-group-horizontal" style="margin-left: 20px">
              <li>
                <input type="checkbox" id="{{$device->name}}" name="device_id[]" class="filled-in the-permission" value="{{$device->id}}">
                <label for="{{$device->name}}">{{$device->name}}</label>
              </li>
            </ul>
            @endforeach
    				@if($errors->has('device_id'))<p class="text-danger">{{ $errors->first('device_id') }}</p> @endif
    			</div>
    		</div>
        <div class="form-group" style="padding-left: 20px;">
            <button class="btn btn-success pull-right">Create</button>
            <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
        </div>
    {!! Form::close() !!}
</section>
@endsection
@push('scripts')
 <script type="text/javascript">


$(document).ready(function(){
  $("#loader-p").hide();
  $('select').formSelect();
});
 $("#back").click(function(){
    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
  });
  $('#location_id').on('change', function() {
		var location_id = this.value;
		$("#building_id").html('');
		getBuilding(location_id);
	});
	function getBuilding(location_id){
    $("#loader-p").show();
		$.ajax({
			url:"{{url('/web-get-building-front')}}",
			type: "POST",
			data: {
			location_id: location_id,
			_token: '{{csrf_token()}}'
			},
			dataType : 'json',
			success: function(result){
        $("#loader-p").hide();
				$('#building_iddiv').html('<label for="state_id">Select Building: </label>'+
				'<select name="building_id" id="building_id" class="form-control nowarn" required style="display:block;">'+
				'<option value="{{old('building_id')}}">Select Building Name</option>'+
				'</select>');
				$.each(result,function(key,value){
					$("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');

				});
			}
		});
	}

</script>


<div class="page-head">
@endpush
