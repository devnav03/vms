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
  
}
  .navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {
      color: #ffffff;
      background-color: #2e98c5 !important;
  }
  #container {
      background: #fff;
      width: 65% !important;
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
                <!-- <li class="nav-item "><a class="nav-link" href="{{route('web.download')}}">Download Slip</a></li> -->
                <li class="nav-item active "><a class="nav-link" href="{{route('web.qr-code')}}">Scan QR Code</a></li>
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
            {{Form::open(['route'=>['web.slip.download'],  'method' => 'post', 'enctype'=>'multipart/form-data'])}}
            <div class="row">
                <!-- <h1></h1> -->
                <div class="heading_dtl">
                   <span>QR Scan</span>
                </div>
                <p>Please scan your qr code to download your visit Slip details.</p>
                <hr/>
                  <video id="preview" style="width:40%"></video>

              </div>
              <div class="row" style="margin-bottom:10px;" id="switch_btn">
                <div id="camera_button">
                 <a href="{{route('web.qr-code')}}"><button type="button" class="btn btn-primary" name="button">Front Camera</button></a>
                 <a href="{{route('web.qr-code_back')}}"><button type="button" class="btn btn-primary" name="button">Back Camera</button></a>
                </div>
              </div>

            {{Form::close()}}
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
          // alert(content);
          location.href=content;
        });
        Instascan.Camera.getCameras().then(function (cameras) {
          if (cameras.length > 0) {
            scanner.start(cameras[0]);
          } else {
            alert('No cameras found.');
          }
        }).catch(function (e) {
          alert('No cameras found.');
        });
    </script>
@endpush
