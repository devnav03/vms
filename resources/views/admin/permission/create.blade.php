@extends('admin.layout.master')
@section('title','Admin :: Role List')

@section('content')
<section class="wrapper main-wrapper" style=''>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <div class="page-title">
            <div class="pull-left">
                <h1 class="title">Create New Role</h1> </div>
            <div class="pull-right hidden-xs">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Home</a>
                    </li>
                   
                    <li class="active">
                        <strong>Create New Role</strong>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                
                    <div class="panel-heading">
                        <strong>Create New Role</strong>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(['route'=>'admin.role.store']) !!}
                            <div class="form-group">
                                {!! Form::label('role_name', '', ['class'=>'control-label']) !!}
                                {!! Form::text('role_name', null, ['class'=>'form-control']) !!}
                                @if ($errors->has('role_name'))
                                    <b class="text-danger">{{$errors->first('role_name
                                    ')}}</b>
                                @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success pull-right">Create</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                   
                
            </div>
        </div>
    </div>
   
</section>
@endsection
