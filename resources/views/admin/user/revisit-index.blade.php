@extends('admin.layout.master')
@section('title','Admin :: Revisit Visitor')
@push('links')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <style type="text/css">
        #results { border:1px solid; background:#ccc; }
    </style>  
@endpush
@section('content')
<section class="wrapper main-wrapper" >
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title">Revisit Visitor</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a href="#">{{ request()->segment(2)}}</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
     <div class="row" style="padding: 20px;">
        <div class="panel-body">
            <div class="col-md-12">
                {!! Form::open(['route'=>'check.visit','method'=>'GET','class'=>'validate cmxform','files'=>true]) !!}  
                <div class="form-group clearfix">
                    <div class="col-md-9">
                        {!! Form::text('name', @$_GET['name'], ['class'=>'form-control','required','placeholder'=>'Enter mobile or adhar no..']) !!}
                        @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                    </div> 
                    <div class="col-md-3">
                        <button class="btn btn-primary pull-right">Check Visitor</button>
                    </div>
                </div>  
                {!! Form::close() !!}
            </div>
        </div>
        <div class="col-md-12 text-center">
            @if(count($visit_list)>0)
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Visitor Id</th>
                        <th> Image</th>
                        <th> Name  </th>
                        <th>Added By </th>
                        <th>Officer Name </th>
                        <th> Email</th>
                        <th> Mobile No</th>
                        <th> Services</th>
                        <th> Status</th>
                        <th> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit_list as $appoint)
                    <tr>
                        <td>#{{$appoint->refer_code}}</td>
                        <td><img src="https://sspl20.com/vms/storage/app/public/{{$appoint->image?$appoint->image:'assets/img/doctor.jpg'}}" style="max-width: 100px; max-height: 100px"></td>
                        <td>{{$appoint->name}}</td>
                        <td>{{$appoint->parentDetail->name}}</td>
                        <td>{{$appoint->OfficerDetail->name}}</td>
                        <td>{{$appoint->email}}</td>
                        <td>{{$appoint->mobile}}</td>
                        <td>{{$appoint->services}}</td>
                        <td>{{$appoint->app_status}}</td>
                        <td><a href="{{route('add-revisit',$appoint->id)}}"><i class="fa fa-plus"></i> Revisit</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p> No appointment Available !!</p>
            @endif
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
@endpush
