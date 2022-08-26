@extends('admin.layout.master')


@section('head')
<!-- page head start-->
<div class="page-head">
    <h3>
    Dashboard
    </h3>
    <span class="sub-title">VMS Dashboard</span>
</div>
<!-- page head end-->
<!-- page head end-->
<style>
    :root {
        --blue-jeans: 71, 135, 222;
        --aqua: 50, 173, 217;
        --mint: 47, 186, 153;
        --grass: 142, 192, 74;
        --sunflower: 247, 188, 50;
        --bittersweet: 238, 85, 59;
        --grapefruit: 223, 66, 83;
        --lavender: 149, 118, 221;
        --pink-rose: 215, 111, 173;
        --light: 255, 255, 255;
        --dark: 0, 0, 0;
    }

    .box-1 {
        background: linear-gradient(to right, rgb(var(--blue-jeans)), rgba(var(--blue-jeans), 0.7));
    }

    .box-2 {
        background: linear-gradient(to right, rgb(var(--aqua)), rgba(var(--aqua), 0.7));
    }

    .box-3 {
        background: linear-gradient(to right, rgb(var(--mint)), rgba(var(--mint), 0.7));
    }

    .box-4 {
        background: linear-gradient(to right, rgb(var(--grass)), rgba(var(--grass), 0.7));
    }

    .box-5 {
        background: linear-gradient(to right, rgb(var(--sunflower)), rgba(var(--sunflower), 0.7));
    }

    .box-6 {
        background: linear-gradient(to right, rgb(var(--bittersweet)), rgba(var(--bittersweet), 0.7));
    }

    .box-7 {
        background: linear-gradient(to right, rgb(var(--grapefruit)), rgba(var(--grapefruit), 0.7));
    }

    .box-8 {
        background: linear-gradient(to right, rgb(var(--lavender)), rgba(var(--lavender), 0.7));
    }

    .box-9 {
        background: linear-gradient(to right, rgb(var(--pink-rose)), rgba(var(--pink-rose), 0.7));
    }

    .box-wrapper {
        border-radius: 5px;
        margin-bottom: 30px;
    }

    .box-wrapper .box-upper {
        position: relative;
    }

    .box-wrapper .box-upper .box-icon {
        position: absolute;
        font-size: 30px;
        top: 50%;
        right: 10px;
        color: rgb(var(--light));
        transform: translateY(-50%);
    }

    .box-wrapper .box-upper,
    .box-wrapper .box-lower {
        padding: 15px;
    }

    .box-wrapper .box-lower {
        border-top: 1px solid rgb(var(--light));
    }

    .box-wrapper .box-inner h4{
        font-size: 25px;
    }
    
    .box-wrapper .box-inner h4,
    .box-wrapper .box-inner p {
        color: rgb(var(--light));
    }

    .box-wrapper .box-inner p {
        margin: 0;
    }
    
    .table{
        border-color: rgba(var(--dark), 0.15);
        margin-bottom: 30px;
    }
    
    .table tbody tr td,
    .table thead tr th{
        border-color: rgba(var(--dark), 0.15);
        font-size: 14px;
    }
    
    .table tbody tr:nth-child(even){
        background: rgba(var(--mint), 0.12);
    }

</style>


<div class="container-fluid">
    
       @if(auth('admin')->user()->role_id ==5)
    <div class="row" style="padding-top: 20px;">
        <div class="col-md-4 col-sm-6 col-xs-12 col-sm-offset-2">
            <div class="box-wrapper box-3" style="width: 56%;">
                <a href="{{url('admin-panel/users/create')}}">
                    <div class="box-inner">
                        <div class="box-upper" style="text-align: center;">
                            <h4>Add New Visitor </h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="box-wrapper box-6" style="width: 56%;">
                <a href="{{route('user-revisit')}}">
                <div class="box-inner">
                    <div class="box-upper" style="text-align: center;">
                        <h4>Revisit </h4>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <div class="box-inner">
                    <div class="box-upper" style="text-align: center;">
                        <h3>Latest Appointment list</h3>
                    </div>
                </div>
                @if(count($appointments)>0)
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Visitor Id</th>
                            <th> Name  </th>
                            <th>Added By </th>
                            <th>Officer Name </th>
                            <th>Services</th>
                            <th> Email</th>
                            <th> Mobile No</th>
                            <th> Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appoint)
                        <tr>
                            <td>#{{$appoint->refer_code}}</td>
                            <td>{{$appoint->name}}</td>
                            <td>{{$appoint->parentDetail->name}}</td>
                            <td>{{$appoint->OfficerDetail->name}}</td>
                            <td>{{$appoint->services}}</td>
                            <td>{{$appoint->email}}</td>
                            <td>{{$appoint->mobile}}</td>
                            <td>{{$appoint->app_status}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p> No appointment Available !!</p>
                @endif
            </div>
        </div>
    </div>
    
</div>

@endsection
@section('content')
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
@endpush