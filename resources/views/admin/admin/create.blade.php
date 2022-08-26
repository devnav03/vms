@extends('admin.layout.master')
@section('title','Add Employee')
@section('content')
<style>
  #loader-p{

  z-index:999999;
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:url(https://sspl20.com/vivek/vms/public/loading-image.gif) 50% 50% no-repeat;
}
</style>

    <section id="content">
      <div id="loader-p">
      </div>
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Add Employee</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Employee</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        <!--start container-->
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="card-panel">
                      <h4 class="header2">Add Employee</h4>
                      <div class="row">
                         {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','files'=>true]) !!}
							<div class="row">
								<div class="input-field col s6">
									<label for="status">Employee Name</label>
									{{ Form::text('name',null,['class'=>'form-control']) }}
									<p class="text-danger">{{$errors->first('name')}}</p>
								</div>
								<div class="input-field col s6">
								  {{-- {!! Form::label('role','Role', []) !!} --}}
								  {!! Form::select('role', $roles, null, ['class'=>'form-control', 'onchange'=>'RoleCheck(this.value)', 'placeholder'=>'Select Role','id'=>'roles']) !!}
								  <p class="text-danger">{{$errors->first('role')}}</p>
								</div>
								
              			  </div>
						  <div class="row">
							  <div class="input-field col s6">

									<select name="location_id" id="location_id" class="form-control nowarn" required>
										<option value="{{old('location_id')}}">Select Location name</option>
										@foreach($locations as $location)
										<option value="{{$location->id}}">{{$location->name}}</option>
										@endforeach
									</select>
									@if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
								</div>
							  <div id="building_iddiv" class="input-field col s6">
								</div>
							
								
                          </div>
              		    <div class="row">
							<div class="input-field col s6" id="department_iddiv">
						 	</div>
							<div id="device_iddiv" class="input-field col s6">
							</div>
						</div>
							
                          <div class="row">
                            <div class="input-field col s6">
                                <label for="email">Admin Email</label>
                                {{ Form::text('email',null,['class'=>'form-control']) }}
                                <p class="text-danger">{{$errors->first('email')}}</p>
                            </div>
                            <div class="input-field col s6">
                                <label for="email">Mobile</label>
                                {{ Form::text('mobile',null,['class'=>'form-control']) }}
                                <p class="text-danger">{{$errors->first('mobile')}}</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="input-field col s6">
                                <label for="name">Admin Password</label>
                                {{ Form::password('password',['class'=>'form-control']) }}
                                <p class="text-danger">{{$errors->first('password')}}</p>
                            </div>
                            <div class="input-field col s6">
                              <label for="password" style='top: -1.1rem'>Ip allow</label>
                              {{-- {!! Form::label('allowip',null, ['class'=>'allowip']) !!} --}}
                              {!! Form::select('allowip',['0'=>'Selected Ip','1'=>'all Ip'],['class'=>'form-control'], ['class'=>'form-control']) !!}
                            </div>
                          </div>
                          <div class="row">
                            <div class="input-field col s6">
                              <label for="ip address">Ip Address</label>
                              {!! Form::label('Ip address',null, []) !!}
                              {{ Form::text('ip',null,['class'=>'form-control']) }}
                              <p class="text-danger">{{$errors->first('ip')}}</p>
                            </div>
                            <div class="input-field col s6">
                                <label for="status" style='top: -1.1rem'>Status</label>
                                {{ Form::select('status_id',['1'=>'Active','2'=>'Deactive'],null,['class'=>'form-control']) }}
                                <p class="text-danger">{{$errors->first('status')}}</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="form-group col s4">
                                <label for="employee_type">Employee Type:</label>
                                <select class="form-control" name="employee_type" id="employee_type">
                                  <option value="permanent">Permanent</option>
                                  <option value="temprary">Temprary</option>
                                  <option value="contract_base">Contract Base</option>
                                </select>
                                @if($errors->has('employee_type'))<p class="text-danger">{{ $errors->first('employee_type') }}</p> @endif
                            </div>
                            <div id="from_date">
                              <div class="form-group col s4">
                                <label for="employee_from_date">From Date:</label>
                                <input type="datetime-local" class="form-control" name="employee_from_date" value="">
                                @if($errors->has('employee_from_date'))<p class="text-danger">{{ $errors->first('employee_from_date') }}</p> @endif
                              </div>
                              <div class="form-group col s4">
                                <label for="employee_till_date">Till Date:</label>
                                <input type="datetime-local"  class="form-control" name="employee_till_date" value="">
                                @if($errors->has('employee_till_date'))<p class="text-danger">{{ $errors->first('employee_till_date') }}</p> @endif
                              </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="input-field col s12">
                                <button type="submit" class="btn btn-info" >Create</button>
                                <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
                              </div>
                          </div>
                        {!! Form::close() !!}
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <!--end container-->
    </section>
        <!-- START FOOTER -->
    <footer class="page-footer">
        <div class="footer-copyright">
            <div class="container"> Copyright © 2021.  All rights reserved.
            </div>
        </div>
    </footer>
    <!-- END FOOTER -->
@endsection
@push('scripts')
<script type="text/javascript">
$("#back").click(function(){
    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
  });    $('#location_id').on('change', function() {
	  var location_id = this.value;
	  $("#building_id").html('');
	  $("#department").html('');
	  getBuilding(location_id);	});
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
		  $('#building_iddiv').html('<select name="building_id" id="building_id" class="form-control nowarn" style="display:block;">'+
			'<option value="" >Select Building</option>'+
		  '</select>');
		  $.each(result,function(key,value){
		  $("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
		  if(key==0){
			getDepartment(value.id);
		  }
		  });

		  }
		  });
		  }
		  function getDepartment(building_id){
        $("#loader-p").show();
		  $.ajax({
		  url:"{{url('/web-get-department')}}",
		  type: "POST",
		  data: {
		  building_id: building_id,
		  _token: '{{csrf_token()}}'
		  },
		  dataType : 'json',
		  success: function(result){
        $("#loader-p").hide();
			  $('#department_iddiv').html('<select name="department" id="department" onchange="getDevice();" class="form-control nowarn" style="display:block;">'+
				'<option value="" >Select Department</option>'+
			  '</select>');
			  $.each(result,function(key,value){
			  $("#department").append('<option value="'+value.id+'" >'+value.name+'</option>');
			  });
			  }
			  });
			  }
        $('#from_date').hide();
        $("#employee_type").on('change', function(){
            var from_date = this.value;
            if(from_date =='permanent'){
                $('#from_date').hide();
            }else{
                $('#from_date').show();
            }
        });
        function getDevice(){
          $('#device_id').val();
          var department_id=$('#department').val();
          $("#loader-p").show();
            $.ajax({
              url:"{{url('/web-get-device-front')}}",
              type: "POST",
              data: {
              department_id: department_id,
              _token: '{{csrf_token()}}'
              },
              dataType : 'json',
              success: function(result){
                $("#loader-p").hide();
                $('#device_iddiv').html('<select name="device_id" id="device_id"  class="form-control nowarn" style="display:block;">'+
                  '<option value="" >select Device</option>'+
                  '</select>');
                $.each(result,function(key,value){
                  $("#device_id").append('<option value="'+value.device.id+'" >'+value.device.name+'</option>');
                });
              }
            });
        }
	function RoleCheck(role){
		if(role==8){
			
			
			$('#department_iddiv').hide();
			
		}
	}


</script>
@endpush
