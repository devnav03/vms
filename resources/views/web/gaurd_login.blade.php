@extends('web.layouts.inner-master')
@section('content')
<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <a href="/"><img src="{!! asset('assets/images/home.png') !!}"></a>
            </div>
            <div class="col-md-11 seceen_two_right2">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h1>Enter your <br>login details</h1>
                        <br><br><br>
                        <img src="{!! asset('assets/images/personal.png') !!}">
                        <div class="bottm_text">Guard Login</div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-7">
                        
                        <form action="{{route('web.gaurd.validate')}}" method="POST">
						@csrf
                            <div class="form-container">
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-envelope icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input id="phone" type="text" name="email" class="validate" required="">
                                        <label for="phone">Enter your mail address</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-key icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input id="otp" type="password" name="password" required="" class="validate">
                                        <label for="otp">Type your password</label>
                                    </div>
                                </div>
                            </div>
                       
                      <!--   <div class="row">
                            <div class="col-md-12 text-end"><button type="button" class="bg-danger text-white">Forget password</button></div>
                        </div> -->
                        <br>
                        <br>
                        <br>
                        <div class="row pagination">
                            <div class="col-md-5"></div>
                            <ul class="col-md-7">
                                <li><a href="/"><i class="bi bi-arrow-left-circle"></i></a></li>
                                <li><button type="submit" class="sub_btn"><i style="color: #FA931A" class="bi bi-arrow-right-circle"></i></button></li>
                            </ul>
                        </div>
                         </form> 
                    </div>
                </div>
            </div>
        </div>
    </section>








<!--

<style media="screen">
  #content {
      padding: 0px 0px !important;
      margin: 0 20px 20px;
      height: auto !important;
      /* height: 350px; */
      /* min-height: 350px; */
  }
  #switch_btn {
    display: none;
  }
  @media  (max-width: 701px) {
  #switch_btn {
    display: block;
  }
	  
	  @media (max-width: 701px){
.logo_image {
	display: inline-block;
    padding-top: 3px;
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
  
}
  .navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {
      color: #ffffff;
      background-color: #2e98c5 !important;
  }
  
	@media (max-width: 701px)
	{
		.user_card 
		{
   		 width: 100% !important;
		}
	}
		.user_card {
			height: 400px;
			width: 350px;
			margin-top: auto;
			margin-bottom: auto;
			background: #2e98c5;
			position: relative;
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 10px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 5px;

		}
		.brand_logo_container {
			height: 110px;
			width: 113px;
			/* top: -75px; */
			border-radius: 50%;
			background: #60a3bc;
			padding: 5px;
			text-align: center;
		}
		.brand_logo {
			height: 97px;
			width: 97px;
			border-radius: 50%;
			border: 2px solid white;
		}
		.form_container {
			margin-top: 25px;
		}
		.login_btn {
			width: 100%;
			background: #0a384c !important;
			color: white !important;
		}
		.login_btn:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.login_container {
			padding: 0 2rem;
			margin-top: 18px;
		}
		.input-group-text {
			background: #c0392b !important;
			color: white !important;
			border: 0 !important;
			border-radius: 0.25rem 0 0 0.25rem !important;
		}
		.input_user,
		.input_pass:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
			background-color: #c0392b !important;
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
                <li class="nav-item  "><a class="nav-link" href="{{route('web.self-registration')}}">New Visit</a></li>
                <li class="nav-item  "><a class="nav-link" href="{{route('web.re-visit')}}">Re-Visit</a></li>
                <li class="nav-item  "><a class="nav-link" href="{{route('web.status')}}">Know Visit Status</a></li>
               <li class="nav-item "><a class="nav-link" href="{{route('web.download')}}">Download Slip</a></li> 
                <li class="nav-item  "><a class="nav-link" href="{{route('web.qr-code')}}">Scan QR Code</a></li>
				  @auth
				  <li class="nav-item"><a class="nav-link" href="{{url('guard/dashboard')}}">Guard Dashboard</a></li>				  
				  <li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.logout')}}">Logout</a></li>			  
				  
				  @elseguest 
					<li class="nav-item active"><a class="nav-link" href="{{route('web.gaurd.login')}}">Guard Login</a></li>
				  @endauth
                </ul>
            </div>
          </div>
        </nav>
        <div id="content" align="center">
            
            <div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="{{json_decode($company_data)->logo}}" class="brand_logo" alt="Logo"> 
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form action="{{route('web.gaurd.validate')}}" method="POST">
						@csrf
						<div class="input-group mb-3">
							<label>User Name:</label>
							<input type="text" name="email" class="form-control input_user" value="" placeholder="username">
						</div>
						<div class="input-group mb-3">
							<label>Password:</label>
							<input type="password" name="password" class="form-control input_pass" value="" placeholder="password">
						</div>
						<div class="d-flex justify-content-center mt-3 login_container">
							<input type="submit" name="button" class="btn login_btn" value="Login">
					   </div>
					</form>
				</div>
		
				{{--<div class="mt-4">
					<div class="d-flex justify-content-center links">
						Don't have an account? <a href="#" class="ml-2">Sign Up</a>
					</div>
					<div class="d-flex justify-content-center links">
						<a href="#">Forgot your password?</a>
					</div>
				</div>--}}
			</div>
		</div>
	</div>
           
        </div> -->

@endsection

@push('scripts')

@endpush
