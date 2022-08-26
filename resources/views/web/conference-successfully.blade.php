@extends('web.layouts.inner-master')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<style media="screen">
#content {
    padding: 0px 0px !important;
    margin: 0 20px;
    height: auto !important;
    /* height: 350px; */
    /* min-height: 350px; */
}
.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover
 {
    color: #ffffff;
    background-color: #2e98c5 !important;
  }

.logo_image {
    padding-top: 3px !important;
    margin-left: 105px;
    width: 34px;
}
	@media (max-width: 701px)
  {
.logo_image
 {
	display: inline-block;
    padding-top: 3px;
    margin-left: 14px;
    width: 34px;
	}
}
	.h1, h1
   {
		font-size: 19px !important;
	}
@media (max-width: 701px)
{
.navbar-inverse .navbar-toggle 
{
    border-color: #2e98c5 !important;
    margin-top: -78px !important;
    margin-bottom: 31px !important;
    position: absolute;
    top: 39px;
    margin-left: 288px;
	}
}
  @media only screen and (min-width: 768px)
  {
  /*.sidebar.pull-right.row
  {
    float: unset !important;
    margin-top: 70px !important;
  }*/
  .pull-right {
      float: left !important;
      margin-top: 70px !important;
      margin-left: 35px;

  }
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
                <li class="nav-item active " ><a class="nav-link" href="{{route('web.home')}}">Home</a></li>
                <li class="nav-item "><a class="nav-link" href="{{route('web.self-registration')}}">New Visit</a></li>
                <li class="nav-item "><a class="nav-link" href="{{route('web.re-visit')}}">Re-Visit</a></li>
                <li class="nav-item  "><a class="nav-link" href="{{route('web.status')}}">Know Visit Status</a></li>
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
        <div id="content" class="row">

            <div id="landing_page">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" align="center" style="margin-top: 50px;">
                        <img src="{{asset('/assets/images/succ_img.png')}}" class="img-fluid" style="width: 90px;">
                    </div>
                </div>

                <div  style="padding-top: 10px;">
                <div>
                <h1 style="text-align: center; font-size: 24px !important;">Successful</h1>
                <p style="text-align: center; font-size: 16px; margin-bottom: 30px;">You are successfully registered for conference meeting</p>
                </div>
                </div>
                <div class="clear"></div>

            </div>
        </div>
    </div>
@endsection
