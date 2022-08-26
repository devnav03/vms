@extends('web.layouts.inner-master')
@section('content')
<style type="text/css">
.pull-left.logo_image{
	display:none;
}
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
   background:url(https://vztor.in/superadmin/public/assets/images/loading_image.gif) 50% 50% no-repeat;
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
    <div id="loader-p"></div>
    {{Form::open(['route'=>['add.conference.registration'],  'method' => 'post', 'enctype'=>'multipart/form-data','id'=>'createForm'])}}
          <input type="hidden" name="meeting_id" value="{{ $meeting_id }}">
          @if($type == 'Guest')
          <input type="hidden" name="member_type" value="{{ $type }}">
            <div class="container" id="visit_information" style="width:93%">
              <div class="heading_dtl">
                 <span>Visitor Details</span>
              </div>
              <div class="row">
                  <div class="login-box col-md-4 col-sm-12 col-xs-12">
                      <div>
                          <label for="name">Visitor Name:<span class="text-danger">*</span></label>
                          <input type="hidden" name="company_id" value="@if(!empty($room->company_id)){{$room->company_id}}@endif">
                          <input type="hidden" name="location_id" value="@if(!empty($room->location_id)){{$room->location_id}}@endif">
                          <input type="hidden" name="building_id" value="@if(!empty($room->building_id)){{$room->building_id}}@endif">
                          <input type="hidden" name="department_id" value="@if(!empty($room->department_id)){{$room->department_id}}@endif">
                          <input type="hidden" name="device_id" value="@if(!empty($room->device_id)){{$room->device_id}}@endif">
						              <input type="hidden" name="room_id" value="@if(!empty($room->id)){{$room->id}}@endif">
                          
                          <input placeholder="Enter name" type="text" name="name" size="30" value="@if(!empty($user_data->name)){{$user_data->name}}@endif" class="form-control nowarn" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" required>
                          @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                      </div>
                  </div>
                  <div class="login-box col-md-4 col-sm-12 col-xs-12">
                      <div>
                          <label for="email">Visitor Email:</label>
                          <input placeholder="eg:- email@gmail.com" type="email" id="email" name="email" size="30" value="@if(!empty($user_data->email)){{$user_data->email}}@elseif(!empty($user_data)){{$user_data}}@endif" class="form-control nowarn" readonly>
                          @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
                          <p id="email_error" style="color:red"></p>
                      </div>
                  </div>
                  <div class="login-box col-md-4 col-sm-12 col-xs-12">
                      <div>
                          <label for="phone">Visitor Phone No.:<span class="text-danger">*</span></label>
                          <input placeholder="Enter Phone No." type="number" id="mobile" name="mobile" value="{{ $MeetingsInvite->mobile }}"  size="30" class="form-control nowarn"  maxlength="10" required>
                          @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                          <p id="mobile_error" style="color:red"></p>
                      </div>
                  </div>
              </div>
              <div class="row">
                <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label for="gender">Select Gender: <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control nowarn" required>
                            <option value="{{old('gender')}}">Select Gender</option>
                            <option value="Male" @if(!empty($user_data->gender)) @if($user_data->gender=='Male') selected @endif @endif>Male </option>
                            <option value="Female" @if(!empty($user_data->gender)) @if($user_data->gender=='Female') selected @endif @endif>Female</option>
                            <option value="Other" @if(!empty($user_data->gender)) @if($user_data->gender=='Other') selected @endif @endif>Other</option>
                        </select>
                        @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
                    </div>
                </div>
				<div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label>Guest Email:</label>
                        <input type="email" id="guest_email" name="guest_email" class="form-control nowarn">
                        @if($errors->has('guest_email'))<p class="text-danger">{{ $errors->first('guest_email') }}</p> @endif
                    </div>
                </div>    
				<!--  <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label>Office : <span class="text-danger">*</span></label>
                        <input placeholder="Enter Office" type="text" id="office" name="office" value="@if(!empty($user_data->office)){{$user_data->office}}@endif"  size="30" class="form-control nowarn" required>
                        @if($errors->has('office'))<p class="text-danger">{{ $errors->first('office') }}</p> @endif
                    </div>
                </div>
                <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label>Department : <span class="text-danger">*</span></label>
                        <input placeholder="Enter Department" type="text" id="department" name="department" value="@if(!empty($user_data->department)){{$user_data->department}}@endif"  size="30" class="form-control nowarn" required>
                        @if($errors->has('department'))<p class="text-danger">{{ $errors->first('department') }}</p> @endif
                    </div>
                </div>
                <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label>Address : <span class="text-danger">*</span></label>
                        <input placeholder="Enter Address" type="text" id="address" name="address" value="@if(!empty($user_data->address)){{$user_data->address}}@endif"  size="30" class="form-control nowarn" required>
                        @if($errors->has('address'))<p class="text-danger">{{ $errors->first('address') }}</p> @endif
                    </div>
                </div> -->
              </div>
				
              <div class="row">
                    <div class="login-box col-md-4 col-sm-4 col-xs-12 hide_in_phone">
                       <label for="adhar">Attachment Mode:<span class="text-danger">*</span></label>
                      <select class="form-control" name="image_mode" onchange="imageMode(this.value);">
                        <option value="folder">Upload from this computer</option>
                        <option value="camera">Capture photo from camera</option>
                      </select>
                    </div>
					<input type="hidden" name="image_mode" value="folder">
				  <div class="login-box col-md-8 col-sm-8 col-xs-12" id="camera_mode_div">
				   <div class="row">	
                   <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="login-box" style="width: 100%; padding: 0px;">
                          <label style="margin-left: 15px;">Show your face in camera:<span class="text-danger">*</span></label>
                          <div id="my_camera"  style="margin-left: 20%;"></div>
                          <input class="btn btn-primary" type="button" value="Take Snapshot" onClick="take_snapshot()" style="width: 100%; margin-left: 15px;background-color: #107cab;">
                          <input type="hidden" name="image_img" value="{{old('image')}}" class="image-tag">
                          @if($errors->has('image'))
                          <p class="text-danger">{{ $errors->first('image') }}</p>
                          @endif
                      </div>
					   </div>
					   <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="login-box" style="background: transparent;">
                          <div id="results" style="border: 0px;"><!--Captured photo image will appear here... --></div>
                      </div>
                    </div>
				  </div>
                  </div>
				  <div class="login-box col-md-4 col-sm-4 col-xs-12">
					<div class="login-box" id="folder_mode_div" style="width: 100%; padding: 0px !important;">
                        <label for="">Browse &amp; Upload:<span class="text-danger">*</span></label>
                        <input type="file" name="image" value="{{old('image')}}" class="form-control">
                      </div>
                  </div>
                  </div>
                </div>
 
        <div align="center">
          <div class="form-group clearfix">
			@if($meeting_status == 'expired')  
			<p style="font-size: 18px; margin-top: 20px;color: #f00;">Meeting has been over</p>
			@else
			@if(empty($user_data->avatar))  
            <span id="error_msg" style="color:red; font-size:15px;"></span>
            <button class="btn btn-primary" type="submit" id="create">Submit</button>
			@else
			  <p style="font-size: 16px; margin-top: 20px;">You have already confirmed the meeting</p>
			@endif 
			@endif  
			  
          </div>
        </div>

            <div class="modal fade" id="exampleModal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image1</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[1][]" onchange="imageMode_1(this.value,'my_camera_1_div','folder_mode_1_div',1);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_1_div">
                      <div id="my_camera_1"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(1)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_1">

                    </div>
                    <div class="login-box" id="folder_mode_1_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image2</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[2][]" onchange="imageMode_1(this.value,'my_camera_2_div','folder_mode_2_div',2);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_2_div">
                      <div id="my_camera_2"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(2)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_2">
                    </div>

                    <div class="login-box" id="folder_mode_2_div">
                      <label for="">select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image3</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[3][]" onchange="imageMode_1(this.value,'my_camera_3_div','folder_mode_3_div',3);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_3_div">
                      <div id="my_camera_3"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(3)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_3">
                    </div>

                    <div class="login-box" id="folder_mode_3_div">
                      <label for="">select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image4</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[4][]" onchange="imageMode_1(this.value,'my_camera_4_div','folder_mode_4_div',4);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_3_div">
                      <div id="my_camera_4"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(4)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_4">
                    </div>

                    <div class="login-box" id="folder_mode_4_div">
                      <label for="">select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image5</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[5][]" onchange="imageMode_1(this.value,'my_camera_5_div','folder_mode_5_div',5);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_5_div">
                      <div id="my_camera_5"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(5)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_5">
                    </div>

                    <div class="login-box" id="folder_mode_5_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image6</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[6][]" onchange="imageMode_1(this.value,'my_camera_6_div','folder_mode_6_div',6);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_6_div">
                      <div id="my_camera_6"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(6)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_6">
                    </div>

                    <div class="login-box" id="folder_mode_6_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image7</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[7][]" onchange="imageMode_1(this.value,'my_camera_7_div','folder_mode_7_div',7);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_7_div">
                      <div id="my_camera_7"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(7)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_7">
                    </div>

                    <div class="login-box" id="folder_mode_7_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[8][]" onchange="imageMode_1(this.value,'my_camera_8_div','folder_mode_8_div',8);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_8_div">
                      <div id="my_camera_8"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(8)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_8">
                    </div>

                    <div class="login-box" id="folder_mode_8_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image9</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[9][]" onchange="imageMode_1(this.value,'my_camera_9_div','folder_mode_9_div',9);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_9_div">
                      <div id="id"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(9)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_9">
                    </div>

                    <div class="login-box" id="folder_mode_9_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_10" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image10</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[10][]" onchange="imageMode_1(this.value,'my_camera_10_div','folder_mode_10_div',10);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_10_div">
                      <div id="my_camera_10"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(10)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_10">
                    </div>

                    <div class="login-box" id="folder_mode_10_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
        @else

        <div class="container" id="visit_information" style="width:93%">
              <div class="heading_dtl">
                 <span>Staff Details</span>
              </div>
              <div class="row">
                <input type="hidden" name="member_type" value="{{ $type }}">
                  <div class="login-box col-md-4 col-sm-12 col-xs-12">
                      <div>
                          <label for="name">Name:<span class="text-danger">*</span></label>
                          <input type="hidden" name="company_id" value="@if(!empty($room->company_id)){{$room->company_id}}@endif">
                          <input type="hidden" name="location_id" value="@if(!empty($room->location_id)){{$room->location_id}}@endif">
                          <input type="hidden" name="building_id" value="@if(!empty($room->building_id)){{$room->building_id}}@endif">
                          <input type="hidden" name="department_id" value="@if(!empty($room->department_id)){{$room->department_id}}@endif">
                          <input type="hidden" name="device_id" value="@if(!empty($room->device_id)){{$room->device_id}}@endif">
                          <input type="hidden" name="room_id" value="@if(!empty($room->id)){{$room->id}}@endif">
                          
                          <input type="text" name="name" size="30" value="{{$user->name}}" class="form-control nowarn" readonly>
                          @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                      </div>
                  </div>
                  <div class="login-box col-md-4 col-sm-12 col-xs-12">
                      <div>
                          <label for="email">Email:</label>
                          <input type="email" id="email" name="email" size="30" value="{{$user->email}}" class="form-control nowarn" readonly>
                          @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
                          <p id="email_error" style="color:red"></p>
                      </div>
                  </div>
                  <div class="login-box col-md-4 col-sm-12 col-xs-12">
                      <div>
                          <label for="phone">Mobile No.:<span class="text-danger">*</span></label>
                          <input type="text" id="mobile" name="mobile" value="{{$user->mobile}}"  size="30" class="form-control nowarn"  maxlength="10" readonly>
                          @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                          <p id="mobile_error" style="color:red"></p>
                      </div>
                  </div>
              </div>
              <div class="row">
                <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label for="gender">Select Gender: <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control nowarn" required>
                            <option value="{{old('gender')}}">Select Gender</option>
                            <option value="Male" @if(!empty($user->gender)) @if($user->gender=='Male') selected @endif @endif>Male </option>
                            <option value="Female" @if(!empty($user->gender)) @if($user->gender=='Female') selected @endif @endif>Female</option>
                            <option value="Other" @if(!empty($user->gender)) @if($user->gender=='Other') selected @endif @endif>Other</option>
                        </select>
                        @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
                    </div>
                </div>
       
                 
             
                <input type="hidden" id="office" name="office" value="">
                    
                 
        
                <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label>Department : <span class="text-danger">*</span></label>
                        <input type="text" id="department" name="department" value="{{$user->getDepartment->name}}"  size="30" class="form-control nowarn"  maxlength="10" readonly>
                        @if($errors->has('department'))<p class="text-danger">{{ $errors->first('department') }}</p> @endif
                    </div>
                </div>
                <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label>Assign Staff Member:</label>
                        <select name="assign_staff" class="form-control nowarn">
                            <option value="">Select Staff</option>
                            @foreach($jr_staff as $jr_staff)
                             <option value="{{ $jr_staff->id }}">{{ $jr_staff->name }} ({{ $jr_staff->email }})</option>
                            @endforeach
                        </select>
                        @if($errors->has('assign_staff'))<p class="text-danger">{{ $errors->first('assign_staff') }}</p> @endif
                    </div>
                </div>
                <div class="login-box col-md-4 col-sm-12 col-xs-12">
                    <div>
                        <label>Guest Email:</label>
                        <input type="email" id="guest_email" name="guest_email" class="form-control nowarn">
                        @if($errors->has('guest_email'))<p class="text-danger">{{ $errors->first('guest_email') }}</p> @endif
                    </div>
                </div>           

                <input type="hidden" name="address" value="{{$user->address}}">
                <input type="hidden" name="emp_id" value="{{$user->id}}">
                  
              </div>
              @if(isset($employeedata->id))
              <input type="hidden" name="avatar" value="{{ $employeedata->avatar }}">
              <input type="hidden" name="image" value="{{ $employeedata->image }}">
              @else  
              <div class="row">
                    <div class="login-box col-md-4 col-sm-4 col-xs-12 hide_in_phone">
                       <label for="adhar">Attachment Mode:<span class="text-danger">*</span></label>
                      <select class="form-control" name="image_mode" onchange="imageMode(this.value);">
                        <option value="folder">Upload from this computer</option>
                        <option value="camera">Capture photo from camera</option>
                      </select>
                    </div>
					<input type="hidden" name="image_mode" value="folder">
				  <div class="login-box col-md-8 col-sm-8 col-xs-12" id="camera_mode_div">
				   <div class="row">	
                   <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="login-box" style="width: 100%; padding: 0px;">
                          <label style="margin-left: 15px;">Show your face in camera:<span class="text-danger">*</span></label>
                          <div id="my_camera"  style="margin-left: 20%;"></div>
                          <input class="btn btn-primary" type="button" value="Take Snapshot" onClick="take_snapshot()" style="width: 100%; margin-left: 15px;background-color: #107cab;">
                          <input type="hidden" name="image_img" value="{{old('image')}}" class="image-tag">
                          @if($errors->has('image'))
                          <p class="text-danger">{{ $errors->first('image') }}</p>
                          @endif
                      </div>
					   </div>
					   <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="login-box" style="background: transparent;">
                          <div id="results" style="border: 0px;"><!--Captured photo image will appear here... --></div>
                      </div>
                    </div>
				  </div>
                  </div>
				  <div class="login-box col-md-4 col-sm-4 col-xs-12">
					<div class="login-box" id="folder_mode_div" style="width: 100%; padding: 0px !important;">
                        <label for="">Browse &amp; Upload:<span class="text-danger">*</span></label>
                        <input type="file" name="image" value="{{old('image')}}" class="form-control">
                      </div>
                  </div>
                  </div>
                  @endif

                  </div>
                </div>
              </div>
          
        </div>

        

        <div align="center" style="margin-top: 20px;">
	    @if($meeting_status == 'expired')  
			<p style="font-size: 18px; margin-top: 20px;color: #f00;">Meeting has been over</p>
		@else		
		@if(empty($user_data->avatar))
          <div class="form-group clearfix">
            <span id="error_msg" style="color:red; font-size:15px;"></span>
            <button class="btn btn-primary" type="submit" id="create">Confirm</button>
          </div>
		@else
		<p style="font-size: 16px; margin-top: 20px;">You have already confirmed the meeting</p>
		@endif 	
		@endif	
        </div>


            <div class="modal fade" id="exampleModal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image1</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[1][]" onchange="imageMode_1(this.value,'my_camera_1_div','folder_mode_1_div',1);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_1_div">
                      <div id="my_camera_1"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(1)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_1">

                    </div>
                    <div class="login-box" id="folder_mode_1_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image2</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[2][]" onchange="imageMode_1(this.value,'my_camera_2_div','folder_mode_2_div',2);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_2_div">
                      <div id="my_camera_2"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(2)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_2">
                    </div>

                    <div class="login-box" id="folder_mode_2_div">
                      <label for="">select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image3</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[3][]" onchange="imageMode_1(this.value,'my_camera_3_div','folder_mode_3_div',3);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_3_div">
                      <div id="my_camera_3"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(3)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_3">
                    </div>

                    <div class="login-box" id="folder_mode_3_div">
                      <label for="">select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image4</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[4][]" onchange="imageMode_1(this.value,'my_camera_4_div','folder_mode_4_div',4);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_3_div">
                      <div id="my_camera_4"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(4)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_4">
                    </div>

                    <div class="login-box" id="folder_mode_4_div">
                      <label for="">select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image5</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[5][]" onchange="imageMode_1(this.value,'my_camera_5_div','folder_mode_5_div',5);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_5_div">
                      <div id="my_camera_5"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(5)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_5">
                    </div>

                    <div class="login-box" id="folder_mode_5_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image6</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[6][]" onchange="imageMode_1(this.value,'my_camera_6_div','folder_mode_6_div',6);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_6_div">
                      <div id="my_camera_6"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(6)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_6">
                    </div>

                    <div class="login-box" id="folder_mode_6_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image7</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[7][]" onchange="imageMode_1(this.value,'my_camera_7_div','folder_mode_7_div',7);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_7_div">
                      <div id="my_camera_7"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(7)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_7">
                    </div>

                    <div class="login-box" id="folder_mode_7_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[8][]" onchange="imageMode_1(this.value,'my_camera_8_div','folder_mode_8_div',8);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_8_div">
                      <div id="my_camera_8"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(8)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_8">
                    </div>

                    <div class="login-box" id="folder_mode_8_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image9</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[9][]" onchange="imageMode_1(this.value,'my_camera_9_div','folder_mode_9_div',9);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_9_div">
                      <div id="id"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(9)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_9">
                    </div>

                    <div class="login-box" id="folder_mode_9_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal_10" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Take Image10</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="">Image Mode</label>
                        <select class="form-control" name="group_image_mode[10][]" onchange="imageMode_1(this.value,'my_camera_10_div','folder_mode_10_div',10);">
                          <option value="folder">Folder</option>
                          <option value="camera">camera</option>
                        </select>
                      </div>
                    </div>
                    <div id="my_camera_10_div">
                      <div id="my_camera_10"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(10)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_10">
                    </div>

                    <div class="login-box" id="folder_mode_10_div">
                      <label for="">Select Image</label>
                      <input type="file" name="group_image[]"  class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>


        @endif


    {{Form::close()}}
  </div>


@endsection

<style type="text/css">
@supports (-webkit-touch-callout: none) {
#results img{
 transform: rotate(-90deg);
}
}  
@media(max-width:999px){	
.hide_in_phone{
	display:none !important;
}
}
</style>

@push('scripts')
    <script>

    $("#createForm").on("submit", function(){
      $("#loader-p").show();
    });//submit

    var current_tab=1;
    var section ={'1':'visit_information','2':'visit_address','3':'organization_information','4':'Asset_div','5':'covid_declaration','6':'visit_type_div'};
    $(document).ready(function(){
       $("#loader-p").hide();
       var i=1;
       while (i < 11) {
        $('#my_camera_'+i+'_div').hide();
        i++;
      }
       $.each(section,function(key,val){
         if(key!=1){
            $('#'+val).hide();
         }
       });
       $("#exampleModal_1").addClass("hide");
       imageMode('folder');
     });
        $("#pan").hide();
        $("#hard").hide();

        $('input[type="checkbox"]').click(function(){
            if($(this).is(":checked")){
               $("#pan").show();
               $("#hard").show();
            }
            else if($(this).is(":not(:checked)")){
              $("#pan").hide();
               $("#hard").hide();
            }
        });

        var add_assets_add = '<div class="assets_delete col-md-12"><div class="login-box col-md-3"><div><label>Name </label><input placeholder="Enter Name" type="text" name="assets_name[]" value="" class="form-control nowarn"></div></div><div class="login-box col-md-3"><div><label>Serial Number </label><input placeholder="Enter Serial Name" type="text" name="assets_number[]" value="" class="form-control nowarn"></div></div><div class="login-box col-md-3"><div><label>Brand</label><input placeholder="Enter Brand" type="text" name="assets_brand[]" value="" class="form-control nowarn"></div></div><div class="login-box col-md-3"><div><label></label><div style="cursor: pointer;" id="add_assets_delete"><img src="{{asset("icons8-minus-64.png")}}" style="width:40px; text-align: center;"></div></div></div></div>';

        function add_assets(){
           $(".add_assets").append(add_assets_add);
        };

        $(".add_assets").on('click','#add_assets_delete', function () {
            $(this).closest('.assets_delete').remove();
            return false;
        });
        var group_count=1;
        function add(){
			group_count=group_count+1;
            var sr_no = $("#sr_no").val();
            var num = parseInt(sr_no)+parseInt(1);
            if(num >10){
                alert('only 10 person can added!!');
                return false
            }
            $("#sr_no").val(num);
            $(".add").append('<div class="delete col-md-12"><div class="col-md-2"><label>Name</label><input placeholder="Enter Name" type="text" name="group_name['+group_count+'][group_name]" size="30" class="form-control nowarn" maxlength="16"></div><div class="col-md-2"><label>Mobile No.</label><input placeholder="Enter Mobile No." type="text" name="group_mobile['+group_count+'][group_mobile]" size="30" class="form-control nowarn" maxlength="16"></div><div class="col-md-2"><label>Gender</label><select name="group_gender['+group_count+'][group_gender]" class="form-control nowarn"><option value="male">Male </option><option value="female">Female</option></select></div><div class="col-md-2"><label>ID Proof</label><input placeholder="Enter ID" type="text" name="group_id_proof['+group_count+'][group_id_proof]" size="30" class="form-control nowarn" maxlength="16"></div><div class="col-md-1"><label>Document</label><input id="file-input"  type="file" name="group_attchment['+group_count+'][attchment]" value="" class="form-control"/></div><div class="col-md-1"><label></label><div id="results_'+num+'"><img src="{{asset("profile.png")}}" width="40px;"></div></div><div class="col-md-1"><label></label><div data-toggle="modal" data-target="#exampleModal_'+num+'" style="cursor: pointer;"><img src="{{asset("icons8-upload-48.png")}}" style="width:40px;"></div></div><div class="col-md-1"><label></label><div id="add_delete" style="cursor: pointer";><img src="{{asset("icons8-minus-64.png")}}" style="width:35px; text-align:right;"></div></div></div>');

		};

        $(".add").on('click','#add_delete', function () {
            $(this).closest('.delete').remove();
            var sr_no = $("#sr_no").val();
            var num = parseInt(sr_no)-parseInt(1);
            $("#sr_no").val(num);
            return false;
        });

        $('.group').hide();
        $("#visit_type").on('change', function(){
            var visit_type = this.value;
            if(visit_type =='group'){
                $('.group').show();
            }else{
                $('.group').hide();
            }
        });
        $('#from_date').hide();
        $("#employee_type").on('change', function(){
            var from_date = this.value;
            if(from_date =='permanent'){
                $('#from_date').hide();
            }else{
                $('#from_date').show();
            }
        });

    </script>

    <script language="JavaScript">
        $('#otp_check').hide();
        @if(session()->has('otp_true'))
            $('#reg').hide();
            $('#otp_check').show();
        @endif

        //webcam code
        Webcam.set({
            width: 280,
            height: 230,
            image_format: 'jpeg',
            jpeg_quality: 500
        });


        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
             
                document.getElementById('results').innerHTML = '<div>Captured photo</div><img src="'+data_uri+'" width = "280px" height= "230px" style="border:1px solid #333;"/>';
            } );
        }
			
 
function resizedataURL(datas, wantedWidth, wantedHeight)
{
  // We create an image to receive the Data URI
  var img = document.createElement('img');

  // When the event "onload" is triggered we can resize the image.
  img.onload = function ()
  {
    // We create a canvas and get its context.
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');

    // We set the dimensions at the wanted size.
    canvas.width = wantedWidth;
    canvas.height = wantedHeight;

    // We resize the image with the canvas method drawImage();
    ctx.drawImage(this, 0, 0, wantedWidth, wantedHeight);

    var dataURI = canvas.toDataURL();

    /////////////////////////////////////////
    // Use and treat your Data URI here !! //
    /////////////////////////////////////////
  };

  // We put the Data URI in the image's src attribute
  img.src = datas;
}

        function take_snapshotDocument() {
            Webcam.snap( function(data_uri) {
                $(".image-tag_document").val(data_uri);
                document.getElementById('results_document').innerHTML = '<div>Captured document image</div><img src="'+data_uri+'" width = "280px" height= "220px" style="border:1px solid #333;transform: rotate(-90deg);"/>';
            } );
        }

       

        function take_snapshot_0(num) {
            Webcam.snap( function(data_uri) {
                $(".image-tag_"+num).val(data_uri);
                document.getElementById('results_'+num).innerHTML = '<img src="'+data_uri+'" width = "80px" />';
            } );
        }

        //endwebcam
    
			
		
		


     function imageMode(type){
       if(type=="camera"){
         Webcam.attach( '#my_camera' );
         $('#folder_mode_div').hide();
         $('#camera_mode_div').show();
       }else{
         $('#folder_mode_div').show();
         $('#camera_mode_div').hide();
       }
     }
     var width=0;
     var count=0;
     $('#preview').click(function(){
       $.each(section,function(key,val){
           $('#'+val).show();
        });
     });
     $("#next").click(function(){
         let allAreFilled = true;
         document.getElementById(section[current_tab]).querySelectorAll("[required]").forEach(function(i) {
           // console.log(i);
            if (!i.value){
              var value=i.name;
                // alert("Please Fill "+value[0].toUpperCase()+value.slice(1));
                // return ;
              return allAreFilled = false;
            }

         });
         var format = /[!@#$%^&*()_+\=\[\]{};':"\\|,.<>\/?]+/;
         var vehical_reg_num=$('#vehical_reg_num').val();
          if(format.test(vehical_reg_num)){
            alert("Enter valid vehicle number");
          }
          if (!allAreFilled) {
            $('#error_msg').html('Please fill all mandatory fields');
            return;
          }else{
            $('#error_msg').html('');
          }
       if(current_tab<6){
         $('#'+section[current_tab]).hide();
         $('#'+section[current_tab]+'_step').addClass("done");
         $('#'+section[current_tab]+'_step').attr("disabled", true);
           current_tab=current_tab+1;
         $('#'+section[current_tab]).show();
         $('#'+section[current_tab]+'_step').attr("disabled", false);
         $('#create').hide();
         $('#back').show();
         $('#preview').hide();
         width=(13*current_tab);
         $('.stepwizard-row').eq(0).attr("style","--width:"+width+"%");

       }
       if(current_tab==6){
         $('#next').hide();
         $('#create').show();
         $('#preview').show();
       }
     });
		function validateAttachment(){
			
		}
     //  function imageUploaded() {
		   
       //  var file = document.querySelector(
       //  'input[id=image_1]')['files'][0];
        // var reader = new FileReader();

        // reader.onload = function () {
         //  base64String = reader.result.replace("data:", "")
           //    .replace(/^.+,/, "");

        //   imageBase64Stringsep = base64String;

        //   $('#image_1').val(base64String);
      // }
     //  reader.readAsDataURL(file);
    // }
       function imageMode_1(type,show_div,hide_div,count){
       if(type=="camera"){
         Webcam.attach( '#my_camera_'+count );
         $('#'+hide_div).hide();
         $('#'+show_div).show();
       }else{
         $('#'+hide_div).show();
         $('#'+show_div).hide();
       }
     }
     $('#camera_document_mode_div').hide();
     function imageDocumentMode(type,show_div,hide_div){
       if(type=="camera"){
         Webcam.attach( '#my_camera_document');
         $('#'+hide_div).hide();
         $('#'+show_div).show();
       }else{
         $('#'+hide_div).show();
         $('#'+show_div).hide();
       }
      }
    function vehivleValidate(){
      var format = /[!@#$%^&*()_+\=\[\]{};':"\\|,.<>\/?]+/;
      var vehical_reg_num=$('#vehical_reg_num').val();
      if(format.test(vehical_reg_num)){
          alert("Enter valid vehicle number");
        }
    }
    $('#vaccine_details').hide();
    function vaccineCheck(val){
      if(val=="yes"){
        $('#vaccine_details').show();
      }else{
        $('#vaccine_details').hide();
      }
    }
  </script>
@endpush
