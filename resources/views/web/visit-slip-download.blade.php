@extends('web.layouts.inner-master')
@section('content')
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
                <li class="nav-item  "><a class="nav-link" href="{{route('web.status')}}">Know Visit Status</a></li>
                <!-- <li class="nav-item active "><a class="nav-link" href="{{route('web.download')}}">Download Slip</a></li> -->
                <li class="nav-item "><a class="nav-link" href="{{route('web.qr-code')}}">Scan QR Code</a></li>
				    @auth
				  <li class="nav-item"><a class="nav-link" href="{{url('guard/dashboard')}}">Guard Dashboard</a></li>				  
				  <li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.logout')}}">Logout</a></li>			  
				  
				  @elseauth
					<li class="nav-item"><a class="nav-link" href="{{route('web.gaurd.login')}}">Guard Login</a></li>
				  @endauth
                </ul>
            </div>
          </div>
        </nav>
        <div id="content" align="center">
            {{Form::open(['route'=>['web.slip.download'],  'method' => 'post', 'enctype'=>'multipart/form-data'])}}
            <div class="row">
                <!-- <h1></h1> -->
                <div class="heading_dtl">
                   <span>Slip Download</span>
                </div>
                <p>Please provide your visiter Id to download your visit Slip details.</p>
                <hr/>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <div>
                        <p class="text-success text-center" style="font-size: 20px;">{{@$message}}</p>
                        <label for="mobile">Visitor Id:</label>
                        <input placeholder="Enter your Visitor id" type="text" name="visiter_id" size="30" value="{{old('visiter_id')}}" class="nowarn form-control" required>
                    </div>
                </div>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <!-- <p>If this is your first time contacting us or you've lost the ticket number, please <a href="{{route('web.self-registration')}}"> open a new visit </a></p> -->
                    <input class="btn btn-primary" type="submit" value="Submit" style="width: 70px; margin-top: 24px;">
                </div>
              </div>
            <div class="row">
              <div class="login-box col-md-4 col-sm-12 col-xs-12">
                <p style="text-align: -webkit-center;">
                    <!-- <input class="btn btn-primary" type="submit" value="Submit" style="width: 70px;"> -->
                </p>
            </div>
          </div>
            {{Form::close()}}
        </div>
    </div>
@endsection
