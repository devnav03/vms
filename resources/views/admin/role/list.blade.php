@extends('admin.layout.master')

@section('title','Admin :: Role List')

@section('content')

    <!-- START CONTENT -->

      <section id="content">

        <!--breadcrumbs start-->

        <div id="breadcrumbs-wrapper" class=" grey lighten-3">

          <div class="container">

            <div class="row">

              <div class="col s12 m12 l12">

                <h5 class="breadcrumbs-title">Roles List</h5>

                <ol class="breadcrumb">

                  <li><a href="{{route('web.home')}}">Dashboard</a>

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

            <!-- <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add New</a> -->

           @can('add')

                <!-- <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add New</a> -->

            @endcan 

          {{-- <p class="caption">Collections allow you to group list objects together.</p> --}}

          <div class="divider"></div>

          <!--Basic Collections-->

          <div id="basic-collections" class="section">

            <!--Avatar Content-->

            <div class="row">

              <div class="col s12 m8 l9">

                <ul class="collection">

                @foreach ($roles as $role)

                  <li class="collection-item avatar">

                    <img src="{{asset('admin-asset/images/avatar.jpg')}}" alt="" class="circle">

                    <span class="title">{{$role->name}}</span>

                    @can('read')

                        <a href="{{ route('admin.'.request()->segment(2).'.show',$role->id) }}" title="view" class="secondary-content" style="padding-right: 35px;color: #a978d1;"><i class="mdi-image-remove-red-eye"></i></a>

                    @endcan

                     <a href="{{ route('admin.'.request()->segment(2).'.edit',$role->id) }}" title="edit" class="secondary-content"><button class="btn-success">Assign Permission</button></a>

                  </li> 

                @endforeach                 

                </ul>

              </div>

            </div>

          </div>

        </div>

        <!--end container-->

      </section>

      <!-- END CONTENT -->

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

        Role List

    </h3>

    <div class="state-information">

        @can('add')

            <!-- <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}">Add Role</a> -->

        @endcan

    </div>

</div>

@endsection

@section('content')

<div class="clearfix"></div>

    <div class="row">

        <div class="col-md-4">

            <table class="table table-bordered" style="background: #fff">

                <tr>

                    <td>Name</td>

                    <td>Action</td>

                </tr>

                @foreach ($roles as $role)

                    <tr>

                        <td>{{$role->name}}</td>

                        <td>

                            {{-- @can('read') --}}

                                <a href="{{ route('admin.'.request()->segment(2).'.show',$role->id) }}" title="view" class="btn btn-primary btn-xs"><i  class="fa fa-eye"></i></a>

                            {{-- @endcan --}}

                            {{-- @can('edit') --}}

                                <a href="{{ route('admin.'.request()->segment(2).'.edit',$role->id) }}" title="e" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>

                            {{-- @endcan --}}

                        </td>

                    </tr>

                @endforeach

            </table>

        </div>

    </div>

@endsection

