@extends('admin.layout.master')
@section('title','Dashboard')

@section('content')
<section class="wrapper main-wrapper" style=''>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <div class="page-title">
            <div class="pull-left">
                <h1 class="title">Add Permission</h1> </div>
            <div class="pull-right hidden-xs">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Home</a>
                    </li>
                   
                    <li class="active">
                        <strong>Add Permission</strong>
                    </li>
                </ol>
                 <a href="{{ route('admin.menu.create') }}" class="btn btn-success btn-xs">Create Menu</a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
   <div class="row">
       <div class="col-md-10 col-md-offset-1">
           {!! Form::open(['route'=>['admin.permission.update',$role->id],'method'=>'patch']) !!}
                <table class="table" style="border:2px">
                    @foreach ($menus as $menu)
                        <tr>
                            <th>{{ $menu->title }}</th>
                            
                        </tr>   
                                <tr>
                                    <td><input type="checkbox" value="view" name="permission[{{ $menu->id }}][]">view</td>
                                    <td><input value="create" type="checkbox" name="permission[{{ $menu->id }}][]">create</td>
                                    <td><input value="edit" type="checkbox" name="permission[{{ $menu->id }}][]">edit</td>
                                    <td><input value="delete" type="checkbox" name="permission[{{ $menu->id }}][]">delete</td></tr>              
                    @endforeach
                </table>
                <button class="btn btn-success btn-sm">Update</button>
           {!! Form::close() !!}
       </div>
   </div>
   
</section>
@endsection
