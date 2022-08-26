@extends('admin.layout.master')
@section('title','Admin :: Add Visitor')
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
            <h5 class="breadcrumbs-title">Add Visitor</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a>Visitor</a>
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
                        <div class="row">
                            {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','class'=>'validate cmxform','files'=>true]) !!} 
							<div class="form-group col s6">
								 <label for="location_id">Select Location: </label>
								<select name="location_id" id="location_id" class="form-control nowarn" required>	
									<option value="{{old('location_id')}}">Select Location name</option>			
									@foreach($locations as $location)									 
									<option value="{{$location->id}}">{{$location->name}}</option>		
									@endforeach									
								</select>				
								@if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
							</div>	
							<div  class="form-group col s6" id="building_idDiv">
								 
							</div>
					</div>
					<div class="row">	
						<div  class="form-group col s6" id="department_idDiv">
							 
						</div>
						<div class="form-group col s6" id="officer_idDiv">
						</div>
					</div>
					<div class="row">	
                            <div class="form-group col s6">
                                <div class="col-md-3">
                                    <label class="control-label">Select Services</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="services" class="form-control" required>
                                        <option value="">Select Services</option>
                                         <option value="{{old('services')}}">Select Services</option>
										<option value="Official">Official</option>
										<option value="Personal">Personal</option>
										<option value="Adhar Services Complaint">Adhar Services Complaint </option>
										<option value="Birth Certificate">Birth Certificate</option>
										<option value="Death Certificate">Death Certificate</option>
                                    </select>
                                    @if($errors->has('services'))<p class="text-danger">{{ $errors->first('services') }}</p> @endif
                                </div>                                
                            </div>                    
                            <div class="form-group col s6">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Name', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::text('name', '', ['class'=>'form-control','required']) !!}
                                    @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                                </div>                                
                            </div>
						</div>
					<div class="col-md-12">	
                            <div class="form-group col s6">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Email', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::email('email', '', ['class'=>'form-control','required']) !!}
                                    @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
                                </div>                                
                            </div>                   
                            <div class="form-group col s6">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Phone', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::text('mobile', '', ['class'=>'form-control','required']) !!}
                                    @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                                </div>                                
                            </div> 
					</div>
					<div class="col-md-12">	
							<div class="form-group col s6">
                                <div class="col-md-3">Document Type</div>
                                <div class="col-md-9">
                                    {!! Form::select('document_type', ['dl' => 'Driving Licence', 'adhar_card' => 'Adhar Card','govt_id_pf'=>'Govt Identity Proof'], null, ['class' => 'form-control']) !!}
                                    @if($errors->has('document_type'))<p class="text-danger">{{ $errors->first('document_type') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group col s6">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Adhar id', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::Number('adhar_no', '', ['class'=>'form-control','required']) !!}
                                    @if($errors->has('adhar_no'))<p class="text-danger">{{ $errors->first('adhar_no') }}</p> @endif
                                </div>                                
                            </div>                 
                            <div class="form-group col s6">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Visit Date Time', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    <input type="datetime-local" id="visite_time" class="form-control" name="visite_time">
                                    @if($errors->has('visite_time'))<p class="text-danger">{{ $errors->first('visite_time') }}</p> @endif
                                </div>                                
                            </div>
					</div>
					<div class="col-md-12">	
                            <div class="form-group col s6">
                                <div class="col-md-3">Gender</div>
                                <div class="col-md-9">
                                    {!! Form::select('gender', ['Male' => 'Male', 'Female' => 'Female','other'=>'Other'], null, ['class' => 'form-control']) !!}
                                    @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group col s6">
                                <div class="col-md-3">Is Active</div>
                                <div class="col-md-9">
                                    {!! Form::select('status', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) !!}
                                    @if($errors->has('status'))<p class="text-danger">{{ $errors->first('status') }}</p> @endif
                                </div>
                            </div>	
					</div>
					<div class="col-md-12">	
							<div class="form-group col s6">		
								<div>						
								<label for="country_id">Select Country: </label>
								<select name="country_id" id="country_id" class="form-control nowarn" required>		
									<option value="{{old('country_id')}}">Select Country name</option>	
									@foreach($get_country as $country)							
										<option value="{{$country->id}}">{{$country->name}}</option>	
									@endforeach								
								</select>						
								@if($errors->has('country_id'))<p class="text-danger">{{ $errors->first('country_id') }}</p> @endif	
								</div>						
							</div>						
							<div class="form-group col s6">		
								<div id="state_iddiv">				
								</div>						
							</div>	
					</div>
					<div class="col-md-12">	
							<div class="form-group col s6">		
								<div id="city_iddiv">		
								</div>						
							</div>				
							<div class="form-group col s6">		
								<div>							
								<label for="pincode">Pincode:</label>
								<input placeholder="Enter Pincode" type="text" name="pincode" size="30" value="{{old('pincode')}}" class="form-control nowarn" onkeypress="return (event.charCode > 47 && event.charCode < 58)" required>								@if($errors->has('pincode'))<p class="text-danger">{{ $errors->first('pincode') }}</p> @endif	
								</div>						
							</div>
					</div>
					<div class="col-md-12">	
							<div class="form-group col s6">		
								<div>						
									<label for="address_1">Address-1:</label>	
									<textarea row="5" col="5" class="form-control nowarn" name="address_1" required></textarea>		
									@if($errors->has('address_1'))<p class="text-danger">{{ $errors->first('address_1') }}</p> @endif	
								</div>					
							</div>		
							<div class="form-group col s6">	
								<div>						
								<label for="address_2">Address-2:</label>
								<textarea row="5" col="5" class="form-control nowarn" name="address_2" required></textarea>	
								@if($errors->has('address_2'))<p class="text-danger">{{ $errors->first('address_2') }}</p> @endif	
								</div>						
							</div>
					</div>
					<div class="col-md-12">	
						
							<div class="form-group col s6">
                                <div class="col-md-3">{!! Form::label('organization_name', 'Organization Name', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::text('organization_name', '', ['class'=>'form-control','required']) !!}
                                    @if($errors->has('organization_name'))<p class="text-danger">{{ $errors->first('organization_name') }}</p> @endif
                                </div>                                
                            </div>					
						
						<div class="form-group col s6">		
							<div>						
							<label for="orga_country_id">Select Organization Country: </label>
							<select name="orga_country_id" id="orga_country_id" class="form-control nowarn" required>		
								<option value="{{old('orga_country_id')}}">Select Country name</option>	
								@foreach($get_country as $country)							
									<option value="{{$country->id}}">{{$country->name}}</option>	
								@endforeach								
							</select>						
							@if($errors->has('orga_country_id'))<p class="text-danger">{{ $errors->first('orga_country_id') }}</p> @endif	
							</div>						
						</div>	
					</div>
					<div class="col-md-12">
						<div class="form-group col s6">		
							<div id="orga_state_iddiv">				
							</div>						
						</div>
						<div class="form-group col s6">		
							<div id="orga_city_iddiv">		
							</div>						
						</div>							
					</div>
					<div class="col-md-12">	
						
							<div class="form-group col s6">
                                <div class="col-md-3">{!! Form::label('orga_pincode', 'Organization Pincode', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::text('orga_pincode', '', ['class'=>'form-control','required']) !!}
                                    @if($errors->has('orga_pincode'))<p class="text-danger">{{ $errors->first('orga_pincode') }}</p> @endif
                                </div>                                
                            </div>					
						
					</div>
					<div class="col-md-12">	
                            <div class="form-group clearfix">
                                <div class="col s2">Image</div>
                                <div class="col s3" style="margin-top: -25px;">
                                    <div id="my_camera"></div>
                                    <input class="btn btn-primary  btn-lg" type=button value="Take Snapshot" onClick="take_snapshot()">
                                    <input type="hidden" name="image" class="image-tag">
                                    @if($errors->has('image'))<p class="text-danger">{{ $errors->first('image') }}</p> @endif
                                </div>
                                <div class="col s1"></div>
                                <div class="col s3">
                                    <div id="results">Your captured image will appear here...</div>
                                </div>
                            </div>
					</div>
					<div class="col-md-12">	
                            <div class="form-group col s12">
                                <h4 style="color: darkblue;"><center>Covid Declaration Form</center></h4>
                                <div class="form-group col s6">
                                    <div class="col-md-3">Have you taken the vaccine?</div>
                                    <div class="col-md-9">
                                        <select name="vaccine" class="form-control" required>
                                        <option value="">Select Option</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    </div>
                                </div>
					</div>
					<div class="col-md-12">	
                                <div class="form-group col s6">
                                    <div class="col-md-3">Are you currently experiencing any following symptoms?</div>
                                    <div class="col-md-9">
                                       <select name="symptoms" class="form-control">
                                           <option value="">Select First</option>
                                           @foreach($symptoms as $sty);
                                           <option value="{{$sty->name}}">{{$sty->name}}</option>
                                           @endforeach();
                                       </select>
                                        @if($errors->has('symptoms'))<p class="text-danger">{{ $errors->first('symptoms') }}</p> @endif
                                    </div>
                                </div> 
						</div>
					<div class="col-md-12">	
                                <div class="form-group col s6">
                                    <div class="col-md-3">Have your travelled in past 14 days to any of the states?</div>
                                    <div class="col-md-9">
                                        <select name="travelled_states" class="form-control" required>
                                        <option value="">Select Option</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group col s6">
                                    <div class="col-md-3">Did you get in touch with any Covid positive patient ?</div>
                                    <div class="col-md-9">
                                        <select name="patient" class="form-control" required>
                                        <option value="">Select Option</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    </div>
                                </div>
						</div>
					<div class="col-md-12">	

                                <div class="form-group col s6">
                                    <div class="col-md-3">Current Body Temprature</div>
                                    <div class="col-md-9">
                                        {!! Form::text('temprature', '', ['class'=>'form-control','required']) !!}
                                        @if($errors->has('temprature'))<p class="text-danger">{{ $errors->first('temprature') }}</p> @endif
                                    </div>                                
                                </div> 
                            </div> 
                            <div class="form-group clearfix">
                                <button class="btn btn-primary pull-right">Create</button>
                                <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-md-offset-2 text-center">
                <img id="output"/>
            </div>
        </div>
</section>
@endsection
@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/jstree.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script language="JavaScript">
    Webcam.set({
        width: 200,
        height: 200,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach( '#my_camera' );
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'" width = "225px" height= "150px"/>';
            document.getElementById("Submit").style.display="block";
        } );
    }	
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
	function getCity() {
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
	}
	$('#location_id').on('change', function() {
	  var location_id = this.value;
	  $("#building_id").html('');
	  $("#department_id").html('');
	  $("#officer_id").html('');
	  getBuilding(location_id);
	});
	
	
		function getBuilding(location_id){
			  $("#department_id").html('');
			  $("#officer").html('');
			  $.ajax({			
				  url:"{{url('web-get-building')}}",			
				  type: "POST",			
				  data: {			
				  location_id: location_id,			
					_token: '{{csrf_token()}}'			
				  },			
				dataType : 'json',			
				success: function(result){	
					$('#building_idDiv').html('<label for="building_id">Select Building: </label>'+
									'<select name="building_id" id="building_id" class="form-control nowarn" required style="display:block;">'+
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

		/* on click department */
	
	
	
		  function getDepartment(building_id){
			   $("#officer").html('');
				  $.ajax({			
					  url:"{{url('web-get-department')}}",			
					  type: "POST",			
					  data: {			
					  building_id: building_id,
					  _token: '{{csrf_token()}}'	
					  },			
					  dataType : 'json',
					  success: function(result){
						  $('#department_idDiv').html('<label for="department_id">Select Department: </label>'+
								'<select name="department_id" onchange="getOfficer()" id="department_id" class="form-control nowarn" required style="display:block;">'+
								'</select>');
						  
							$.each(result,function(key,value){		
							  $("#department_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
								  if(key==0){						
									getOfficer(value.id);					
								}									
							});
						  		
						 }		
					  });	
					  }
					  
		$('#department_id').on('change', function() {
		  var department_id = this.value;
		  console.log(department_id);
		  $("#officer").html('');
		  getOfficer(department_id);
		});	  
					  
			function getOfficer() {
				 var depart_id = $("#department_id").val();
				$("#officer").html('');
                $.ajax({
                    url:"{{route('web.get.officer')}}",
                    type: "POST",
                    data: {
                    department_id: depart_id,
                    _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result){
						$('#officer_idDiv').html('<label for="officer">Select Officer: </label>'+
							'<select name="officer" id="officer" class="form-control nowarn" required style="display:block;">'+
							'</select>');
						$("#officer").append('<option value="">select offcer</option>');
                        $.each(result.states,function(key,value){
                            $("#officer").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            }
			
			
	 $("#back").click(function(){
		window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
	  });
	$('#orga_country_id').on('change', function() {
		var country_id = this.value;
		$("#orga_city_id").html('');	
		$("#orga_state_id").html('');
			$.ajax({
				url:"{{route('web.get.state')}}",
				type: "POST",
				data: {		
				country_id: country_id,
				_token: '{{csrf_token()}}'
				},	
				dataType : 'json',
				success: function(result){	
					$('#orga_state_iddiv').html('<label for="orga_state_id">Select State: </label>'+				
					'<select name="orga_state_id" id="orga_state_id" class="form-control nowarn" required style="display:block" onchange="getOrgaCity();">'+
					'<option value="{{old('orga_state_id')}}">Select State Name</option>'+
					'</select>');								
					$.each(result.states,function(key,value){
						$("#orga_state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
					});										
				}		
			});	
	});
	function getOrgaCity() {
		var state_id = $('#orga_state_id').val();
		$("#orga_city_id").html('');	
		$.ajax({		
			url:"{{route('web.get.city')}}",	
			type: "POST",			
			data: {					
			state_id: state_id,		
			_token: '{{csrf_token()}}'	
			},					
			dataType : 'json',	
			success: function(result){	
				$('#orga_city_iddiv').html('<label for="orga_city_id">Select City: </label>'+	
				'<select name="orga_city_id" id="orga_city_id" class="form-control nowarn" required style="display:block">'+	
				'<option value="{{old('orga_city_id')}}">Select City Name</option>'+		
				'</select>');					
				$.each(result.city,function(key,value){	
					$("#orga_city_id").append('<option value="'+value.id+'">'+value.name+'</option>');	
				});					
			}	
		});
	}
</script>
@endpush
