@extends('admin.layout.master')
@section('title','Admin :: Repeated Visitor List')
@section('head')
<div class="page-head">
  <div class="row">
    <div class="col-md-4">
      <h3 class="m-b-less">
        Repeated Visitor Details
      </h3>
    </div>

  </div>
</div>
<style>

</style>
@endsection
@section('content')
<section id="content">
  <!--breadcrumbs start-->
  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
      <div class="row">
        <div class="col s12 m12 l12">
          <h5 class="breadcrumbs-title">Repeated Visitor Details</h5>
          <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
            </li>
            <li><a>Repeated Visitor</a>
            </li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="content">
<div class="row">
 
  @foreach($datas as $no => $data)
    <div class="repeat-visitor" style="padding: 2px 10px 5px 41px; background: #00bcd317; margin: 15px;"> 
    <div style="width: 16%; display: inline-block; padding: 15px;">
      <div>       
        <label for="officer">S.No. : {{$no+1}}</label><br>
        <label for="vaccine">Visitor Id : #{{$data->refer_code}}</label><br>
        <label for="patient">Status : @if($data->status =='0') <strong style="color:red;">Pending</strong> @elseif($data->status =='1') <strong style="color:green;">Approved</strong> @elseif($data->status =='2') <strong style="color:yellow;">Blocked</strong> @endif </label>
        <label for="officer">&nbsp;</label><br>
      </div>
    </div>
    <div style="width: 18%; display: inline-block; padding: 15px;">
      <div>
        <label for="officer">Officer : {{$data->OfficerDetail->name}}</label><br>
        <label for="vaccine">Name : {{$data->name}}</label><br>
        <label for="states">Mobile : {{$data->mobile}}</label><br>
        <label for="states">Gender : {{$data->gender}}</label><br>
      </div>
    </div>
    <div style="width: 42%; display: inline-block; padding: 15px;">
      <div>
        <label for="patient">Purpose Of Visit : {{$data->services}}</label><br>
        <label for="symptoms">Email : {{$data->email}}</label><br>
        <label for="patient">Visitor Adhar Id / Voter Id / Pan Card Id : {{$data->adhar_no}}</label><br>
        <label for="patient">Visit Date Time : {{$data->visite_time}}</label><br>
      </div>
    </div>    
  </div>
  @endforeach
  </div>
</div>
</div>
@endsection

@push('scripts')

@endpush