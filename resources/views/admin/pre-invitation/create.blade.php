@extends('admin.layout.master')

@section('title','Admin :: Add Pre Invitation')

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

            <h5 class="breadcrumbs-title">Add Pre Invitation</h5>

            <ol class="breadcrumb">

              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

              </li>

              <li><a>Pre Invitation</a>

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

                    {{-- <div class="panel-heading">

                        <h1 class="panel-title" style="padding: 10px">Add New Pre Invitation</h1>

                    </div> --}}

                    <div class="panel-body">

                        <div class="row col s12">
                          
                            {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','class'=>'validate cmxform','files'=>true]) !!}  

                            <div class="form-group col s6">

                                <div class="col-md-3">{!! Form::label('name', 'Visitor Phone', ['class'=>'control-label']) !!}</div>

                                <div class="col-md-9">

                                    {!! Form::text('mobile', '', ['class'=>'form-control','required']) !!}

                                    @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif

                                </div>                                

                            </div> 

                            <div class="form-group col s6">

                                <div class="col-md-3">{!! Form::label('name', 'Visitor Name', ['class'=>'control-label']) !!}</div>

                                <div class="col-md-9">

                                    {!! Form::text('name', '', ['class'=>'form-control','required']) !!}

                                    @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif

                                </div>                                

                            </div>

							<div class="form-group col s6">

                                <div class="col-md-3">{!! Form::label('name', 'Visitor email', ['class'=>'control-label']) !!}</div>

                                <div class="col-md-9">

                                    {!! Form::email('email', '', ['class'=>'form-control','required']) !!}

                                    @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif

                                </div>                                

                            </div>

							

                            <div class="form-group col s6">

                                <div class="col-md-3">

                                    {!! Form::label('name', 'Pre Visit Date Time', ['class'=>'control-label']) !!}
                                </div>

                                <div class="col-md-9">

                                    <input type="datetime-local" id="pre_visit_date_time" class="form-control" name="pre_visit_date_time">

                                    @if($errors->has('pre_visit_date_time'))<p class="text-danger">{{ $errors->first('pre_visit_date_time') }}</p> @endif

                                </div>                                

                            </div>  						
                           
							
							<div class="form-group col s6" style="height: 2px; display:table;">
                                <div class="col-md-3">
									{!! Form::label('name', 'Location', ['class'=>'control-label']) !!}
								</div>
								


                                <div class="col-md-9">
									<select name="location_id" id="location_id" class="form-control nowarn" required>
                                        <option value="{{old('location_id')}}">Select</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}" {{@old('location_id') ==$location->id?'selected':''}}>{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                   
                                    @if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
                                </div>
                            </div>  
							
							<div class="form-group col s6" id="bd" style="height: 84px;">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Building', ['class'=>'control-label']) !!}
								</div>
                                <div class="col-md-9" id="building">
									       
                                    @if($errors->has('building_id'))<p class="text-danger">{{ $errors->first('building_id') }}</p> @endif
                                </div>  
                            </div>  
							
							<div class="form-group col s6" id="dd" style="margin-bottom: 57px;">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Department', ['class'=>'control-label']) !!}
								</div>
                                <div class="col-md-9" id="department">
									
                                    @if($errors->has('department_id'))<p class="text-danger">{{ $errors->first('department_id') }}</p> @endif
								</div>  
                            </div>  
                            <div class="form-group col s6" id="od">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Officer', ['class'=>'control-label']) !!}
								</div>
                                <div class="col-md-9" id="officer">									
                                    @if($errors->has('officer_id'))<p class="text-danger">{{ $errors->first('officer_id') }}</p> @endif
								</div>  
                            </div> 
                            <div class="col s12">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Upload Image', ['class'=>'control-label']) !!}
                                </div>
                                <div class="col-md-9">
                                    <input type="file" name="image" onchange="imageUploaded()" class="form-control">
                                </div>
                            </div>  
                        </div>
                        <div class="row col s12" style="margin-top: 10px;">
                            <div class="form-group "style="float: right;" >
                                <button class="btn btn-primary pull-right">Create</button>
                                <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
                            </div> 
                        </div>
                        {!! Form::close() !!}

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

 <script type="text/javascript">
    $("#bd").hide();
    $("#dd").hide();
    $("#od").hide();
    $('#location_id').on('change', function() {
        var location_id = this.value;
        $("#building_id").html('');
        $("#department_id").html('');
        getBuilding(location_id);
        $("#bd").show();
    
    });
	function getBuilding(location_id){
		$("#department_id").html('');
		$("#officer_id").html('');
        $("#loader-p").show();
        $.ajax({
            url:"{{url('/web-get-building')}}",
            type: "POST",
            data: {
            location_id: location_id,
            _token: '{{csrf_token()}}'
            },
            dataType : 'json',
            success: function(result){
                $("#loader-p").hide();
                $("#building").html('<select name="building_id" id="building_id" onchange="getDepartment()" class="form-control" style="display:block;"></select>');
                $("#building_id").append('<option value="">Select Building</option>');
                $.each(result,function(key,value){
                    $("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');                   
                });
            }
        });
	}
	function getDepartment(){
        $("#dd").show();
 
        $("#loader-p").show();
        var building_id = $("#building_id").val();
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
                $("#department").html('<select name="department_id" id="department_id" onchange="getOfficer()" class="form-control" style="display:block;"></select>');
                $("#department_id").append('<option value="">Select Department</option>');
                $.each(result,function(key,value){
                    $("#department_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
                    
                });
                
            }
        });
	}

    function getOfficer(){
        $("#od").show();
            $("#loader-p").show();
                $("#officer_id").html('');
                $("#officer").html('');
                var department_id = $("#department_id").val();
                $.ajax({
                    url:"{{route('web.get.officer')}}",
                    type: "POST",
                    data: {
                    department_id: department_id,
                    _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result){
                      $("#loader-p").hide();
                      $("#officer").append('<select name="officer_id" id="officer_id" class="form-control" style="display:block;"></select>');
                      $("#officer_id").append('<option value="">Select Officer</option>');
                        $.each(result.states,function(key,value){
                            $("#officer_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
    }

    $("#back").click(function(){
        window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
    });

    function imageUploaded(event) {
        if(event.target.name == 'image'){
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    }

</script>

@endpush

