@extends('admin.layout.master')
@section('title','Dashboard')
<style>
  .sr-custom-collapsive .panel-success .panel-heading{
      padding: 15px 10px;
      background: #85c7a6;
  }
  .sr-custom-collapsive .panel-heading .panel-title a{
      color: #fff !important;
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      text-transform: uppercase;
      position: relative;
      width: 100%;
      text-align: left;
      display: block;
  }
  .panel-group .panel-heading .panel-title > a.collapsed:after {
    content: "\f067";
    color: #8b8b8b;
  }
  .panel-group .panel-heading .panel-title > a:after {
      font-family: 'FontAwesome';
      content: "\f068";
      float: right;
      color: #333;
  }
</style>
@section('head')
<div class="page-head">
  <div class="row">
    <div class="col-md-4">
      <h3 class="m-b-less">
      Edit Visitor
      </h3>
    </div>
  </div>
</div>
@endsection
@section('content')
 
 
  <section class="wrapper main-wrapper">
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title">Edit Visitor</h5>
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
                  <div class="col-md-12">
                      {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update',$user->id],'method'=>'put', 'id'=>'update-form','files'=>true]) !!}
                      <div class="form-group col s6">
                          <div class="col-md-3">
                              <label class="control-label">Select Officer</label>
                          </div>
                          <div class="col-md-9">
                              <select name="officer" class="form-control">
                                  <option value="">Select Officer</option>
                                  @foreach($get_officers as $officers)
                                      <option value="{{$officers->id}}" {{$officers->id == $user->officer_id ?'selected':''}}>{{$officers->name}}</option>
                                  @endforeach
                              </select>
                              @if($errors->has('officer'))<p class="text-danger">{{ $errors->first('officer') }}</p> @endif
                          </div>
                      </div>
                      <div class="form-group col s6">
                          <div class="col-md-3">
                              <label class="control-label">Select Services</label>
                          </div>
                          <div class="col-md-9">
                              <select name="services" class="form-control" required>
                                  <option value="">Select Services</option>
                								  <option value="Official" {{$user->services == "Official"?'selected':''}}>Official</option>
                								  <option value="Personal" {{$user->services == "Personal"?'selected':''}}>Personal</option>
                                  <option value="Adhar Services Complaint"{{$user->services == "Adhar Services Complaint"?'selected':''}}>Adhar Services Complaint </option>
                                  <option value="Birth Certificate"{{$user->services == "Birth Certificate"?'selected':''}}>Birth Certificate</option>
                                  <option value="Death Certificate"{{$user->services == "Death Certificate"?'selected':''}}>Death Certificate</option>
                              </select>
                              @if($errors->has('services'))<p class="text-danger">{{ $errors->first('services') }}</p> @endif
                          </div>
                      </div>
                      <div class="form-group col s6">
                          <div class="col-md-3">{!! Form::label('name', 'Visitor Name', ['class'=>'control-label']) !!}</div>
                          <div class="col-md-9">
                              {!! Form::text('name', $user->name, ['class'=>'form-control','required']) !!}
                              @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                          </div>
                      </div>
                      <div class="form-group col s6">
                          <div class="col-md-3">{!! Form::label('name', 'Visitor Email', ['class'=>'control-label']) !!}</div>
                          <div class="col-md-9">
                              {!! Form::email('email', $user->email, ['class'=>'form-control','required']) !!}
                              @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
                          </div>
                      </div>
                      <div class="form-group col s6">
                          <div class="col-md-3">{!! Form::label('name', 'Visitor Phone', ['class'=>'control-label']) !!}</div>
                          <div class="col-md-9">
                              {!! Form::text('mobile', $user->mobile, ['class'=>'form-control','required']) !!}
                              @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                          </div>
                      </div>
                      <div class="form-group col s6">
                          <div class="col-md-3">{!! Form::label('name', 'Visitor Adhar id', ['class'=>'control-label']) !!}</div>
                          <div class="col-md-9">
                              {!! Form::text('adhar_no', $user->adhar_no, ['class'=>'form-control','required']) !!}
                              @if($errors->has('adhar_no'))<p class="text-danger">{{ $errors->first('adhar_no') }}</p> @endif
                          </div>
                      </div>
                      <div class="form-group col s6">
                        <div class="col-md-3">{!! Form::label('name', 'Visitor Duration', ['class'=>'control-label']) !!}</div>
                        <div class="col-md-9">
                          {!! Form::text('visite_duration', $user->visite_duration, ['class'=>'form-control','required']) !!}
                           @if($errors->has('visite_duration'))<p class="text-danger">{{ $errors->first('visite_duration') }}</p> @endif
                         </div>
                       </div>
                       <div class="form-group col s6">
                         <div class="col-md-3">{!! Form::label('name', 'Topic', ['class'=>'control-label']) !!}</div>
                         <div class="col-md-9"> {!! Form::text('topic', $user->topic, ['class'=>'form-control',]) !!}
                             @if($errors->has('topic'))<p class="text-danger">{{ $errors->first('topic') }}</p> @endif
                           </div>
                         </div>
                         <div class="form-group col s6">
                           <div class="col-md-3">{!! Form::label('name', 'Visitor Vehicle Registration Number', ['class'=>'control-label']) !!}</div>
                           <div class="col-md-9">                              {!! Form::text('vehical_reg_num', $user->vehical_reg_num, ['class'=>'form-control']) !!}
                               @if($errors->has('vehical_reg_num'))<p class="text-danger">{{ $errors->first('vehical_reg_num') }}</p> @endif
                             </div>
                           </div>
                      <div class="form-group col s6">
                          <div class="col-md-3">Gender</div>
                          <div class="col-md-9">
                              {!! Form::select('gender', ['Male' => 'Male', 'Female' => 'Female'], $user->gender, ['class' => 'form-control']) !!}
                              @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
                          </div>
                      </div>
                      <div class="form-group col s6">
                          <div class="col-md-3">Is Active</div>
                          <div class="col-md-9">
                              {!! Form::select('status', ['1' => 'Yes', '0' => 'No','2'=>'Block'], $user->status, ['class' => 'form-control']) !!}
                              @if($errors->has('status'))<p class="text-danger">{{ $errors->first('status') }}</p> @endif
                          </div>
                      </div>
                      @if(auth('admin')->user()->role_id ==6)
                      <div class="form-group col s6">
                          <div class="col-md-3">Appointment Status</div>
                          <div class="col-md-9">
                              {!! Form::select('app_status', ['Pending' => 'Pending', 'Approve' => 'Approve', 'Reject' => 'Reject', 'Reschedule' => 'Reschedule'], $user->app_status, ['class' => 'form-control','id'=>'app_status']) !!}
                              @if($errors->has('app_status'))<p class="text-danger">{{ $errors->first('app_status') }}</p> @endif
                          </div>
                      </div>

                       <div class="form-group col s6">
                          <div class="col-md-3">{!! Form::label('name', 'Visit Date Time', ['class'=>'control-label']) !!}</div>
                          <div class="col-md-9">
                               <input type="datetime-local" id="visite_time" value="{{$user->visite_time}}" class="form-control" name="visite_time">
                              @if($errors->has('visite_time'))<p class="text-danger">{{ $errors->first('visite_time') }}</p> @endif
                          </div>
                      </div>
                      @else

                       <div class="form-group col s6">
                          <div class="col-md-3">{!! Form::label('name', 'Visit Date Time', ['class'=>'control-label']) !!}</div>
                          <div class="col-md-9">
                               <input type="datetime-local" value="{{$user->visite_time}}" class="form-control" name="visite_time">
                              @if($errors->has('visite_time'))<p class="text-danger">{{ $errors->first('visite_time') }}</p> @endif
                          </div>
                      </div>
                      @endif
                     <div class="form-group col s3">
                          <div class="col-md-3 ">Visitor Image</div>
                          <div class="col-md-9">
                             <img src="{{str_replace('','',URL::to('/'))}}/uploads/img/{{$user->image?$user->image:'assets/img/doctor.jpg'}}" class="img-responsive pull-right" style="width: 175px;height: 175px">
                          </div>
                      </div>
					  <div class="form-group col s3">
                          <div class="col-md-3 ">Attachment</div>
                          <div class="col-md-9">
                              <img src="{{str_replace('','',URL::to('/'))}}/uploads/img/{{$user->attachmant?$user->attachmant:'assets/img/doctor.jpg'}}" class="img-responsive pull-right" style="width: 175px;height: 175px">
                          </div>
                      </div>

                 
                                                                                                                                       
					  
                      <div class="form-group clearfix">
                        <input type="hidden" name="type" value="update_user">
                        <button type="submit" class="btn btn-primary">update</button>
                        <a href="{{route('admin.'.request()->segment(2).'.index')}}" class="btn btn-info" style="background: #25cfea;">Back</a>
                      </div>
                      {!! Form::close() !!}
                  </div>
              </div>
          </div>
      </div>
    </div>
</section>
 
 
 
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
     $('#visite_time').attr('disabled','disabled');
    });
    $('#app_status').change(function(){
        $("#visite_time").removeAttr('disabled');
    });
    var vaccine_detils='<?php echo $user->vaccine;?>';
    if(vaccine_detils=="yes"){
      $('#vaccine_details').show();
    }else{
      $('#vaccine_details').hide();
    }
    
    function vaccineCheck(val){
      if(val=="yes"){
        $('#vaccine_details').show();
      }else{
        $('#vaccine_details').hide();
      }
    }
</script>
@endpush
