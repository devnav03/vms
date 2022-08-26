@extends('web.layouts.inner-master')
@section('content')
<style type="text/css">
  #loader-p{

   z-index:999999;
   display:block;
   position:fixed;
   top:0;
   left:0;
   width:100%;
   height:100%;
   background:url(https://sspl20.com/vivek/vms/public/loading-image.gif) 50% 50% no-repeat;
  }
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
    #results { border:1px solid; background:#ccc; }
  .show{
    display: block;
    position: absolute;
  }
  .show2{
    display: block;
  }
  .hide{
      display: none;
  }
  #loader-p{
   z-index:999999;
   display:block;
   position:fixed;
   top:0;
   left:0;
   width:100%;
   height:100%;
   background:url(https://sspl20.com/vivek/vms/public/loading-image.gif) 50% 50% no-repeat;
  }

  .stepwizard-step p {
      margin-top: 0px;
      color:#666;
  }
  .stepwizard-row {
      display: table-row;
  }
  .stepwizard {
      display: table;
      width: 93%;
      position: relative;
      background-color:#ffffff;
      height: 0px;
      margin-top: 25px;
  }
  .stepwizard-step button[disabled] {
      /* opacity: 1 !important;
      filter: alpha(opacity=100) !important; */
  }
  .stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
      opacity:1 !important;
      color:#000000;
  }
  .stepwizard-row:before {
    top: 23px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 17px;
    background-color: #ccc;
    z-index: 1;
    margin-left:25px;
  }
  .stepwizard-row:after {
      top: 23px;
      bottom: 0;
      position: absolute;
      content: " ";
      width:var(--width, 0%);
      height: 17px;
      background-color: #69d400;
      color: #ffffff;
      z-index: 2;
      margin-left: -96%;
  }
  .stepwizard-step {
      display: table-cell;
      text-align: center;
      position: relative;
      z-index: 99;
  }
  .btn-circle {
    width: 58px;
    height: 57px;
    text-align: center;
    padding: 13px 0;
    font-size: 22px;
    line-height: 1.428571429;
    border-radius: 29px;
    font-weight: bold;
    z-index: 10;
    background-color: #d0d0d0;
    color: #ffffff;
  }
  .done{
    background-color: #69d400;
    z-index: 0;
  }
  .btn:hover, .btn-large:hover {
      background-color: #00bcd4 !important;
  }
  .text-danger{
    color: #fe0000;
  }
  .col-xs-3{
    width:16%!important;
  }

  .pull-right {
    float: right;
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
	}

	
	}
</style>


  <div class="row" style="margin-top:40px;" id="reg">
    {{Form::open(['route'=>['verified.preinvite.submit'],  'method' => 'post', 'enctype'=>'multipart/form-data'])}}

        <input type="hidden" value="{{@$datas->id}}" id="id" name="id">
         
            <div class="container" id="visit_information" style="width:93%">
              <div class="heading_dtl">
                 <span>Verify Pre-Invite Visitor</span>
              </div>
              <div class="row">
                <div class="login-box col-md-12 col-sm-12 col-xs-12">
                        <h2>Visitor Name: {{$datas->name}}</h2>
                        <h2>Visitor Id: {{$datas->refer_code}}</h2>
                        <h2>Authentication Pin: {{$datas->pre_invite_pin}}</h2>
					<div class="row">
                        <div  id="camera_document_mode_div">
                            <div class="login-box col-md-6 col-sm-6 col-xs-12">
                                <div class="login-box">
                                    <label style="margin-left: 15px;">Show Image in camera:<span class="text-danger">*</span></label>
                                    <div id="image"></div>
                                    <input class="btn btn-primary" type="button" value="Capture Image Snapshot" onClick="take_snapshot()" style="width: 100%;    background-color: #107cab;  margin-top: 10px;  margin-left: 102px;">
                                    <input type="hidden" name="image" value="" class="image-tag">
                                    @if($errors->has('image'))
                                    <p class="text-danger">{{ $errors->first('image') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="login-box col-md-6 col-sm-6 col-xs-12">
                                <div class="login-box col-md-6 col-sm-6 col-xs-12">
                                    <div id="results" style="color:#107cab; font-size: 14px;margin-top: 13px;">Your captured image will appear here...</div>
                                </div>
                                </div>
                        </div>
                    </div>

                  </div>
                </div>
              
              

          </div>
          
          
        </div>

  

        <div align="center">
          <div class="form-group clearfix">
              <button class="btn btn-primary" type="submit" id="create">Submit</button>
          </div>
        </div>


    {{Form::close()}}
  </div>


@endsection

@push('scripts') 

    <script language="JavaScript">
       
        //webcam code
        Webcam.set({
            width: 280,
            height: 230,
            image_format: 'jpeg',
            jpeg_quality: 500,
            facingMode: "environment"
        });
        Webcam.set('constraints',{
        facingMode: "environment"
    });


        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<div>Captured photo</div><img src="'+data_uri+'" width = "280px" height= "230px" style="border:1px solid #333; margin-top: -21px;"/>';
            } );
        }
        Webcam.attach( '#image');
        //endwebcam
    </script>

@endpush
