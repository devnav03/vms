@extends('admin.layout.master')
@section('title','Dashboard')

@section('content')
<section class="wrapper main-wrapper" style=''>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <div class="page-title">
            <div class="pull-left">
                <h1 class="title">View Role</h1> </div>
            <div class="pull-right hidden-xs">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Home</a>
                    </li>
                   
                    <li class="active">
                        <strong>View Role</strong>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
   
   
</section>
@endsection
