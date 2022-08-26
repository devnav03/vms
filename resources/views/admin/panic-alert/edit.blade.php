@extends('admin.layout.master')
@section('title','Admin :: Edit Emergency Contact')
@section('head')

<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Edit Emergency Contact
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
            <h5 class="breadcrumbs-title">Edit Emergency Contact</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a>Emergency Contact</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update', $menus->id], 'method'=>'put','id'=>'menuForm','class'=>'col s12']) !!}

        <div class="form-group" style="padding: 20px;">
            <label for="">Name</label>
            {!! Form::text('name', $menus->name , ['class'=>'form-control','required']) !!}
             <b class="text-danger">{{$errors->first('name')}}</b>
        </div>
        <div class="form-group" style="padding: 20px;">
            <div class="col-md-3">{!! Form::label('name', 'Email', ['class'=>'control-label']) !!}</div>
            <div class="col-md-9">
                {!! Form::email('email', $menus->email, ['class'=>'form-control','required']) !!}
                @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
            </div>                                
        </div>                   
        <div class="form-group" style="padding: 20px;">
            <div class="col-md-3">{!! Form::label('name', 'Phone', ['class'=>'control-label']) !!}</div>
            <div class="col-md-9">
                {!! Form::text('mobile', $menus->mobile, ['class'=>'form-control','required']) !!}
                @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
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
  });
</script>
@endpush
