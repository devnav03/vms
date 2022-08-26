@extends('admin.layout.master')

@section('title','Categories: Category Detail')

@section('head')

<div class="page-head">

    <div class="row">

        <div class="col-md-6">

            <h3 class="m-b-less">

            View Category

            </h3>

        </div>

      

    </div>

</div>

@endsection

@section('content')

<div class="col-md-12">

    <table class="table">
        {{-- {{dd($category)}} --}}

        <tr>

            <td>Name</td>

            <td>:</td>

            <td>{{ $category->name }}</td>

        </tr>

        <tr>

            <td>Slug</td>

            <td>:</td>

            <td>{{ $category->description }}</td>

        </tr>

         <tr>

            <td>Status</td>

            <td>:</td>

            <td>{{ @$category->status == 1 ?'Active' :'Deactive' }}</td>

        </tr>

    </table>

</div>




@endsection

