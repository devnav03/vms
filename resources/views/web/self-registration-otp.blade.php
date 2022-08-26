@extends('web.layouts.inner-master')
@section('content')
<style>
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

  #loader-p{

    z-index:999999;
    display:block;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:url(https://vztor.in/superadmin/public/assets/images/loading_image.gif) 50% 50% no-repeat;
    }
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
        <li class="nav-item active "><a class="nav-link" href="{{route('web.self-registration')}}">New Visit</a></li>
        <li class="nav-item  "><a class="nav-link" href="{{route('web.re-visit')}}">Re-Visit</a></li>
        <li class="nav-item  "><a class="nav-link" href="{{route('web.status')}}">Know Visit Status</a></li>
        <!-- <li class="nav-item  "><a class="nav-link" href="{{route('web.download')}}">Download Slip</a></li> -->
        <li class="nav-item "><a class="nav-link" href="{{route('web.qr-code')}}">Scan QR Code</a></li>
		    @auth
				  <li class="nav-item"><a class="nav-link" href="{{url('guard/dashboard')}}">Guard Dashboard</a></li>				  
				  <li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.logout')}}">Logout</a></li>			  
				  
				  @elseguest
					<li class="nav-item"><a class="nav-link" href="{{route('web.gaurd.login')}}">Guard Login</a></li>
				  @endauth
        </ul>
    </div>
  </div>
</nav>

        <div id="content">
          <div id="loader-p"></div>
            {{Form::open(['route'=>['add.self.registration.success'],  'method' => 'post', 'enctype'=>'multipart/form-data','id'=>'submitForm'])}}
                <div id="otp_check">
                  <div class="" align="center">
                    <div class="heading_dtl">
                       <span>Otp Verify</span>
                    </div>

                      <p>Please verify your otp to complete registration process.</p>
                      <p class="text-success text-center" style="font-size: 20px;">{{@$message}}</p>
                  </div>

                    <hr/>
                    <div class="login-box col-md-6 col-sm-12 col-xs-12">
                        <div>
                            <input type="hidden" name="all_old_data" value="{{$all_data}}">
                            <label for="otp">Enter otp:</label>
                              <input type="text" class="form-control nowarn" value="{{old('otp')}}" name="otp" placeholder="Enter your otp" maxlength="6" required>
                            <small class="text-danger">{{$errors->first('otp')}}</small>
                        </div>
                    </div>
                </div>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                  <input type="submit" value="Submit" id="submitForm" style="width: 60px;  margin-top: 24px;"">
                  <input type="reset" name="reset" value="Reset" style="width: 60px; margin-top: 24px;"">
                </div>

            {{Form::close()}}
        </div>
    </div>

@endsection

@push('scripts')
    <script language="JavaScript">
      $("#loader-p").hide();
     $("#submitForm").on("submit", function(){
      $("#loader-p").show();
    });//submit
        $('#otp_check').show();
    </script>
@endpush
