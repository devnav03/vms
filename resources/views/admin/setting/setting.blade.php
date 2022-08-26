@extends('admin.layout.master')

@section('title','Admin :: Setting')

@push('links')

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

<style type="text/css">

    #results { border:1px solid; background:#ccc; }

</style>

@endpush

@section('content')

<section class="wrapper main-wrapper">

    <!--breadcrumbs start-->

    <div id="breadcrumbs-wrapper" class="grey lighten-3">

      <div class="container">

        <div class="row">

          <div class="col s12 m12 l12">

            <h5 class="breadcrumbs-title">Settings</h5>

            <ol class="breadcrumb">

              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

              </li>

              <li><a>Setting</a>

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

                            {!! Form::open(['route'=>['admin.setting.update',$settings->id],'method'=>'post','class'=>'validate cmxform','files'=>true]) !!}

                            <div class="form-group col s6">
                              <span>You want to send data in ams</span>
                  							<div class="form-group">
                  								<div class="input-outer">
                  									<small><i class="fa fa-map-marker" aria-hidden="true"></i></small>

                  									<select class="form-control" name="ams_send" required>
                                      <option value="" >Select Status</option>
                                      <option value="Active" {{$settings->status =="Active" ? "selected" : ""}}>Active</option>
                                      <option value="Pending" {{$settings->status =="Pending" ? "selected" : ""}}>DeActive</option>
                                    </select>
                  								</div>
                  							</div>
                                <div class="form-group clearfix" style="margin-top:25px;">

                                  <button class="btn btn-primary pull-right">Update</button>

                                  <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>

                              </div>

                            {!! Form::close() !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>
	</div>

</section>

@endsection

@push('scripts')
@endpush
