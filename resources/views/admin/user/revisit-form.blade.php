

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



      Re-Visitor Detail



      </h3>



    </div>



  </div>



</div>



@endsection



@section('content')
  
  <section class="wrapper main-wrapper" >
     <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h1 class="panel-title" style="padding: 10px">Re-Visitor</h1>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            {!! Form::open(['route'=>'add-revisit-add','method'=>'POST', 'id'=>'update-form','files'=>true]) !!}
                            <input type="hidden" value="{{$user->id}}" name="id">
                            <div class="form-group clearfix">
                                <div class="col-md-3">
                                    <label class="control-label">Select Officer</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="officer"  class="form-control">
                                        <option value="">Select Officer</option>
                                        @foreach($get_officers as $officers)
                                            <option value="{{$officers->id}}">{{$officers->name}}</option>
                                        @endforeach   
                                    </select>
                                    @if($errors->has('officer'))<p class="text-danger">{{ $errors->first('officer') }}</p> @endif
                                </div>                                
                            </div>   
                            <div class="form-group clearfix">
                                <div class="col-md-3">
                                    <label class="control-label">Select Services</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="services" class="form-control" required>
                                        <option value="">Select Services</option>
                                        <option value="Adhar Services Complaint"{{$user->services == "Adhar Services Complaint"?'selected':''}}>Adhar Services Complaint </option>
                                        <option value="Birth Certificate"{{$user->services == "Birth Certificate"?'selected':''}}>Birth Certificate</option>
                                        <option value="Death Certificate"{{$user->services == "Death Certificate"?'selected':''}}>Death Certificate</option>
                                    </select>
                                    @if($errors->has('services'))<p class="text-danger">{{ $errors->first('services') }}</p> @endif
                                </div>                                
                            </div> 
                            <div class="form-group clearfix">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Name', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::text('name', $user->name, ['class'=>'form-control','required','readonly']) !!}
                                    @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                                </div>                                
                            </div>                   
                            <div class="form-group clearfix">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Email', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::email('email', $user->email, ['class'=>'form-control','required','readonly']) !!}
                                    @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
                                </div>                                
                            </div>                   
                            <div class="form-group clearfix">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Phone', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::text('mobile', $user->mobile, ['class'=>'form-control','required','readonly']) !!}
                                    @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                                </div>                                
                            </div>                
                            <div class="form-group clearfix">
                                <div class="col-md-3">{!! Form::label('name', 'Visitor Adhar id', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                    {!! Form::Number('adhar_no', $user->adhar_no, ['class'=>'form-control','required','readonly']) !!}
                                    @if($errors->has('adhar_no'))<p class="text-danger">{{ $errors->first('adhar_no') }}</p> @endif
                                </div>                                
                            </div>  
                            <div class="form-group clearfix">
                                <div class="col-md-3">Gender</div>
                                <div class="col-md-9">
                                    {!! Form::select('gender', ['Male' => 'Male', 'Female' => 'Female'], $user->gender, ['class' => 'form-control','readonly']) !!}
                                    @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <div class="col-md-3">Is Active</div>
                                <div class="col-md-9">
                                    {!! Form::select('status', ['1' => 'Yes', '0' => 'No'], $user->status, ['class' => 'form-control']) !!}
                                    @if($errors->has('status'))<p class="text-danger">{{ $errors->first('status') }}</p> @endif
                                </div>
                            </div>
                             <div class="form-group clearfix">
                                <div class="col-md-3">{!! Form::label('name', 'Last Visit Date Time', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                     <input type="datetime-local" value="{{@$last_visit->date_time}}" class="form-control" id="visite_time">
                                </div>                                
                            </div> 
                             <div class="form-group clearfix">
                                <div class="col-md-3 text-danger">{!! Form::label('name', 'Re-Visit Date Time', ['class'=>'control-label']) !!}</div>
                                <div class="col-md-9">
                                     <input type="datetime-local" class="form-control" name="revisite_time" id="revisite_time">
                                    @if($errors->has('revisite_time'))<p class="text-danger">{{ $errors->first('revisite_time') }}</p> @endif
                                </div>                                
                            </div> 
                            
                            
                           <div class="form-group clearfix">
                                <div class="col-md-3 ">Image</div>
                                <div class="col-md-9">
                                    <img src="https://sspl20.com/vms/storage/app/public/{{$user->image?$user->image:'assets/img/doctor.jpg'}}" class="img-responsive pull-right" style="width: 150px;">
                                </div>
                            </div>
                            <div class="form-group clearfix">                              
                              <input type="hidden" name="type" value="update_user">
                              <button type="submit" class="btn btn-primary pull-right">Submit</button>
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
    
</script>
@endpush