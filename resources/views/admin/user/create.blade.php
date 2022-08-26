
@extends('admin.layout.master')
@section('title','Admin :: Add Visitor')
@push('links')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<style type="text/css">
    #results { border:1px solid; background:#ccc; }
    .heading_dtl {
      background-color: #00bcd4;
      text-align: left;
      color: white;
      font-size: 20px;
      padding: 10px;
	  font-weight: 600;
  }

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
      margin-top: 5px;
      color:#666;
  }
  .stepwizard-row {
      display: table-row;
  }
  .stepwizard {
      display: table;
      width: 100%;
      position: relative;
      background-color:#ffffff;
      height: 0px;
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
    background-color: #eee;
    z-index: 1;
  }
  .stepwizard-row:after {
      top: 23px;
      bottom: 0;
      position: absolute;
      content: " ";
      /* width: 101%; */
      width:var(--width, 0%);
      height: 17px;
      background-color: #69d400;
      color: #ffffff;
      z-index: 2;
      margin-left: -100%;
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
    background-color: #eeeeee;
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
</style>
@endpush
@section('content')
<div id="loader-p">
</div>

<section class="wrapper main-wrapper">
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title">Add Visitor</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a>Visitor</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!--breadcrumbs end-->
     <div class="row" style="padding: 20px;">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
          <div class="panel-body">
            <!-- progressbar -->
            <div class="container">
              <div class="stepwizard">
                  <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step col-xs-3">
                        <a  type="button" id="visit_information_step" class="btn btn-default btn-circle">1</a>
                        <p><small>Visitor Information</small></p>
                    </div>
                      <div class="stepwizard-step col-xs-3">
                          <a  type="button" id="visit_address_step" class="btn btn-success btn-circle" disabled="disabled">2</a>
                          <p><small>Visit Address</small></p>
                      </div>

                      <div class="stepwizard-step col-xs-3">
                          <a  type="button" id="organization_information_step" class="btn btn-default btn-circle" disabled="disabled">3</a>
                          <p><small>Visitor Organization Address</small></p>
                      </div>
                      <div class="stepwizard-step col-xs-3">
                          <a  type="button" id="Asset_div_step" class="btn btn-default btn-circle" disabled="disabled">4</a>
                          <p><small>Asset</small></p>
                      </div>
                      <div class="stepwizard-step col-xs-3">
                          <a  type="button" id="covid_declaration_step" class="btn btn-default btn-circle" disabled="disabled">5</a>
                          <p><small>Covid Declaration Form</small></p>
                      </div>
                      <div class="stepwizard-step col-xs-3">
                          <a  type="button" id="visit_type_div_step" class="btn btn-default btn-circle" disabled="disabled">6</a>
                          <p><small>Visit Type</small></p>
                      </div>
                  </div>
              </div>
            </div>

              {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','class'=>'validate cmxform','files'=>true]) !!}
            <div class="container" id="visit_information">
              <h5 class="heading_dtl" align="center">Visitor Information</h5>

              <div class="row">
                <div class="form-group col s5">
                    <div class="col-md-3">{!! Form::label('name', 'Visitor Name', ['class'=>'']) !!}<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></div>
                    <div class="col-md-9">
                        {!! Form::text('name', '', ['class'=>'form-control','required']) !!}
                        @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                    </div>
                </div>
                <div class="form-group col s5">
                    <div class="col-md-3">{!! Form::label('name', 'Visitor Email', ['class'=>'']) !!}<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></div>
                    <div class="col-md-9">
                        {!! Form::email('email', '', ['class'=>'form-control','required']) !!}
                        @if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
                    </div>
                </div>
              
                <div class="form-group col s5">
                    <div class="col-md-3">{!! Form::label('name', 'Visitor Phone', ['class'=>'']) !!}<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></div>
                    <div class="col-md-9">
                        {!! Form::text('mobile', '', ['class'=>'form-control','required','id'=>'mobile']) !!}
                        @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                        <p id="mobile_error" style="color:red"></p>
                    </div>
                </div>
                <div class="form-group col s5">
                    <label class="">Gender</label>
                    <select name="gender" class="form-control nowarn">
                        <option value="male">Male </option>
                        <option value="female">Female</option>
                    </select>
                </div>
              </div>
             
             <div class="row">
                 <h5 class="heading_dtl" align="center">Attached Document</h5>
				 <div class="form-group col s5">
                  <label for="document_type" class="">Document Type: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                    <div class="col-md-9">
                        {!! Form::select('document_type', ['dl' => 'Driving Licence', 'adhar_card' => 'Aadhar Card','govt_id_pf'=>'Govt Identity Proof','Pancard'=>'Pan Card'], null, ['class' => 'form-control']) !!}
                        @if($errors->has('document_type'))<p class="text-danger">{{ $errors->first('document_type') }}</p> @endif
                    </div>
                </div>
				<div class="login-box col s5">
                      <label for="">Image Mode</label>
                      <select class="form-control" name="image_document_mode" onchange="imageDocumentMode(this.value,'camera_document_mode_div','folder_document_mode_div');">
                        <option value="folder">Upload From Computer</option>
                        <option value="camera">Camera</option>
                      </select>
                   </div>
				</div>
				<div class="row">
							<div  id="camera_document_mode_div">
								<div class="login-box col s6">
									<div class="login-box">
										<label style="margin-left: 15px;">Image:<span class="text-danger">*</span></label>
										<div id="my_camera_document" ></div>
										<input class="btn btn-primary" type="button" value="Take Snapshot" onClick="take_snapshotDocument()" style="width: 100%;    background-color: #107cab;">
										<input type="hidden" name="attachmant" value="{{old('attachmant')}}" class="image-tag_document">
										@if($errors->has('attachmant'))
										<p class="text-danger">{{ $errors->first('attachmant') }}</p>
										@endif
									</div>
								</div>
								<div class="login-box col s6">
									<div class="login-box col s6">
										<div id="results_document" style="color:#107cab; font-size: 16px;">Your captured image will appear here...</div>
									</div>
								 </div>
							</div>
						  <div id="folder_document_mode_div">
							  <div class="login-box col s6">
								  	<label for="">Select Attachment:<span class="text-danger">*</span></label>
							<input type="file" name="attachmant"  id="attachments_1" value="{{old('attachmant')}}" onchange="validateAttachment();" class="form-control">
							  </div>
							
						  </div>
						
                  </div>

				<div class="row">
                <div class="form-group col s3">
                    <div class="col-md-3">{!! Form::label('name', 'Visitor Adhar id', ['class'=>'']) !!}<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></div>
                    <div class="col-md-9">
                        {!! Form::text('adhar_no', '', ['class'=>'form-control','required']) !!}
                        @if($errors->has('adhar_no'))<p class="text-danger">{{ $errors->first('adhar_no') }}</p> @endif
                    </div>
                </div>
                <div class="form-group col s3">
                    <div class="col-md-3">
                        {!! Form::label('name', 'Visit Date & Time', ['class'=>'']) !!}<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></div>
                    <div class="col-md-9">
                        <input type="datetime-local" id="visite_time" value="{{old('visite_time')}}" class="form-control" name="visite_time">
                        @if($errors->has('visite_time'))<p class="text-danger">{{ $errors->first('visite_time') }}</p> @endif
                    </div>
                </div>
              
                <div class="form-group col s3">
                  <label for="vehical_type" class="">Vehicle Type:</label>
                  <div class="col-md-9">
                      {!! Form::select('vehical_type', [''=>'select type','2 wheeler' => '2 wheeler', '4 wheeler' => '4 wheeler'], null, ['class' => 'form-control']) !!}
                    @if($errors->has('vehical_type'))<p class="text-danger">{{ $errors->first('vehical_type') }}</p> @endif
                </div>
              </div>

                <div class="form-group col s3">
                  <div class="col-md-3">{!! Form::label('vehical_reg_num', 'Vehicle Registration Number:', ['class'=>'',]) !!}</div>
                  <div class="col-md-9">
                      {!! Form::text('vehical_reg_num', @old('vehical_reg_num'), ['class'=>'form-control']) !!}
                      @if($errors->has('vehical_reg_num'))<p class="text-danger">{{ $errors->first('vehical_reg_num') }}</p> @endif
                  </div>
                  </div>


              </div>
            <div class="row">
              <div class="form-group col s3">
                <label for="visite_duration" class="">Visit Duration: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                <div class="col-md-9">
                    {!! Form::select('visite_duration', ['15' => '15 Min', '30' => '30 Min','45'=>'45 Min','1'=>'1 Hour'], null, ['class' => 'form-control']) !!}
                  @if($errors->has('visite_duration'))<p class="text-danger">{{ $errors->first('visite_duration') }}</p> @endif
              </div>
            </div>

            <div class="form-group col s3">
              <label for="status" class="">Is Active: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                <div class="col-md-9">
                    {!! Form::select('status', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) !!}
                    @if($errors->has('status'))<p class="text-danger">{{ $errors->first('status') }}</p> @endif
                </div>
              </div>
            
              <div class="form-group col s3">
				  <div>
				  <label for="country_id" class="">Select Country: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
				  <select name="country_id" id="country_id" class="form-control " required>
					<option value="{{old('country_id')}}">Select Country name</option>
					@foreach($get_country as $country)
					  <option value="{{$country->id}}" {{@old('country_id') ==$country->id?'selected':''}}>{{$country->name}}</option>
					@endforeach
				  </select>
				  @if($errors->has('country_id'))<p class="text-danger">{{ $errors->first('country_id') }}</p> @endif
				  </div>
            </div>
				<div class="form-group col s3">
					<div id="state_iddiv">
					</div>
				</div>
              </div>
              <div class="row">
                <div class="form-group col s3">
                  <div id="city_iddiv">
                  </div>
                </div>
                <div class="form-group col s3">
    				<label for="pincode"  class="">Pincode: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
    				<input placeholder="Enter Pincode" type="text" name="pincode" size="30" value="{{old('pincode')}}" class="form-control " onkeypress="return (event.charCode > 47 && event.charCode < 58)" required>								
					@if($errors->has('pincode'))<p class="text-danger">{{ $errors->first('pincode') }}</p> @endif
    			</div>
			  </div>
              <div class="row">
				<div class="form-group col s6">
                  <div>
                    <label for="address_1"  class="">Official addres:</label>
                    <textarea row="5" col="5" class="form-control " name="address_1" required>{{old('address_1')}}</textarea>
                    @if($errors->has('address_1'))<p class="text-danger">{{ $errors->first('address_1') }}</p> @endif
                  </div>
                </div>
				  <div class="form-group col s6">
					  <div>
						  <label for="address_2"  class="">Residential address:</label>
						  <textarea row="5" col="5" class="form-control " name="address_2" required>{{old('address_2')}}</textarea>
						  @if($errors->has('address_2'))<p class="text-danger">{{ $errors->first('address_2') }}</p> @endif
					  </div>
				  </div>
              </div>
              <div class="row">
                
                <div id="from_date">
                  <div class="form-group col s4">
                    <label for="employee_from_date">From Date:</label>
                    <input type="datetime-local" class="form-control" name="employee_from_date" value="">
                    @if($errors->has('employee_from_date'))<p class="text-danger">{{ $errors->first('employee_from_date') }}</p> @endif
                  </div>
                  <div class="form-group col s4">
                    <label for="employee_till_date">Till Date:</label>
                    <input type="datetime-local"  class="form-control" name="employee_till_date" value="">
                    @if($errors->has('employee_till_date'))<p class="text-danger">{{ $errors->first('employee_till_date') }}</p> @endif
                  </div>
                </div>
              </div>
          </div>
          <div class="container" id="visit_address">
              <h5 class="heading_dtl" align="center">Visiting Address</h5>
              <div class="row" style="margin-top: 20px;">
                <div class="form-group col s3">
                   <label for="location_id" class="">Select Location: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                  <select name="location_id" id="location_id" class="form-control " required>
                    <option value="{{old('location_id')}}">Select Location name</option>
                    @foreach($locations as $location)
                    <option value="{{$location->id}}" {{@old('location_id') ==$location->id?'selected':''}}>{{$location->name}}</option>
                    @endforeach
                  </select>
                  @if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
                </div>
                <div  class="form-group col s3" id="building_idDiv">
				 </div>
				  <div  class="form-group col s3" id="department_idDiv">
				 </div>
				  <div  class="form-group col s3" id="officer_idDiv">
				 </div>
			  </div> 
			  <div class="row" style="margin-top: 20px;">
				  <div  class="form-group col s6">
					  <label class="">Select Services: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
					<select name="services" class="form-control" required>
                        <option value="{{old('services')}}">Select Services</option>
                        <option value="Official"  {{@old('services') =='Official'?'selected':''}} >Official</option>
                        <option value="Personal"  {{@old('services') =='Personal'?'selected':''}} >Personal</option>
                       {{-- <option value="Adhar Services Complaint"  {{@old('services') =='Adhar Services Complaint'?'selected':''}} >Adhar 							Services Complaint </option>
                        <option value="Birth Certificate"  {{@old('services') =='Birth Certificate'?'selected':''}} >Birth 											Certificate</option>
                        <option value="Death Certificate"  {{@old('services') =='Death Certificate'?'selected':''}} >Death 											Certificate</option>--}}
                    </select>
                    @if($errors->has('services'))<p class="text-danger">{{ $errors->first('services') }}</p> @endif
				 </div>
					 
                </div>
              
            </div>

            </div>
          <div class="container" id="organization_information">
              <h5 class="heading_dtl" align="center">Visitor Organization Address</h5>

              <div class="row">
                <div class="form-group col s5">
                  <div class="col-md-3">{!! Form::label('organization_name', 'Organization Name', ['class'=>'']) !!}<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></div>
                  <div class="col-md-9">
                      {!! Form::text('organization_name', @old('organization_name'), ['class'=>'form-control','required']) !!}
                      @if($errors->has('organization_name'))<p class="text-danger">{{ $errors->first('organization_name') }}</p> @endif
                  </div>
              </div>
      				<div class="form-group col s5">
      				    <label for="orga_country_id"  class="">Select Organization Country: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
        					<select name="orga_country_id" id="orga_country_id" class="form-control " required>
        						<option value="{{old('orga_country_id')}}">Select Country name</option>
        						@foreach($get_country as $country)
        							<option value="{{$country->id}}"  {{@old('orga_country_id')==$country->id ?'selected':''}}>{{$country->name}}</option>
        						@endforeach
        					</select>
        					@if($errors->has('orga_country_id'))<p class="text-danger">{{ $errors->first('orga_country_id') }}</p> @endif
                </div>
             
                <div class="form-group col s3">
    							<div id="orga_state_iddiv">
    							</div>
    						</div>
    						<div class="form-group col s3">
    							<div id="orga_city_iddiv">
    							</div>
    						</div>
              
              </div>
              <div class="heading_dtl">
             <span>Visitor Image</span>
          </div>
          <div class="row">
            <div class="login-box col s4">
              <label for="">Image Mode</label>
              <select class="form-control" name="image_mode" onchange="imageMode(this.value);">
                <option value="folder">Upload From Computer</option>
                <option value="camera">Camera</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div  id="camera_mode_div">
              <div class="login-box">
                  <label style="margin-left: 15px;">Image:<span class="text-danger">*</span></label>
				  <div class="row">
					  <div class="col s6">
						   <div id="my_camera"  style="margin-left: 20%;"></div>
						  <input class="btn btn-primary" type="button" value="Take Snapshot" onClick="take_snapshot()" style="margin-left: 15px;background-color: #107cab;">
					  </div>
					  <div class="col s6">
					  	<div id="results" style="color:#107cab; font-size: 14px;margin-top: 15px;margin-left: 20px;width: 228px;text-align: center;">Your captured image will appear here...</div>
					  </div>
				  </div>
                 <input type="hidden" name="image" value="{{old('image')}}" class="image-tag">
                  @if($errors->has('image'))
                  <p class="text-danger">{{ $errors->first('image') }}</p>
                  @endif
				  
              </div>
              
            </div>

              <div class="login-box" id="folder_mode_div" style="display: block;">
                <label for="">Select Image:<span class="text-danger">*</span></label>
                <input type="file" name="image" id="image_1" value="{{old('image')}}" onchange="imageUploaded()" class="form-control">
              </div>
            </div>
          </div>
          <div class="container" id="Asset_div">
            <h5 class="heading_dtl" align="center">Asset</h5>

            <div class="row add_assets">
                   <div class="form-group col s12">
                        <div class="col s3">
                            <div>
                                <label  class="">Name </label>
                                <input placeholder="Enter Name" type="text" name="assets_name[]" value="" class="form-control nowarn">
                            </div>
                        </div>
                        <div class="col s3">
                            <div>
                                <label  class="">Serial Number </label>
                                <input placeholder="Enter Serial Name" type="text" name="assets_number[]" value="" class="form-control nowarn">
                            </div>
                        </div>
                         <div class="col s3">
                            <div>
                                <label  class="">Brand</label>
                                <input placeholder="Enter Brand" type="text" name="assets_brand[]" value="" class="form-control nowarn">
                            </div>
                        </div>
                        <div class="col s3">
                            <label></label>
                            <div style="cursor: pointer;" onclick="add_assets()">
                               <img src="{{asset('icons8-add-96.png')}}" style="width:40px; text-align: center;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="login-box" style="width: 100%;">
                        <span style="color: #2e98c5; font-size: 16px;"><input type="checkbox" name="carrying_device" id="carrying_device" style="width: 60px;" class="nowarn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Are you carrying storage device?</u></b> </span>
                    </div>
                    <div class="col s6" id="pan">
                        <div>
                            <label for="adhar"  class="">No. of Pan Drives:</label>
                            <input placeholder="Enter No. of Pan Drives" type="number" name="pan_drive" value="{{old('pan_drive')}}" class="form-control nowarn">
                        </div>
                    </div>

                     <div class="col s6" id="hard">
                        <div>
                            <label for="adhar"  class="">No. of Hard Disks:</label>
                           <input placeholder="Enter No. of Hard Disks" type="number" name="hard_disk" value="{{old('hard_disk')}}" class="form-control nowarn">

                        </div>
                    </div>
                </div>
          </div>

          <div class="container" id="covid_declaration">
              <h4 class="heading_dtl"><center>Covid Declaration Form</center></h4>

              <div class="row">
                <div class="form-group col s6">
                    <label  class="">Have you taken the vaccine?<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                    <div class="col-md-9">
                        <select name="vaccine" class="form-control" required onchange="vaccineCheck(this.value);">
                        <option value="">Select Option</option>
                        <option value="yes"  {{@old('vaccine') =='yes'?'selected':''}}>Yes</option>
                        <option value="no"  {{@old('vaccine') =='no'?'selected':''}}>No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col s6">
                    <label  class="">Are You Currently Experiencing Any Following Symptoms?<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                    <div class="col-md-9">
                       <select name="symptoms" class="form-control">
                           <option value="">Select First</option>
                           @foreach($symptoms as $sty);
                           <option value="{{$sty->name}}" {{@old('symptoms') ==$sty->name?'selected':''}}>{{$sty->name}}</option>
                           @endforeach();
                       </select>
                        @if($errors->has('symptoms'))<p class="text-danger">{{ $errors->first('symptoms') }}</p> @endif
                    </div>
                </div>
              </div>
              <div id="vaccine_details" class="row">
                  <div class="col s6">
                      <div>
                          <label for="vaccine">Which dose of vaccine have you taken?:<span class="text-danger">*</span> </label>
                          <select name="vaccine_count" class="form-control nowarn">
                              <option value="1">First Dose</option>
                              <option value="2">Second Dose </option>
                              <option value="3">Booster Dose</option>
                          </select>
                          @if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
                      </div>
                  </div>
                  <div class="col s6">
                      <div>
                          <label for="vaccine">Which company's vaccine have you taken ?:<span class="text-danger">*</span> </label>
                          <select name="vaccine_name" class="form-control nowarn">
                              <option value="Covishield">Covishield</option>
                              <option value="Covaxin">Covaxin</option>
                              <option value="Sputnik V">Sputnik V</option>
                              <option value="ZyCoV-D">ZyCoV-D</option>
                              <option value="mRNA-1273">mRNA-1273</option>
                          </select>
                          @if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
                      </div>
                  </div>
            </div>
              <div class="row">
                <div class="form-group col s6">
                    <label class="">Have you traveled to any covid affected State in the past 14 days?:<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                    <div class="col-md-9">
                        <select name="travelled_states" class="form-control" required>
                        <option value="{{old('travelled_states')}}">Select Option</option>
                        <option value="yes" {{@old('travelled_states') =='yes'?'selected':''}}>Yes</option>
                        <option value="no" {{@old('travelled_states') =='no'?'selected':''}}>No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col s6">
                    <label class="">Did you get in touch with any COVID positive patient in the last 15 days?<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                    <div class="col-md-9">
                        <select name="travelled_states" class="form-control" required>
                        <option value="{{old('travelled_states')}}">Select Option</option>
                        <option value="yes"  {{@old('travelled_states') =='yes'?'selected':''}}>Yes</option>
                        <option value="no"  {{@old('travelled_states') =='no'?'selected':''}}>No</option>
                    </select>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col s6">
                  <label class="">Current Body Temprature:<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>
                  <div class="col-md-9">
                      {!! Form::text('temprature', @old('temprature'), ['class'=>'form-control','required']) !!}
                      @if($errors->has('temprature'))<p class="text-danger">{{ $errors->first('temprature') }}</p> @endif
                  </div>
                </div>
              </div>
          </div>
          <!-- Modal -->
          <div class="modal" id="exampleModal_1" style="display:block">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image1</h5>
                      <button type="button" data-uid="exampleModal_1" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_1"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(1)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_1">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image2</h5>
                      <button type="button" data-uid="exampleModal_2"  class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_2"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(2)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_2">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image3</h5>
                      <button type="button" class="close" data-uid="exampleModal_3"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_3"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(3)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_3">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image4</h5>
                      <button type="button" class="close" data-uid="exampleModal_4"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_4"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(4)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_4">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image5</h5>
                      <button type="button" class="close" data-uid="exampleModal_5"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_5"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(5)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_5">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image6</h5>
                      <button type="button" class="close" data-uid="exampleModal_6"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_6"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(6)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_6">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image7</h5>
                      <button type="button" class="close" data-uid="exampleModal_7"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_7"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(7)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_7">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image8</h5>
                      <button type="button" class="close" data-uid="exampleModal_8"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_8"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(8)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_8">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image9</h5>
                      <button type="button" class="close" data-uid="exampleModal_9"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_9"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(9)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_9">
                    </div>
                  </div>
                </div>
              </div>
          <div class="modal fade" id="exampleModal_10" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Take Image10</h5>
                      <button type="button" class="close" data-uid="exampleModal_10"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="my_camera_10"  style="margin-left: 135px;"></div>
                      <input class="btn btn-primary  btn-lg" type="button" value="Take Snapshot" onClick="take_snapshot_0(10)" data-dismiss="modal" style="width: 100%; margin-left: 15px;">
                      <input type="hidden" name="group_image[]" value="" class="image-tag_10">
                    </div>
                  </div>
                </div>
              </div>
        <div class="container" id="visit_type_div">
          <div class="row">
              <div class="col s6">
                <label for="visit_type" class="">Visit Type:<sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup> </label>
                <select name="visit_type" id="visit_type" class="form-control nowarn" required="">
                    <!-- <option value="">Select visit type</option> -->
                    <option value="single">Single</option>
                    <option value="group">Group</option>
                </select>
                <input type="hidden" id="sr_no" value="1">
              </div>
          </div>
          <div class="row add group" style="">
            <div class="col s12">
                <div class="col s2">
                    <label class="">Name</label>
                    <input placeholder="Enter Name" type="text" name="group_name[1][group_name]" size="30" class="form-control nowarn" maxlength="16">
                </div>
                <div class="col s2">
                    <label class="">Mobile No.</label>
                    <input placeholder="Enter Mobile No." type="text" name="group_mobile[1][group_mobile]" size="30" class="form-control nowarn" maxlength="16">
                </div>
                <div class="col s2">
                    <label class="">Gender</label>
                    <select name="group_gender[1][group_gender]" class="form-control nowarn">
                        <option value="male">Male </option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col s1">
                    <label class="">ID Proof</label>
                    <input placeholder="Enter ID" type="text" name="group_id_proof[1][group_id_proof]" size="30" class="form-control nowarn" maxlength="16">
                </div>
			         <div class="col s2">
                <label class="">Document</label>

							   <input id="file-input" type="file" name="group_attchment[1][attchment]" class="form-control">

                  </div>
                  <div class="col s1">
                      <label></label>
                      <div id="results_1"><img src="{{asset('profile.png')}}" width="40px;"></div>
                  </div>
                  <div class="col s1">
                      <label></label>
                      <div  style="cursor: pointer;"  onclick="ImageModalOpen(1)">
                          <img src="{{asset('icons8-upload-48.png')}}" style="width:40px;">
                      </div>
                  </div>
                  <div class="col s1">
                      <label></label>
                      <div style="cursor: pointer;" onclick="add()">
                          <img src="{{asset('icons8-add-96.png')}}" style="width:40px;">
                      </div>
                  </div>
              </div>
          </div>
        </div>
            <div class="container">
              <div class="row">
                <div class="form-group clearfix">
                    <button class="btn btn-primary pull-right" id="create">Create</button>
                    <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
                    <a style="background: #25cfea;" class="btn btn-default pull-right" id="next">Next</a>
                      <a style="background: #25cfea;" class="btn btn-default pull-right" id="preview">Preview</a>
                    <span id="error_msg" style="color:red; font-size:20px;"></span>
                </div>
              </div>
            </div>

        {!! Form::close() !!}
    </div>
  </div>
</div>
<div class="col-md-8 col-md-offset-2 text-center">
    <img id="output"/>
</div>
        </div>
</section>
@endsection
@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/jstree.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script language="JavaScript">
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
    Webcam.set({
        width: 200,
        height: 200,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach( '#my_camera' );
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'" width = "225px" height= "150px"/>';
            document.getElementById("Submit").style.display="block";
        } );
    }
    Webcam.attach('#my_camera_1');
    Webcam.attach('#my_camera_2');
    Webcam.attach('#my_camera_3');
    Webcam.attach('#my_camera_4');
    Webcam.attach('#my_camera_5');
    Webcam.attach('#my_camera_6');
    Webcam.attach('#my_camera_7');
    Webcam.attach('#my_camera_8');
    Webcam.attach('#my_camera_9');
    Webcam.attach('#my_camera_10');

     function take_snapshot_0(num) {
         Webcam.snap( function(data_uri) {
             $(".image-tag_"+num).val(data_uri);
             document.getElementById('results_'+num).innerHTML = '<img src="'+data_uri+'" width = "80px" />';
         } );
     }
	$('#country_id').on('change', function() {
		var country_id = this.value;
    getState(country_id);
  });
  function getState(country_id){
    if(!country_id){
      return;
    }
		$("#city_id").html('');
		$("#state_id").html('');
    $("#loader-p").show();
			$.ajax({
				url:"{{route('web.get.state')}}",
				type: "POST",
				data: {
				country_id: country_id,
				_token: '{{csrf_token()}}'
				},
				dataType : 'json',
				success: function(result){
          $("#loader-p").hide();
					$('#state_iddiv').html('<label for="state_id" class="">Select State: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>'+
					'<select name="state_id" id="state_id" class="form-control " required style="display:block" onchange="getCity();">'+
					'<option value="{{old('state_id')}}">Select State Name</option>'+
					'</select>'+
        '	@if($errors->has('state_id'))<p class="text-danger">{{ $errors->first('state_id') }}</p> @endif');
					$.each(result.states,function(key,value){
						$("#state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
					});
				}
			});
	}
	function getCity() {

		var state_id = $('#state_id').val();
    if(!state_id){
      return;
    }
    $("#loader-p").show();
		$("#city_id").html('');
		$.ajax({
			url:"{{route('web.get.city')}}",
			type: "POST",
			data: {
			state_id: state_id,
			_token: '{{csrf_token()}}'
			},
			dataType : 'json',
			success: function(result){
        $("#loader-p").hide();
				$('#city_iddiv').html('<label for="city_id" class="">Select City: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>'+
				'<select name="city_id" id="city_id" class="form-control " required style="display:block">'+
				'<option value="{{old('city_id')}}">Select City Name</option>'+
				'</select>'+
      '	@if($errors->has('city_id'))<p class="text-danger">{{ $errors->first('city_id') }}</p> @endif');
				$.each(result.city,function(key,value){
					$("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
				});
			}
		});
	}
	$('#location_id').on('change', function() {
	  var location_id = this.value;
	  $("#building_id").html('');
	  $("#department_id").html('');
	  $("#officer_id").html('');
	  getBuilding(location_id);
	});


		function getBuilding(location_id){
      $("#loader-p").show();
			  $("#department_id").html('');
			  $("#officer").html('');
			  $.ajax({
				  url:"{{url('web-get-building')}}",
				  type: "POST",
				  data: {
				  location_id: location_id,
					_token: '{{csrf_token()}}'
				  },
				dataType : 'json',
				success: function(result){
          $("#loader-p").hide();
					$('#building_idDiv').html('<label for="building_id" class="">Select Building: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>'+
									'<select name="building_id" id="building_id" class="form-control " required style="display:block;">'+
									'</select>'+
                '	@if($errors->has('building_id'))<p class="text-danger">{{ $errors->first('building_id') }}</p> @endif');
				  $.each(result,function(key,value){
				  $("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
				  if(key==0){
					getDepartment(value.id);
					  }
				  });

				 }
			 });
		}

		/* on click department */



		  function getDepartment(building_id){
        $("#loader-p").show();
			   $("#officer").html('');
				  $.ajax({
					  url:"{{url('web-get-department')}}",
					  type: "POST",
					  data: {
					  building_id: building_id,
					  _token: '{{csrf_token()}}'
					  },
					  dataType : 'json',
					  success: function(result){
              $("#loader-p").hide();
						  $('#department_idDiv').html('<label for="department_id" class="">Select Department: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>'+
								'<select name="department_id" onchange="getOfficer()" id="department_id" class="form-control " required style="display:block;">'+
								'</select>'+
              '	@if($errors->has('department_id'))<p class="text-danger">{{ $errors->first('department_id') }}</p> @endif');

							$.each(result,function(key,value){
							  $("#department_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
								  if(key==0){
									getOfficer(value.id);
								}
							});

						 }
					  });
					  }

		$('#department_id').on('change', function() {
		  var department_id = this.value;
		  $("#officer").html('');
		  getOfficer(department_id);
		});

			function getOfficer() {
        $("#loader-p").show();
				 var depart_id = $("#department_id").val();
				$("#officer").html('');
                $.ajax({
                    url:"{{route('web.get.officer')}}",
                    type: "POST",
                    data: {
                    department_id: depart_id,
                    _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result){
                      $("#loader-p").hide();
          						$('#officer_idDiv').html('<label for="officer" class="">Select Officer: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>'+
          							'<select name="officer" id="officer" class="form-control " required style="display:block;">'+
          							'</select>'+
                        '	@if($errors->has('officer'))<p class="text-danger">{{ $errors->first('officer') }}</p> @endif');
          						$("#officer").append('<option value="">select offcer</option>');
                        $.each(result.states,function(key,value){
                            $("#officer").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            }



	$('#orga_country_id').on('change', function() {
    $("#loader-p").show();
		var country_id = this.value;
		$("#orga_city_id").html('');
		$("#orga_state_id").html('');
			$.ajax({
				url:"{{route('web.get.state')}}",
				type: "POST",
				data: {
				country_id: country_id,
				_token: '{{csrf_token()}}'
				},
				dataType : 'json',
				success: function(result){
          $("#loader-p").hide();
					$('#orga_state_iddiv').html('<label for="orga_state_id" class="">Select State: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>'+
					'<select name="orga_state_id" id="orga_state_id" class="form-control " required style="display:block" onchange="getOrgaCity();">'+
					'<option value="{{old('orga_state_id')}}">Select State Name</option>'+
					'</select>'+
        '	@if($errors->has('orga_state_id'))<p class="text-danger">{{ $errors->first('orga_state_id') }}</p> @endif');
					$.each(result.states,function(key,value){
						$("#orga_state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
					});
				}
			});
	});
	function getOrgaCity() {
    $("#loader-p").show();
		var state_id = $('#orga_state_id').val();
		$("#orga_city_id").html('');
		$.ajax({
			url:"{{route('web.get.city')}}",
			type: "POST",
			data: {
			state_id: state_id,
			_token: '{{csrf_token()}}'
			},
			dataType : 'json',
			success: function(result){
        $("#loader-p").hide();
				$('#orga_city_iddiv').html('<label for="orga_city_id" class="">Select City: <sup class="mdi-action-grade" style="color: red;font-size: 8px;padding-left: 3px;"></sup></label>'+
				'<select name="orga_city_id" id="orga_city_id" class="form-control " required style="display:block">'+
				'<option value="{{old('orga_city_id')}}">Select City Name</option>'+
				'</select>'+
      '	@if($errors->has('orga_city_id'))<p class="text-danger">{{ $errors->first('orga_city_id') }}</p> @endif');
				$.each(result.city,function(key,value){
					$("#orga_city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
				});
			}
		});
	}
  var add_assets_add = '<div class="assets_delete col s12"><div class="col s3"><div><label class="">Name </label><input placeholder="Enter Name" type="text" name="assets_name[]" value="" class="form-control nowarn"></div></div><div class="col s3"><div><label class="">Serial Number </label><input placeholder="Enter Serial Name" type="text" name="assets_number[]" value="" class="form-control nowarn"></div></div><div class="col s3"><div><label class="">Brand</label><input placeholder="Enter Brand" type="text" name="assets_brand[]" value="" class="form-control nowarn"></div></div><div class="col s3"><div><label></label><div style="cursor: pointer;" id="add_assets_delete"><img src="{{asset("icons8-minus-64.png")}}" style="width:40px; text-align: center;"></div></div></div></div>';
  function add_assets(){
     $(".add_assets").append(add_assets_add);
  };

  $(".add_assets").on('click','#add_assets_delete', function () {
      $(this).closest('.assets_delete').remove();
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
      $(".add").append('<div class="delete col s12"><div class="col s2"><label class="">Name</label>'+
      '<input placeholder="Enter Name" type="text" name="group_name['+group_count+'][group_name]" size="30" class="form-control nowarn" maxlength="16">'+
      '</div><div class="col s2"><label class="">Mobile No.</label><input placeholder="Enter Mobile No." type="text" name="group_mobile['+group_count+'][group_mobile]"'+
      ' size="30" class="form-control nowarn" maxlength="16"></div><div class="col s2"><label class="">Gender</label><select name="group_gender['+group_count+'][group_gender]" class="form-control nowarn show2"><option value="male">Male </option><option value="female">Female</option></select></div><div class="col s1"><label>ID Proof</label>'+
      '<input placeholder="Enter ID" type="text" name="group_id_proof['+group_count+'][group_id_proof]" size="30" class="form-control nowarn" maxlength="16">'+
      '</div><div class="col s2"><label class="">Document</label><input id="file-input"  type="file"'+ 'name="group_attchment['+group_count+'][attchment]" class="form-control"/>'+
      '</div><div class="col s1"><label></label><div id="results_'+num+'"><img src="{{asset("profile.png")}}" width="40px;"></div></div><div class="col s1">'+
      '<label></label><div style="cursor: pointer;"><img src="{{asset('icons8-upload-48.png')}}" style="width:40px;" onClick="ImageModalOpen('+num+');"></div></div><div class="col s1">'+
      '<label></label><div id="add_delete" style="cursor:'+ 'pointer";><img src="{{asset("icons8-minus-64.png")}}" style="width:35px; text-align:right;"></div></div></div>');

};

  $(".add").on('click','#add_delete', function () {
      $(this).closest('.delete').remove();
      var sr_no = $("#sr_no").val();
      var num = parseInt(sr_no)-parseInt(1);
      $("#sr_no").val(num);
      return false;
  });
  function ImageModalOpen(id){
    $('#exampleModal_'+id).addClass("show");
    $("#exampleModal_"+id).removeClass("hide");

  }
$(".close").click(function(e){
  var uid=$(this).attr("data-uid");
  $("#"+uid).addClass("hide");
    $("#"+uid).removeClass("show");

});
var current_tab=1;
var section ={'1':'visit_information','2':'visit_address','3':'organization_information','4':'Asset_div','5':'covid_declaration','6':'visit_type_div'};
$(window).load(function(){
   $("#loader-p").hide();
   $.each(section,function(key,val){
     if(key!=1){
        $('#'+val).hide();
     }
   });
   $("#exampleModal_1").addClass("hide");
 });
 $('#back').hide();
 $('#create').hide();
 $('#preview').hide();
 $("#back").click(function(){
   if(current_tab!=1){
     $('#'+section[current_tab]).hide();
     $('#'+section[current_tab]+'_step').attr("disabled", true);
      current_tab=current_tab-1;
      $('#'+section[current_tab]+'_step').attr("disabled", false);
      $('#'+section[current_tab]).show();
      $('#back').show();
   }
   $('#error_msg').html('');
   $('#create').hide();
   $('#next').show();
   $('#preview').hide();
  });
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
         if (!allAreFilled) return;
         if (!i.value) allAreFilled = false;
      })
       if (!allAreFilled) {
         $('#error_msg').html('Please fill all mandatory field');
         //return;
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
      width=(16.6*current_tab);
      $('.stepwizard-row').eq(0).attr("style","--width:"+width+"%");

    }
    if(current_tab==6){
      $('#next').hide();
      $('#create').show();
      $('#preview').show();
    }


   });
   $("#mobile").focusout(function(){
     var mobile = $("#mobile").val();
     $('#mobile_error').html('');
     $.ajax({
         url:"{{route('web.get.mobiledetails')}}",
         type: "POST",
         data: {
         mobile: mobile,
         _token: '{{csrf_token()}}'
         },
         dataType : 'json',
         success: function(result){
           if(result.status=="failed"){
             $('#mobile_error').html(result.message);
           }
         }
     });
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
   $('#vaccine_details').hide();
    function vaccineCheck(val){
      if(val=="yes"){
        $('#vaccine_details').show();
      }else{
        $('#vaccine_details').hide();
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
	function take_snapshotDocument() {
		Webcam.snap( function(data_uri) {
			$(".image-tag_document").val(data_uri);
			document.getElementById('results_document').innerHTML = '<img src="'+data_uri+'" width = "280px" height= "210px"/>';
		} );
	}
	$('#camera_mode_div').hide();
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




</script>
@endpush
