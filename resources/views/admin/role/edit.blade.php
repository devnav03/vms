@extends('admin.layout.master')

@section('title','Edit Role')

@section('content')

    <section id="content">

        <!--breadcrumbs start-->

        <div id="breadcrumbs-wrapper" class=" grey lighten-3">

          <div class="container">

            <div class="row">

              <div class="col s12 m12 l12">

                <h5 class="breadcrumbs-title">Edit Roles</h5>

                <ol class="breadcrumb">

                  <li><a href="{{route('web.home')}}">Dashboard</a>

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

            @can('add')

                <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right;    margin-top: 10px;">Create</a>

            @endcan


          <div class="divider"></div>

          <!--Basic Collections-->

          <div id="basic-collections" class="section">

            <div class="row">

            {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update',$role_id],'method'=>'put']) !!}

            <input type="hidden" name="_method" value="PUT">

            <!-- <legend>Edit your Detail </legend> -->

            <div class="row">

                <div class="col s12 m12 l6">

                    <div class="form-group">

                        <label for="permission"></label><br>

                        <button class="permission-select-all btn-floating waves-effect waves-light green"><i class="mdi-navigation-check"></i></button>

                        <button class="permission-deselect-all btn-floating waves-effect waves-light red"><i class="mdi-content-clear"></i></button>

                    </div>

                </div>

                <div class="col s12 m12 l6">

                    <div class="form-group">

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </div>

                </div>

            </div>

   

            @foreach($permissions as $table => $permission)

            <div class="col s12 m12 l4">

                <ul class="permissions checkbox list-unstyled">

                    <li>

                        <input type="checkbox" class="permission-group">

                        <label for="{{$table}}"><strong>{{ title_case(str_replace('_',' ', $table)) }} </strong></label>

                        <ul class="list-unstyled" style="margin-left: 20px">

                            @foreach($permission as $perm)

                            <li>

                                <input type="checkbox" id="permission-{{ $perm->id }}" name="permissions[]" class="filled-in the-permission" {{ (in_array($perm->id, array_flatten($role_permission->toArray())))? 'checked' :'' }} value="{{ $perm->id }}" >

                                <label for="permission-{{ $perm->id }}">{{ title_case(str_replace('_',' ', $perm->key)) }}</label>

                            </li>

                            @endforeach

                        </ul>

                    </li>

                </ul>

            </div>

            @endforeach

            @if(auth('admin')->user()->role_id == 1)

                <input type="hidden"  name="permissions[]" value="53" >

                <input type="hidden"  name="permissions[]" value="54" >

                <input type="hidden"  name="permissions[]" value="55" >

                <input type="hidden"  name="permissions[]" value="56" >

                <input type="hidden"  name="permissions[]" value="57" >

            @endif

            {!! Form::close() !!}

           

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







@section('head')

<div class="page-head">

    <h3 class="m-b-less">

    Edit Role

    </h3>

    <div class="state-information">

        @can('add')

        <a class="btn btn-primary btn-sm" href="{{ adminRoute('create') }}">Create</a>

        @endcan

    </div>

</div>

@endsection

@section('content')

<div class="clearfix"></div>

<div class="col-lg-12" style="padding-bottom: 60px">

    {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update',auth('admin')->user()->role_id],'method'=>'put']) !!}

    <input type="hidden" name="_method" value="PUT">

    <!-- <legend>Edit your Detail </legend> -->

    <div class="row">

        <div class="col-md-6">

            <div class="form-group">

                <label for="permission"></label><br>

                <button class="permission-select-all btn btn-success btn-xs "><i class="fa fa-check"></i></button>

                <button class="permission-deselect-all btn btn-danger btn-xs"><i class="fa fa-times"></i></button>

            </div>

        </div>

        <div class="col-md-6">

            <div class="form-group">

                <button type="submit" class="btn btn-primary">Submit</button>

            </div>

        </div>

    </div>

   
    @foreach($permissions as $table => $permission)

    <div class="col-md-4">

        <ul class="permissions checkbox list-unstyled">

            <li>

                <input type="checkbox" class="permission-group">

                <label for="{{$table}}"><strong>{{ title_case(str_replace('_',' ', $table)) }} </strong></label>

                <ul class="list-unstyled" style="margin-left: 20px">

                    @foreach($permission as $perm)

                    <li>

                        <input type="checkbox" id="permission-{{ $perm->id }}" name="permissions[]" class="the-permission" {{ (in_array($perm->id, array_flatten($role_permission->toArray())))? 'checked' :'' }} value="{{ $perm->id }}" >

                        <label for="permission-{{ $perm->id }}">{{ title_case(str_replace('_',' ', $perm->key)) }}</label>

                    </li>

                    @endforeach

                </ul>

            </li>

        </ul>

    </div>

    @endforeach

    @if(auth('admin')->user()->role_id == 1)

        <input type="hidden"  name="permissions[]" value="53" >

        <input type="hidden"  name="permissions[]" value="54" >

        <input type="hidden"  name="permissions[]" value="55" >

        <input type="hidden"  name="permissions[]" value="56" >

        <input type="hidden"  name="permissions[]" value="57" >

    @endif

    {!! Form::close() !!}

</div>

@endsection

@push('scripts')

<script>

$('document').ready(function() {

    $('.toggleswitch').toggle();

    $('.permission-group').on('change', function() {

        $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);

    });

    $('.permission-select-all').on('click', function() {

        $('ul.permissions').find("input[type='checkbox']").prop('checked', true);

        return false;

    });

    $('.permission-deselect-all').on('click', function() {

        $('ul.permissions').find("input[type='checkbox']").prop('checked', false);

        return false;

    });



    function parentChecked() {

        $('.permission-group').each(function() {

            var allChecked = true;

            $(this).siblings('ul').find("input[type='checkbox']").each(function() {

                if (!this.checked) allChecked = false;

            });

            $(this).prop('checked', allChecked);

        });

    }

    parentChecked();

    $('.the-permission').on('change', function() {

        parentChecked();

    });

});

</script>

@endpush