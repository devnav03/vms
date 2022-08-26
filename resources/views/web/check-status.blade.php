@extends('web.layouts.inner-master')
@section('content')
<style>
    label {
         color: #000;
    }
	@media (max-width: 701px){
.logo_image {
	display: inline-block;
    padding-top: 7px;
    margin-left: 14px;
    width: 34px;
	}
	.h1, h1 {
		font-size: 19px !important;
	}
	}
@media (max-width: 701px){
.navbar-inverse .navbar-toggle {
    border-color: #2e98c5 !important;
    margin-top: -78px !important;
    margin-bottom: 31px !important;
    position: absolute;
    top: 39px;
    margin-left: 288px;
	}}
</style>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav row col-lg-12" align="center">
        <li class="nav-item " ><a class="nav-link" href="{{route('web.home')}}">Home</a></li>
        <li class="nav-item  "><a class="nav-link" href="{{route('web.self-registration')}}">New Visit</a></li>
        <li class="nav-item  "><a class="nav-link" href="{{route('web.re-visit')}}">Re-Visit</a></li>
        <li class="nav-item active "><a class="nav-link" href="{{route('web.status')}}">Know Visit Status</a></li>
        <!-- <li class="nav-item  "><a class="nav-link" href="{{route('web.download')}}">Download Slip</a></li> -->
        <li class="nav-item "><a class="nav-link" href="{{route('web.qr-code')}}">Scan QR Code</a></li>
		    @auth
				  <li class="nav-item "><a class="nav-link" href="{{url('guard/dashboard')}}">Guard Dashboard</a></li>				  
				  <li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.logout')}}">Logout</a></li>			  
				  
				  @elseguest
					<li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.login')}}">Guard Login</a></li>
				  @endauth
        </ul>
    </div>
  </div>
</nav>
        <div id="content">
          <div class="heading_dtl">
             <span>Check Status</span>
          </div>
          <!-- <p>Please check your visit status.</p> -->
            <hr/>
            <center><h3>Vistor Status Details</h3></center>
            @foreach($datas as $no => $data)
            <div class="row">
                <div style="width: 16%; display: inline-block; padding: 15px;">
                    <div>
                        <label for="officer">S.No. : {{$no+1}}</label><br>
                        <label for="vaccine">Visitor Id : #{{@$data->refer_code}}</label>
                        <label for="patient">Status : @if(@$data->status =='0') <strong style="color:red;">Pending</strong> @elseif(@$data->status =='1') <strong style="color:green;">Approved</strong> @elseif(@$data->status =='2') <strong style="color:yellow;">Blocked</strong> @endif </label>
                    </div>
                </div>
                <div style="width: 18%; display: inline-block; padding: 15px;">
                    <div>
                        <label for="officer">Officer : {{@$data->OfficerDetail->name}}</label>
                        <label for="vaccine">Name : {{@$data->name}}</label>
                        <label for="states">Mobile : {{@$data->mobile}}</label>
                        <label for="states">Gender : {{@$data->gender}}</label>
                    </div>
                </div>
                <div style="width: 42%; display: inline-block; padding: 15px;">
                    <div>
                        <label for="patient">Purpose Of Visit : {{@$data->services}}</label></br>
                        <label for="symptoms">Email : {{@$data->email}}</label>
                        <label for="patient">Visitor Adhar Id / Voter Id / Pan Card Id : {{@$data->adhar_no}}</label>
                        <label for="patient">Visit Date Time : {{@$data->visite_time}}</label>
                    </div>
                </div>
                <div style="width: 10%; display: inline-block;">
                    <a href="{{route('generate.slip',Crypt::encryptString(@$data->id))}}" class="btn btn-primary" target="_blank">View Detail</a>
                </div>
            </div>
            <hr>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')

@endpush
