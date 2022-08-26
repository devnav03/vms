{{-- {{dd($data)}} --}}

@extends('admin.layout.master')
@section('title','Grievance And Query')
@section('head')
<div class="page-head">
    <h3 class="m-b-less">
    Grievance And Query
    </h3>
</div>
@endsection
@section('content')
<div class="">
    <div class="col-md-12">
        {!! Form::open(['route'=>['admin.'.request()->segment(2).'.store']]) !!}
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="mobile">Subject</label>
                    {{ Form::text('subject', @$data->subject, ['class'=>'form-control', 'disabled']) }}
                </div>
            </div>
        </div>


        <div class="row">
            
            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">Type</label>
                    {{ Form::select('type', [1=>'Grievance', 2=>'Query'], @$data->type, ['class'=>'form-control', 'disabled']) }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">Name</label>
                    {{ Form::text('name', @$data->name, ['class'=>'form-control', 'disabled']) }}
                </div>
            </div>

            @if($data->type == 1)

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Refer Code</label>
                        <input type="text" name="refer_code" class="form-control" value="{{@$data->refer_code}}" disabled>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="mobile">Grievance Type</label>
                        {{ Form::select('grievance_type', [1=>'Grievance Redressal', 2=>'Complaint', 3=>'Feedback', 4=>'Suggestion'], @$data->grievance_type, ['class'=>'form-control', 'disabled']) }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="mobile">Grievance Nature</label>
                        {{ Form::select('grievance_nature',[1=>'Product & Quality', 2=>'Direct Seller', 3=>'Depots', 4=>'Bazaar & RSP', 5=>'Pickup Center', 6=>'Others'], @$data->grievance_nature ,['class'=>'form-control', 'disabled']) }}
                    </div>
                </div>

            @endif


            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">Mobile</label>
                    {{ Form::text('mobile', @$data->number,['class'=>'form-control', 'disabled']) }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">Email</label>
                    {{ Form::text('email', @$data->email,['class'=>'form-control', 'disabled']) }}
                </div>
            </div>     

            <div class="col-md-3">
                <div class="form-group">
                    <label for="mobile">Status</label>
                    {{ Form::select('status',[0=>'Pending', 1=>'Closed'], @$data->status, ['class'=>'form-control']) }}
                    <p class="text-danger">{{$errors->first('status')}}</p>
                </div>
            </div>       
            
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Description</label>
                     {{ Form::textarea('description',@$data->description,['class'=>'form-control', 'disabled']) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Remark</label>
                     {{ Form::textarea('remark',@$data->remark,['class'=>'form-control']) }}
                    <p class="text-danger">{{$errors->first('remark')}}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class=" col-md-12 form-group">
                <button type="submit" class="btn btn-info pull-right">Submit</button>
                <a href="{{adminRoute('index')}}" style=" margin-right: 14px;padding: 7px;width: 71px;background: #dcd7d7;" class="btn btn-default">Back</a>
            </div>
        </div>

            <input type="hidden" name="id" value="{{@$data->id}}">
        {!! Form::close() !!}
    </div>
</div>
@endsection