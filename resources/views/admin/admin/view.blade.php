@extends('admin.layout.master')
@section('title','Profile')
@section('content')
<section id="content">
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title">Profile</h5>
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
    <!--start container-->
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <div class="row">
                        <table class="table">
                            <tr><td>Name</td> : <td>{{@$admin->name}}</td></tr>
                            <tr><td>Email</td> : <td>{{@$admin->email}}</td></tr>
                            <tr><td>Mobile</td> : <td>{{@$admin->mobile}}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end container-->
@endsection
