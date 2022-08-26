@extends('admin.layout.master')

@section('title','Admin :: Edit Device')

@section('head')



<div class="page-head">

    <div class="row">

        <div class="col-md-4">

            <h3 class="m-b-less">

            Edit Device

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

            <h5 class="breadcrumbs-title">Edit Device</h5>

            <ol class="breadcrumb">

              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

              </li>

              <li><a>Device</a>

              </li>

            </ol>

          </div>

        </div>

      </div>

    </div>

    {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update', @$device->id], 'method'=>'put','id'=>'menuForm','class'=>'col s12']) !!}


			<div class="login-box col-md-4" style="padding: 20px;">

					<label for="country_id">Device Name: </label>
          <input type="text" name="device_name" value="{{$device->name}}">
          @if($errors->has('device_name'))<p class="text-danger">{{ $errors->first('device_name') }}</p> @endif

    </div>
	<div class="login-box col-md-4" style="padding: 20px;">

					<label for="country_id">Office Name: </label>
          <input type="text" name="office_name" value="{{$device->office_name}}">
          @if($errors->has('office_name'))<p class="text-danger">{{ $errors->first('office_name') }}</p> @endif

    </div>
    <div class="login-box col-md-4" style="padding: 20px;">
      <div>
        <label for="status">Status: </label>
        <select class="form-control" name="status">
          <option value="1" {{$device->status == 1 ? 'selected':''}}>Active</option>
          <option value="0" {{$device->status == 0 ? 'selected':''}}>DeActive</option>
        </select>
        @if($errors->has('status'))<p class="text-danger">{{ $errors->first('status') }}</p> @endif
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

@endpush
