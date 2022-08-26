@extends('admin.layout.master')
@section('title','Admin :: Add Role')
@section('content')
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Add Role</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Roles</a>
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
                <div class="col s12 m12 l12">
                  <div class="card-panel">
                    <div class="row">
                    {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','class'=>'col s12']) !!}
                      <h4 class="header2">Add Role</h4>
                      <div class="row">
                        <div class="input-field col s12">
                            <i class="mdi-action-account-circle prefix active"></i>
                            {!! Form::label('role_name', '', ['for'=>'icon_prefix','class'=>'active']) !!}
                            {!! Form::text('role_name', null, ['id'=>'icon_prefix2','class'=>'validate form-control']) !!}
                            @if ($errors->has('role_name'))
                                <b class="text-danger">{{$errors->first('role_name
                                ')}}</b>
                            @endif
                        </div>
                        <div class="input-field">
                          <div class="input-field col s12">
                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="action"><i class="mdi-action-perm-identity"></i> Create</button>
                            <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
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