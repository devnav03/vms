@extends('admin.layout.master')
@section('title','Admin :: Create Bread')
@section('head')

<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Add Bread
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
            <h5 class="breadcrumbs-title">Add Bread</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a>Bread</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    {!! Form::open(['route'=>'admin.breads.store']) !!}
        <div class="form-group" style="padding: 20px;">
            <label for="">Name</label>
            {!! Form::text('name', '' , ['class'=>'form-control']) !!}
             <b class="text-danger">{{$errors->first('name')}}</b>
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
