@extends('admin.layout.master')
@section('title','Edit Menu')
@section('content')
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Edit Menu</h5>
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
        <!--breadcrumbs end-->
         <!--start container-->
        <div class="container">
            <div class="row">
                <div class="col s6 offset-s3 ">
                    <div class="card-panel">
                      <h4 class="header2">Update Menu</h4>
                      <div class="row">
                         {!! Form::open(['route'=>['admin.menus.update', $menu->id], 'method'=>'put','id'=>'menuForm','class'=>'col s12']) !!}
                          <div class="row">
                            <div class="input-field col s12">
                               {!! Form::text('menu_name', $menu->title, ['class'=>'form-control']) !!}
                                {!! Form::label('menu_name', 'Menu Name', ['class'=>'control-label']) !!}
                                <b class="text-danger">{{$errors->first('menu_name')}}</b>
                            </div>
                          </div>
                          <div class="row">
                            <div class="input-field col s12">
                              {!! Form::label('icon', 'Icon', ['class'=>'control-label']) !!}
                                {!! Form::text('icon', $menu->icon, ['class'=>'form-control']) !!}
                                <b class="text-danger">{{$errors->first('icon')}}</b>
                            </div>
                          </div>
                          <div class="row">
                            <div class="input-field col s12">
                                 {!! Form::label('status', 'Status', ['class'=>'control-label','style'=>'top: -1.1rem']) !!}
                                {!! Form::select('status', array(1 => 'Active', '0' => 'Deactive'), $menu->status, array('class' => 'form-control')); !!}
                                <b class="text-danger">{{$errors->first('status')}}</b>
                            </div>
                          </div>
                          <div class="row">                            
                            <div class="row">
                              <div class="input-field col s12">
                                <input type="hidden" name="id" value="{{ $menu->id }}" />
                                <button type="submit" onclick="submitForm()" class="btn waves-effect waves-light">Update <i class="mdi-content-send right"></i></button>
                                <a class="btn cyan waves-effect" id="back">Back</a>
                              </div>
                            </div>
                          </div>
                        {!! Form::close() !!}
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <!--end container-->
    </section>
        <!-- START FOOTER -->
    <footer class="page-footer">    
        <div class="footer-copyright">
            <div class="container"> Copyright © 2021.  All rights reserved.
            </div>
        </div>
    </footer>
    <!-- END FOOTER -->
@endsection        


@push('scripts')
<script type="text/javascript">
$("#back").click(function(){
    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
  });
</script>
@endpush