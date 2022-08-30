@extends('web.layouts.inner-master')
@section('content')

<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="assets/img/icons/home.png">
            </div>
            <div class="col-md-11 seceen_two_right2">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h1>OTP</h1>
                        <div class="gap"></div>
                        <div class="bottm_text">One Time password</div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-7">
                        
                        <form>
                            <div class="form-container">
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-tablet icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input id="phone" type="text" class="validate">
                                        <label for="phone" class="">Enter your mobile no</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-pin-map icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input id="otp" type="text" class="validate">
                                        <label for="otp" class="">Enter OTP received</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12 otp_text mb-4">Enter the OTP that was received on your mobile device.</div>
                            <div class="col-md-12 text-end"><button type="button" class="bg-danger text-white">Resend OTP</button></div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="row pagination">
                            <div class="col-md-5"></div>
                            <ul class="col-md-7">
                                <li><a href="#"><i class="bi bi-arrow-left-circle"></i></a></li>
                                <li><a href="#" style="color: #FA931A"><i class="bi bi-arrow-right-circle"></i></a></li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>


<style media="screen">
  #content {
      padding: 0px 0px !important;
    margin: 0 20px;
    height: auto !important;
    /* height: 350px; */
    /* min-height: 350px; */
  }
  .navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {
      color: #ffffff;
      background-color: #2e98c5 !important;
  }
  #container {
      background: #fff;
      width: 65% !important;
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
				  <li class="nav-item"><a class="nav-link" href="{{url('guard/dashboard')}}">Guard Dashboard</a></li>				  
				  <li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.logout')}}">Logout</a></li>			  
				  
				  @elseguest
					<li class="nav-item"><a class="nav-link" href="{{route('web.gaurd.login')}}">Guard Login</a></li>
				  @endauth
                </ul>
            </div>
          </div>
        </nav>

        <div id="content" align="center">
            {{Form::open(['route'=>['web.check.status'],  'method' => 'post', 'enctype'=>'multipart/form-data'])}}
            <div class="row" id="reg">
              <div class="heading_dtl">
                 <span>Know Visit Status</span>
              </div>
                <!-- <h1></h1> -->
                <p>Please provide your mobile number to access your visit status details.</p>
                <hr/>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <div>
                        <label for="mobile">Mobile Number:<span class="text-danger">*</span></label>
                        <input placeholder="Enter your register mobile number" type="text" name="mobile" size="30" value="{{old('mobile')}}" class="nowarn form-control" maxlength="10">
                    </div>
                </div>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <!-- <p>If this is your first time contacting us or you've lost the ticket number, please <a href="{{route('web.self-registration')}}"> open a new visit </a></p> -->
                    <p style="text-align: -webkit-center;">
                        <input  type="submit" value="Submit" class="btn btn-primary" style="width: 70px; margin-top: 24px;">
                    </p>
                </div>
            </div>

          <div id="otp_check">
            <div class="row">
              <div class="heading_dtl">
                 <span>Otp Verify</span>
              </div>
                <p>Please verify your otp to check your status.</p>
                <p class="text-success text-center">{{\Session::get('message')}}</p>
                <hr/>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <div>
                        <label for="otp">Enter otp:<span class="text-danger">*</span></label>
                        <input type="text" class="nowarn form-control" value="{{old('otp')}}" name="otp" placeholder="Enter your otp" maxlength="6">
                        <small class="text-danger">{{$errors->first('otp')}}</small>
                    </div>
                </div>
                <!-- <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <p>If this is your first time contacting us or you've lost the ticket number, please <a href="{{route('web.self-registration')}}"> open a new visit </a></p>
                </div> -->
              <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <p style="text-align: -webkit-center;">
                        <input  type="submit" value="Submit" class="btn btn-primary" style="width: 70px; margin-top: 24px;">
                    </p>
                  </div>

                </div>
            </div>


          {{Form::close()}}
      </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#otp_check').hide();
        @if(session()->has('otp_true'))
            $('#reg').hide();
            $('#otp_check').show();
        @endif
    </script>
@endpush
