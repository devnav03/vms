@extends('admin.layout.master')

@section('title','Admin :: Create Building')

@section('head')



<div class="page-head">

    <div class="row">

        <div class="col-md-4">

            <h3 class="m-b-less">

            Add Building

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

            <h5 class="breadcrumbs-title">Add Building</h5>

            <ol class="breadcrumb">

              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

              </li>

              <li><a>Building</a>

              </li>

            </ol>

          </div>

        </div>

      </div>

    </div>

    {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store']) !!}
		<div class="form-group" style="padding: 20px;">
			<div>
				<label for="country_id">Select Location: </label>
				<select name="location_id" id="location_id" class="form-control nowarn" required>
				  <option value="{{old('location_id')}}">Select Location name</option>
				  @foreach($locations as $location)
				  <option value="{{$location->id}}">{{$location->name}}</option>
				  @endforeach
				</select>
				@if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
			</div>
		</div>

        <div class="form-group" style="padding: 20px;">

            <label for="">Name</label>

            {!! Form::text('name', '' , ['class'=>'form-control']) !!}

             <b class="text-danger">{{$errors->first('name')}}</b>

        </div>
		<div class="form-group" style="padding: 20px;">
			<div>
				<label for="country_id">Select Status: </label>
				<select name="status_id" id="status_id" class="form-control nowarn" required>
				 <option value="1">Active</option>
				 <option value="0">DeActive</option>

				</select>
				@if($errors->has('status_id'))<p class="text-danger">{{ $errors->first('status_id') }}</p> @endif
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

 $("#back").click(function(){

    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"

  });

</script>

@endpush
