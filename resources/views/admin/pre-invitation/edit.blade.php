@extends('admin.layout.master')

@section('title','Admin :: Edit Pre Invitation')

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

            <h5 class="breadcrumbs-title">Edit Pre Invitation</h5>

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

                    <div class="panel-body">

                        <div class="col-md-12">
                            <div class="col-md-12" style="text-align:center;padding-bottom: 22px;">
                                <p>Visitor Image</p>
                                <img src="{{str_replace("/public","",URL::to("/")).'/storage/app/public/'.@$menus->image}}" style="width: 140px;
                                border-radius: 50%;">
                            </div>
                               {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update',$user],'method'=>'put','files'=>true]) !!}

                            <input type="hidden" name="type" value="update_user">

                            <div class="form-group col s6">

                                <div class="col-md-3">{!! Form::label('name', 'Visitor Phone', ['class'=>'control-label']) !!}</div>

                                <div class="col-md-9">

                                    {!! Form::text('mobile', @$menus->mobile, ['class'=>'form-control','required']) !!}

                                    @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif

                                </div>                                

                            </div> 

                            <div class="form-group col s6">

                                <div class="col-md-3">{!! Form::label('name', 'Visitor Name', ['class'=>'control-label']) !!}</div>

                                <div class="col-md-9">

                                    {!! Form::text('name', @$menus->name, ['class'=>'form-control','required']) !!}

                                    @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif

                                </div>                                

                            </div>   
							<div class="form-group col s6">

                                <div class="col-md-3">{!! Form::label('name', 'Visitor email', ['class'=>'control-label']) !!}</div>

                                <div class="col-md-9">

                                    {!! Form::email('email',  @$menus->email, ['class'=>'form-control','required']) !!}

                                    @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif

                                </div>                                

                            </div>

                            <div class="form-group col s6">

                                <div class="col-md-3">

                                    {!! Form::label('name', 'Pre Visit Date Time', ['class'=>'control-label']) !!}</div>

                                <div class="col-md-9">

                                    <input type="datetime-local" id="pre_visit_date_time" class="form-control" name="pre_visit_date_time" value="{{$menus->pre_visit_date_time}}">

                                    @if($errors->has('pre_visit_date_time'))<p class="text-danger">{{ $errors->first('pre_visit_date_time') }}</p> @endif

                                </div>                                

                            </div>  
							
							<div class="form-group col s6">
                                <div class="col-md-3">
									{!! Form::label('name', 'Location', ['class'=>'control-label']) !!}
								</div>
								
                                




                                <div class="col-md-9">
									<select name="location_id" id="location_id" class="form-control" readonly="true">
										<option value="{{@$user_details->location->id}}">{{@$user_details->location->name}}</option>
									</select>
                                   
                                    @if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
                                </div>
                            </div>  
							
							<div class="form-group col s6">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Building', ['class'=>'control-label']) !!}
								</div>
                                <div class="col-md-9">
									<select name="building_id" id="building_id" class="form-control" readonly="true">
										<option value="{{@$user_details->building->id}}">{{@$user_details->building->name}}</option>
									</select>                                   
                                    @if($errors->has('building_id'))<p class="text-danger">{{ $errors->first('building_id') }}</p> @endif
                                </div>  
                            </div>  
							
							<div class="form-group col s6">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Department', ['class'=>'control-label']) !!}
								</div>
                                <div class="col-md-9">
									<select name="department_id" id="department_id" class="form-control" readonly="true">
										<option value="{{@$user_details->OfficerDepartment->id}}">{{@$user_details->OfficerDepartment->name}}</option>
									</select>
                                    @if($errors->has('department_id'))<p class="text-danger">{{ $errors->first('department_id') }}</p> @endif
								</div>  
                            </div>  
							
							<div class="form-group col s6">
                                <div class="col-md-3">
                                    {!! Form::label('name', 'Officer', ['class'=>'control-label']) !!}
								</div>
                                <div class="col-md-9">
									<select name="officer_id" id="officer_id" class="form-control" readonly="true">
										<option value="{{@$user_details->OfficerDetail->id}}">{{@$user_details->OfficerDetail->name}}</option>
									</select>
                                    @if($errors->has('officer_id'))<p class="text-danger">{{ $errors->first('officer_id') }}</p> @endif
								</div>  
                            </div>  

                            <div class="form-group clearfix">

                                <button class="btn btn-primary pull-right">Edit</button>

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

 <script type="text/javascript">

 $("#back").click(function(){

    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"

  });

</script>

@endpush

