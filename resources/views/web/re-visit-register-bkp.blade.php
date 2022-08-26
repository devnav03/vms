@extends('web.layouts.inner-master')
@section('title','Visitor Management System | Re Visit Registration Page')
@section('content')
<style media="screen">
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
        <li class="nav-item active "><a class="nav-link" href="{{route('web.re-visit')}}">Re-Visit</a></li>
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
        {{Form::open(['route'=>['add.re-visit-registration'],  'method' => 'post', 'enctype'=>'multipart/form-data'])}}
        <div id="loader-p">
        </div>
            <div id="reg">
                <div style="text-align: right; padding: 0px 20px 0px 0px; margin-top: 26px; font-size: 16px;">
                    <span><b>Re Visit Registration</b></span>
                    <p style="font-size: 12px;">Please fill the below details</p>
                </div>
                <div class="container" id="visit_information" style="width:93%">
                    <div class="heading_dtl">
                       <span>Visitor Details</span>
                    </div>
                    <div class="row">
                        <div class="login-box col-md-4 col-sm-12 col-xs-12">
                                <label for="name">Visitor Name:<span class="text-danger">*</span></label>
                                <input placeholder="Enter name" type="text" name="name" size="30" value="{{@$datas->name}}" class="form-control nowarn" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" required>
                                @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
                        </div>
                        <div class="login-box col-md-4 col-sm-12 col-xs-12">
                                <label for="email">Visitor Email:</label>
                                <input placeholder="eg:- email@gmail.com" type="email" name="email" size="30" value="{{@$datas->email}}" class="form-control nowarn" >
                        </div>
                        <div class="login-box col-md-4 col-sm-12 col-xs-12">
                                <label for="phone">Visitor Phone:<span class="text-danger">*</span></label>
                                <input placeholder="Enter Phone" type="text" name="mobile" value="{{@$datas->mobile}}"  size="30" class="form-control nowarn" maxlength="10" required>
                                @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="login-box col-md-4 col-sm-12 col-xs-12">
                            <div>
                                <label for="gender">Select Gender:<span class="text-danger">*</span></label>
                                <select name="gender" class="form-control nowarn">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{@$datas->gender == 'male'?'selected':''}}>Male </option>
                                    <option value="female" {{@$datas->gender == 'female'?'selected':''}} >Female</option>
                                    <option value="other" {{@$datas->gender == 'other'?'selected':''}} >Other</option>
                                    
                                </select>
                                @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
                            </div>
                        </div>
                        <div class="login-box col-md-4 col-sm-12 col-xs-12">
                           <label for="service">Document Type:<span class="text-danger">*</span></label>
                           <select name="document_type" class="form-control nowarn" required>
                               <option value="{{old('document_type')}}">Select Document</option>
                               <option value="dl" {{$datas->document_type == 'dl' ? 'selected':''}}>Driving Licence</option>
                               <option value="adhar_card" {{$datas->document_type == 'adhar_card' ? 'selected':''}}>Aadhar Card</option>
                               <option value="govt_id_pf" {{$datas->document_type == 'govt_id_pf' ? 'selected':''}}>Govt Identity Proof </option>
                               <option value="Pancard" {{@old('document_type') =='Pancard'?'selected':''}}>Pancard</option>
                           </select>
                           @if($errors->has('document_type'))<p class="text-danger">{{ $errors->first('document_type') }}</p> @endif
                         </div>
                         <div class="login-box col-md-4 col-sm-12 col-xs-12">
                             <div>
                                 <label for="adhar">Visitor Adhar/Voter/Pan Card Id:<span class="text-danger">*</span></label>
                                 <input placeholder="Enter Adhar id" type="text" name="adhar_no" size="30" value="{{@$datas->adhar_no}}" class="form-control nowarn" maxlength="16" required>
                                 @if($errors->has('adhar_no'))<p class="text-danger">{{ $errors->first('adhar_no') }}</p> @endif
                             </div>
                         </div>
              				</div>
                      <div class="row">
  					             <div class="login-box col-md-4 col-sm-12 col-xs-12">
                            <label for="visite_duration">Visit Duration:<span class="text-danger">*</span></label>
                            <select name="visite_duration" id="visite_duration" class="form-control nowarn" required>
                                <option value="{{old('visite_duration')}}">Visit Duration</option>
                                <option value="15">15 Min </option>
                                <option value="30">30 Min </option>
                                <option value="45">45 Min</option>
                                <option value="60">1 Hour</option>
                                <option value="90">1.5 Hour</option>
                                <option value="120">2 Hour</option>
                                <option value="240">4 Hour</option>
                                <option value="1440">Full Day</option>
                            </select>
                            @if($errors->has('visite_duration'))<p class="text-danger">{{ $errors->first('visite_duration') }}</p> @endif
                      </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                          <label for="vehical_type">Vehicle Type:</label>
                          <select name="vehical_type" class="form-control nowarn">
                              <option value="{{old('visit_type')}}">Select Vehicle Type</option>
                              <option value="2 wheeler">2 wheeler</option>
                              <option value="4 wheeler">4 wheeler</option>
                          </select>
                          @if($errors->has('visit_type'))<p class="text-danger">{{ $errors->first('visit_type') }}</p> @endif
                      </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                          <div>
                              <label for="vehical_reg_num">Vehicle Registration Number:</label>
                              <input placeholder="Enter Vehicle Registration Number" onfocusout="vehivleValidate();" type="text"  name="vehical_reg_num" size="30" value="{{old('vehical_reg_num')}}" class="form-control nowarn" maxlength="16">
                              @if($errors->has('vehical_reg_num'))<p class="text-danger">{{ $errors->first('vehical_reg_num') }}</p> @endif
                          </div>
                      </div>
      	            </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                          <div>
                              <label for="visite_time">Visit Date Time:<span class="text-danger">*</span></label>
                              <input type="datetime-local" name="visite_time" size="30" value="{{old('visite_time')}}" class="form-control nowarn" required>
                              @if($errors->has('visite_time'))<p class="text-danger">{{ $errors->first('visite_time') }}</p> @endif
                          </div>
                      </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                          <label for="service">Purpose Of Visit:<span class="text-danger">*</span></label>
                          <select name="services" class="form-control nowarn" required>
                              <option value="{{old('services')}}">Select Services</option>
                              <option value="Official" {{$datas->services == 'Official' ? 'selected':''}}>Official</option>
                              <option value="Personal" {{$datas->services == 'Personal' ? 'selected':''}}>Personal</option>
                              <!-- <option value="Adhar Services Complaint" {{$datas->services == 'Adhar Services Complaint' ? 'selected':''}}>Adhar Services Complaint </option>
                              <option value="Birth Certificate" {{$datas->services == 'Birth Certificate' ? 'selected':''}}>Birth Certificate</option>
                              <option value="Death Certificate" {{$datas->services == 'Death Certificate' ? 'selected':''}}>Death Certificate</option> -->
                          </select>
                          @if($errors->has('services'))<p class="text-danger">{{ $errors->first('services') }}</p> @endif
                  </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                        <label for="country_id">Select Country:<span class="text-danger">*</span></label>
                        <select name="country_id" id="country_id" class="form-control nowarn" required>
                          <option value="{{old('country_id')}}">Select Country name</option>
                          @foreach($get_country as $country)
                          <option value="{{$country->id}}" {{$datas->country_id == $country->id ? 'selected':''}}>{{$country->name}}</option>
                          @endforeach
                        </select>
                        @if($errors->has('country_id'))<p class="text-danger">{{ $errors->first('country_id') }}</p> @endif
                      </div>

                    </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                         <label for="state_id">Select State:<span class="text-danger">*</span></label>
                         <select name="state_id" id="state_id" class="form-control nowarn" required>
                           <option value="{{old('state_id')}}">Select State Name</option>
                         </select>
                         @if($errors->has('state_id'))<p class="text-danger">{{ $errors->first('state_id') }}</p> @endif
                     </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                        <label for="city_id">Select City:<span class="text-danger">*</span></label>
                        <select name="city_id" id="city_id" class="form-control nowarn" required>
                          <option value="{{old('city_id')}}">Select City Name</option>
                        </select>
                        @if($errors->has('city_id'))<p class="text-danger">{{ $errors->first('city_id') }}</p> @endif
                      </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                          <label for="pincode">Pincode:</label>
                          <input placeholder="Enter Pincode" type="text" name="pincode" size="30" value="{{@$datas->pincode}}"  class="form-control nowarn" onkeypress="return (event.charCode > 47 && event.charCode < 58)">
                          @if($errors->has('pincode'))<p class="text-danger">{{ $errors->first('pincode') }}</p> @endif
                      </div>

                    </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                          <label for="adhar">Attached Document:<span class="text-danger">*</span></label>
                          <input placeholder="Enter Adhar attachment" type="file" name="attachmant" value="{{old('attachmant')}}" class="form-control nowarn" required>
                          @if($errors->has('attachmant'))<p class="text-danger">{{ $errors->first('attachmant') }}</p> @endif
                      </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                        <label for="address_1">Official addres:</label>
                        <textarea row="5" col="5" class="form-control nowarn" name="address_1">{{@$datas->address_1}}</textarea>
                        @if($errors->has('address_1'))<p class="text-danger">{{ $errors->first('address_1') }}</p> @endif
                      </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                        <label for="address_2">Residential address:<span class="text-danger">*</span></label>
                        <textarea row="5" col="5" class="form-control nowarn" name="address_2">{{@$datas->address_2}}</textarea>
                        @if($errors->has('address_2'))<p class="text-danger">{{ $errors->first('address_2') }}</p> @endif
                      </div>
                    </div>

                </div>
                <div class="container" id="visit_address" style="width:93%">
                    <div class="heading_dtl">
                       <span>Visit Address</span>
                    </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                         <label for="location_id">Select Location:<span class="text-danger">*</span></label>
                        <select name="location_id" id="location_id" class="form-control nowarn" required>
                          <option value="{{old('location_id')}}">Select Location name</option>
                          @foreach($locations as $location)
                          <option value="{{$location->id}}" {{$location->id == $datas->location_id ? 'selected':''}}>{{$location->name}}</option>
                          @endforeach
                        </select>
                        @if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
                      </div>
                      <div  class="login-box col-md-4 col-sm-12 col-xs-12">
                         <label for="building_id">Select Building:<span class="text-danger">*</span></label>
                        <select name="building_id" id="building_id" class="form-control nowarn" required style="display:block;">
                        </select>
                      </div>
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
            					<label for="department_id">Select Department:<span class="text-danger">*</span></label>
            						<select name="department_id" id="department_id" class="form-control nowarn" required style="display:block;">
            						</select>
            					</div>
                    </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                          <label for="officer">Select Officer:<span class="text-danger">*</span></label>
                          <select name="officer" id="officer_id" class="form-control nowarn" required>
                            <option value="{{old('officer')}}">Select Officer name</option>
                          </select>
                          @if($errors->has('officer'))<p class="text-danger">{{ $errors->first('officer') }}</p> @endif
                      </div>

                </div>
                </div>
                <div class="container" id="organization_information" style="width:93%">
                    <div class="heading_dtl">
                       <span>Visitor Address</span>
                    </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                                <label for="organization_name">Name:<span class="text-danger">*</span></label>
                                <input placeholder="Enter Organization Name" type="text" name="organization_name" value="{{@$datas->organization_name}}"  class="form-control nowarn">
                                @if($errors->has('organization_name'))<p class="text-danger">{{ $errors->first('organization_name') }}</p> @endif
                        </div>
                        <div class="login-box col-md-4 col-sm-12 col-xs-12">
                                <label for="orga_country_id">Select Country:<span class="text-danger">*</span></label>
                                <select name="orga_country_id" id="orga_country_id" class="form-control nowarn" required>
                                  <option value="{{old('orga_country_id')}}">Select Country Name</option>
                                  @foreach($get_country as $country)
                                  <option value="{{$country->id}}" {{$datas->orga_country_id == $country->id ? 'selected':''}}>{{$country->name}}</option>
                                  @endforeach
                                </select>
                                @if($errors->has('orga_country_id'))<p class="text-danger">{{ $errors->first('orga_country_id') }}</p> @endif
                        </div>
                        <div class="login-box col-md-4 col-sm-12 col-xs-12">
                            <label for="orga_state_id">Select State:<span class="text-danger">*</span></label>
                            <select name="orga_state_id" id="orga_state_id" class="form-control nowarn" required>
                              <option value="{{old('orga_state_id')}}">Select State Name</option>
                            </select>
                            @if($errors->has('orga_state_id'))<p class="text-danger">{{ $errors->first('orga_state_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
                        <label for="orga_city_id">Select City:<span class="text-danger">*</span></label>
                        <select name="orga_city_id" id="orga_city_id" class="form-control nowarn" required>
                          <option value="{{old('orga_city_id')}}">Select City Name</option>
                        </select>
                        @if($errors->has('orga_city_id'))<p class="text-danger">{{ $errors->first('orga_city_id') }}</p> @endif
                      </div>

                    </div>
                    <div class="heading_dtl">
                       <span>Visitor Image</span>
                    </div>
                    <div class="row">
                      <div class="login-box col-md-4 col-sm-12 col-xs-12">
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
                            <div id="my_camera"  style="margin-left: 20%;"></div>
                            <input class="btn btn-primary" type="button" value="Take Snapshot" onClick="take_snapshot()" style="width: 100%; margin-left: 15px;background-color: #107cab;">
                            <input type="hidden" name="image" value="{{old('image')}}" class="image-tag">
                            @if($errors->has('image'))
                            <p class="text-danger">{{ $errors->first('image') }}</p>
                            @endif
                        </div>
                        <div class="login-box">
                               <div id="results" style="color:#107cab; font-size: 14px;"><img  src="{{$image}}" alt="Card image cap" width = "280px" height= "210px" style="margin-top: -250px; margin-left: 20%"></div>
                        </div>
                      </div>
                      <div id="folder_mode_div">
                        <div class="login-box" >
                          <label for="">Select Image:<span class="text-danger">*</span></label>
                          <input type="file" name="image" id="image_1" value="{{old('image')}}" onchange="imageUploaded()" class="form-control">
                        </div>
                        <div class="login-box">
                               <div  style="color:#107cab; font-size: 14px;"><img  src="{{$image}}" alt="Card image cap" width = "280px" height= "210px"></div>
                        </div>
                      </div>


                    </div>
                </div>
                <div class="container" id="Asset_div" style="width:93%">
                    <div class="heading_dtl">
                       <span>Visitor Asset Information</span>
                    </div>
                    <div class="row add_assets">
                       <div class="col-md-12">
                            <div class="login-box col-md-3">
                                <div>
                                    <label>Name </label>
                                    <input placeholder="Enter Name" type="text" name="assets_name[]" value="" class="form-control nowarn">
                                </div>
                            </div>
                            <div class="login-box col-md-3">
                                <div>
                                    <label>Serial Number </label>
                                    <input placeholder="Enter Serial Name" type="text" name="assets_number[]" value="" class="form-control nowarn">
                                </div>
                            </div>
                             <div class="login-box col-md-3">
                                <div>
                                    <label>Brand</label>
                                    <input placeholder="Enter Brand" type="text" name="assets_brand[]" value="" class="form-control nowarn">
                                </div>
                            </div>
                            <div class="login-box col-md-3">
                                <label></label>
                                <div style="cursor: pointer;"onclick="add_assets()">
                                   <img src="{{asset('icons8-add-96.png')}}" style="width:40px; text-align: center;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="login-box" style="width: 100%;">
                            <span style="color: #2e98c5; font-size: 16px;"><input type="checkbox" name="carrying_device" id="carrying_device" style="width: 60px;" class="nowarn"><b><u>Are You Carrying Storage Device?</u></b> </span>
                        </div>
                        <div class="login-box" id="pan">
                            <div>
                                <label for="adhar">No. of Pan Drives:</label>
                                <input placeholder="Enter No. of Pan Drives" type="number" name="pan_drive" value="{{old('pan_drive')}}" class="form-control nowarn">
                            </div>
                        </div>

                         <div class="login-box" id="hard">
                            <div>
                                <label for="adhar">No. of Hard Disks:</label>
                               <input placeholder="Enter No. of Hard Disks" type="number" name="hard_disk" value="{{old('hard_disk')}}" class="form-control nowarn">

                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="container" id="covid_declaration" style="width:93%">
                      <div class="heading_dtl">
                         <span>Covid Declaration Form</span>
                      </div>
                        <div class="row">
                            <div class="login-box">
                                <div>
                                    <label for="vaccine">Have you taken the vaccine?:<span class="text-danger">*</span></label>
                                    <select name="vaccine" class="form-control nowarn" required onchange="vaccineCheck(this.value);">
                                        <option value="{{old('vaccine')}}">Select Option</option>
                                        <option value="yes">Yes </option>
                                        <option value="no">No</option>
                                    </select>
                                    @if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
                                </div>
                            </div>
                            <div class="login-box">
                                <div>
                                    <label for="symptoms">Are You Currently Experiencing Any Following Symptoms?:<span class="text-danger">*</span></label>
                                    <select name="symptoms" class="form-control nowarn" required>
                                        <option value="{{old('symptoms')}}">Select Option</option>
                                        @foreach($symptoms as $sym)
                                        <option value="{{$sym->name}}">{{$sym->name}} </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('symptoms'))<p class="text-danger">{{ $errors->first('symptoms') }}</p> @endif
                                </div>
                            </div>
                        </div>
                        <div id="vaccine_details" class="row">
                          <div class="login-box">
                              <div>
                                  <label for="vaccine">Which dose of vaccine have you taken? :<span class="text-danger">*</span> </label>
                                  <select name="vaccine_count" class="form-control nowarn">
                                      <option value="1">First Dose</option>
                                      <option value="2">Second Dose </option>
                                      <option value="3">Booster Dose</option>
                                  </select>
                                  @if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
                              </div>
                          </div>
                          <div class="login-box">
                              <div>
                                  <label for="vaccine">Which vaccine you have taken? :<span class="text-danger">*</span> </label>
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
                            <div class="login-box">
                                <div>
                                    <label for="states">Have you traveled to any covid affected State in the past 14 days? :<span class="text-danger">*</span></label>
                                    <select name="states" class="form-control nowarn" required>
                                        <option value="{{old('states')}}">Select Option</option>
                                        <option value="yes">Yes </option>
                                        <option value="no">No</option>
                                    </select>
                                    @if($errors->has('states'))<p class="text-danger">{{ $errors->first('states') }}</p> @endif
                                </div>
                            </div>
                            <div class="login-box">
                                <div>
                                    <label for="patient">Did you get in touch with any COVID positive patient in the last 15 days? :<span class="text-danger">*</span></label>
                                    <select name="patient" class="form-control nowarn" required>
                                        <option value="{{old('patient')}}">Select Option</option>
                                        <option value="yes">Yes </option>
                                        <option value="no">No</option>
                                    </select>
                                    @if($errors->has('patient'))<p class="text-danger">{{ $errors->first('patient') }}</p> @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="login-box">
                                <div>
                                    <label for="temprature">Current Body Temprature:<span class="text-danger">*</span></label>
                                    <input placeholder="Enter Current Body Temprature" type="text" name="temprature" size="30" value="{{old('temprature')}}" class="form-control nowarn" maxlength="5" required>
                                    @if($errors->has('temprature'))<p class="text-danger">{{ $errors->first('temprature') }}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container" id="visit_type_div" style="width:93%">
                      <div class="heading_dtl">
                         <span>Visitor Type</span>
                      </div>
                      <div class="row">
                          <div class="login-box">
                              <div>
                                  <label for="visit_type">Visitor Type:<span class="text-danger">*</span></label>
                                  <select name="visit_type" id="visit_type" class="form-control nowarn" required>
                                      <option value="single">Single</option>
                                      <option value="group">Group</option>
                                  </select>
                              </div>
                              <input type="hidden" id="sr_no" value="1">
                          </div>
                          <div class="row add group">
                              <div class="col-md-12">
                                  <div class="col-md-2">
                                      <label>Name</label>
                                      <input placeholder="Enter Name" type="text" name="group_name[1][group_name]" size="30" class="form-control nowarn" maxlength="16">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Mobile No.</label>
                                      <input placeholder="Enter Mobile No." type="text" name="group_mobile[1][group_mobile]" size="30" class="form-control nowarn" maxlength="16">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Gender</label>
                                      <select name="group_gender[1][group_gender]" class="form-control nowarn">
                                          <option value="male">Male </option>
                                          <option value="female">Female</option>
                                          <option value="other">Other</option>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <label>ID Proof</label>
                                      <input placeholder="Enter ID" type="text" name="group_id_proof[1][group_id_proof]" size="30" class="form-control nowarn" maxlength="16">
                                  </div>

      							                 <div class="col-md-1">
                                     <label>Document</label>

      							                <input id="file-input"  type="file" name="group_attchment[1][attchment]"  class="form-control"/>

                                  </div>
                                  <div class="col-md-1">
                                      <label></label>
                                      <div id="results_1"><img src="{{asset('profile.png')}}" width="40px;"></div>
                                  </div>
                                  <div class="col-md-1">
                                      <label></label>
                                      <div data-toggle="modal" data-target="#exampleModal_1" style="cursor: pointer;">
                                          <img src="{{asset('icons8-upload-48.png')}}" style="width:40px;">
                                      </div>
                                  </div>
                                  <div class="col-md-1">
                                      <label></label>
                                      <div style="cursor: pointer;"onclick="add()">
                                          <img src="{{asset('icons8-add-96.png')}}" style="width:40px;">
                                      </div>
                                  </div>
                              </div>
                          </div>

                      </div>
                    </div>
                  </div>

            <div id="otp_check">
                    <h1>Otp Verify</h1>
                    <p>Please verify your otp to complete registration process.</p>
                    <p class="text-success text-center"></p>
                    <hr/>
                    <div class="login-box">
                        <div>
                            <label for="otp">Enter otp:</label>
                              <input type="text" class="form-control nowarn" value="" name="otp" placeholder="Enter your otp" maxlength="6">
                            <small class="text-danger"></small>
                        </div>
                    </div>
                </div>

                <div align="center">
                  <div class="form-group clearfix">
                      <button class="btn btn-primary" type="submit" id="create">Submit</button>
                      <a style="background: #25cfea;" class="btn btn-default" id="back">Back</a>
                      <a style="background: #25cfea;" class="btn btn-default" id="next">Next</a>
                        <a style="background: #25cfea;" class="btn btn-default" id="preview">Preview</a>
                      <span id="error_msg" style="color:red; font-size:20px;"></span>
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
                      <label for="">Select Image</label>
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
                      <label for="">Select Image</label>
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
                      <label for="">Select Image</label>
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
                    <h5 class="modal-title" id="exampleModalLabel">Take Image8</h5>
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

        {{Form::close()}}
    </div>
@endsection

@push('scripts')
    <script>

    var current_tab=1;
    var section ={'1':'visit_information','2':'visit_address','3':'organization_information','4':'Asset_div','5':'covid_declaration','6':'visit_type_div'};
    $(document).ready(function(){
        var i=1;
        while (i < 11) {
         $('#my_camera_'+i+'_div').hide();
         i++;
       }
       $("#loader-p").hide();
       $.each(section,function(key,val){
         if(key!=1){
            $('#'+val).hide();
         }
       });
       $("#exampleModal_1").addClass("hide");
        imageMode('folder');
        // var from_date='<?php echo $datas->employee_type;?>';
        // if(from_date =='permanent'){
        //     $('#from_date').hide();
        // }else{
        //     $('#from_date').show();
        // }
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
        $('#from_date').hide();
        $("#employee_type").on('change', function(){
            var from_date = this.value;
            if(from_date =='permanent'){
                $('#from_date').hide();
            }else{
                $('#from_date').show();
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
            $(".add").append('<div class="delete col-md-12"><div class="col-md-2"><label>Name</label><input placeholder="Enter Name" type="text" name="group_name['+group_count+'][group_name]" size="30" class="form-control nowarn" maxlength="16"></div><div class="col-md-2"><label>Mobile No.</label><input placeholder="Enter Mobile No." type="text" name="group_mobile['+group_count+'][group_mobile]" size="30" class="form-control nowarn" maxlength="16"></div><div class="col-md-2"><label>Gender</label><select name="group_gender['+group_count+'][group_gender]" class="form-control nowarn"><option value="male">Male </option><option value="female">Female</option><option value="other">Other</option></select></div><div class="col-md-2"><label>ID Proof</label><input placeholder="Enter ID" type="text" name="group_id_proof['+group_count+'][group_id_proof]" size="30" class="form-control nowarn" maxlength="16"></div><div class="col-md-1"><label>Document</label><input id="file-input"  type="file" name="group_attchment['+group_count+'][attchment]" value="" class="form-control"/></div><div class="col-md-1"><label></label><div id="results_'+num+'"><img src="{{asset("profile.png")}}" width="40px;"></div></div><div class="col-md-1"><label></label><div data-toggle="modal" data-target="#exampleModal_'+num+'" style="cursor: pointer;"><img src="{{asset("icons8-upload-48.png")}}" style="width:40px;"></div></div><div class="col-md-1"><label></label><div id="add_delete" style="cursor: pointer";><img src="{{asset("icons8-minus-64.png")}}" style="width:35px; text-align:right;"></div></div></div>');
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
        })

    </script>

    <script language="JavaScript">
        $('#otp_check').hide();
        @if(session()->has('otp_true'))
            $('#reg').hide();
            $('#otp_check').show();
        @endif

        //webcam code
        Webcam.set({
            width: 350,
            height: 200,
            image_format: 'jpeg',
            jpeg_quality: 500
        });
        // Webcam.attach( '#my_camera' );

        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'" width = "280px" height= "210px" style="margin-top: -250px; margin-left: 20%"/>';
            } );
        }

       // Webcam.attach('#my_camera_1');
       // Webcam.attach('#my_camera_2');
       // Webcam.attach('#my_camera_3');
       // Webcam.attach('#my_camera_4');
       // Webcam.attach('#my_camera_5');
       // Webcam.attach('#my_camera_6');
       // Webcam.attach('#my_camera_7');
       // Webcam.attach('#my_camera_8');
       // Webcam.attach('#my_camera_9');
       // Webcam.attach('#my_camera_10');

        function take_snapshot_0(num) {
            Webcam.snap( function(data_uri) {
                $(".image-tag_"+num).val(data_uri);
                document.getElementById('results_'+num).innerHTML = '<img src="'+data_uri+'" width = "80px" />';
            } );
        }

        //endwebcam
        function getOfficer(depart_id){
          $("#officer_id").html('');
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
                      $("#officer_id").append('<option value="">Select Officer</option>');
                        $.each(result.states,function(key,value){
            							var old_officer='<?php echo $datas->officer_id;?>';
            							if(old_officer==value.id){
            								$("#officer_id").append('<option value="'+value.id+'" selected>'+value.name+'</option>');
            							}else{
            								$("#officer_id").append('<option value="'+value.id+'">'+value.name+'</option>');
            							}


                        });
                        $('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
        }
        
        $('#department_id').on('change', function() {
              $("#loader-p").show();
                var depart_id = this.value;
                getOfficer(depart_id);
            });
		$('#country_id').on('change', function() {
                var country_id = this.value;
                $("#city_id").html('');
				$("#state_id").html('');
				getState(country_id);
            });
			function getState(country_id){
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
                      $("#state_id").append('<option value="">Select State</option>');
          						var old_state='<?php echo $datas->state_id;?>';
          						$.each(result.states,function(key,value){
          							if(old_state==value.id){
          								$("#state_id").append('<option value="'+value.id+'" selected >'+value.name+'</option>');
          							}else{
          								$("#state_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
          							}

                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
			}
		$('#state_id').on('change', function() {
                var state_id = this.value;
                $("#city_id").html('');
				getCity(state_id);

            });
			function getCity(state_id){
                $("#loader-p").show();
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
                      $("#city_id").append('<option value="">Select City</option>');
          						var old_city='<?php echo $datas->city_id;?>';
                                  $.each(result.city,function(key,value){
          							if(old_city==value.id){
          								$("#city_id").append('<option value="'+value.id+'" selected>'+value.name+'</option>');
          							}else{
          								$("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
          							}

                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
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
				  $("#officer_id").html('');
				  $.ajax({
					  url:"{{url('/web-get-building')}}",
					  type: "POST",
					  data: {
					  location_id: location_id,
						_token: '{{csrf_token()}}'
					  },
					dataType : 'json',
					success: function(result){
            $("#loader-p").hide();
            $("#building_id").append('<option value="">Select Building</option>');
            var old_building_id='<?php echo $datas->building_id;?>';
            console.log(old_building_id);
					  $.each(result,function(key,value){
              if(old_building_id==value.id){
                $("#building_id").append('<option value="'+value.id+'" selected>'+value.name+'</option>');
              }else{
                $("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
              }
					  
					  if(key==0){
						getDepartment(value.id);
						  }
					  });

					 }
				 });
			}
		  function getDepartment(building_id){
        $("#loader-p").show();
			$("#officer_id").html('');
			  $.ajax({
				  url:"{{url('/web-get-department')}}",
				  type: "POST",
				  data: {
				  building_id: building_id,
				  _token: '{{csrf_token()}}'
				  },
				  dataType : 'json',
				  success: function(result){
            $("#loader-p").hide();
            var old_department_id='<?php echo $datas->department_id;?>';
            getOfficer(old_department_id);
            $("#department_id").append('<option value="">Select Department</option>');
					  $.each(result,function(key,value){
              if(old_department_id==value.id){
                $("#department_id").append('<option value="'+value.id+'" selected>'+value.name+'</option>');

              }else{
                $("#department_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
              }
							
						});
					}
			  });
			}

			$('#country_id').on('change', function() {
                var country_id = this.value;
                $("#city_id").html('');
				$("#state_id").html('');
				getState(country_id);
            });
			function getState(country_id){
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
                      $("#state_id").append('<option value="">Select State</option>');
          						var old_state='<?php echo $datas->state_id;?>';
          						$.each(result.states,function(key,value){
          							if(old_state==value.id){
          								$("#state_id").append('<option value="'+value.id+'" selected >'+value.name+'</option>');
          							}else{
          								$("#state_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
          							}

                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
			}
		$('#state_id').on('change', function() {
                var state_id = this.value;
                $("#city_id").html('');
				getCity(state_id);

            });
			function getCity(state_id){
              $("#loader-p").show();
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
                      $("#city_id").append('<option value="">Select City</option>');
          						var old_city='<?php echo $datas->city_id;?>';
                                  $.each(result.city,function(key,value){
          							if(old_city==value.id){
          								$("#city_id").append('<option value="'+value.id+'" selected>'+value.name+'</option>');
          							}else{
          								$("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
          							}

                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
			}

			$('document').ready(function(){
				var old_country='<?php echo $datas->country_id;?>';
				getState(old_country);
				var old_state='<?php echo $datas->state_id;?>';
				getCity(old_state);
				var old_location='<?php echo $datas->location_id;?>';
				getBuilding(old_location);
				var old_orga_country='<?php echo $datas->orga_country_id;?>';
				getOrgaState(old_orga_country);
				var old_orga_state='<?php echo $datas->orga_state_id;?>';
				getOrgaCity(old_orga_state);
			});
			$('#orga_country_id').on('change', function() {
                var country_id = this.value;
                $("#orga_city_id").html('');
				$("#orga_state_id").html('');
				getOrgaState(country_id);
            });
			function getOrgaState(country_id){
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
                        $("#orga_state_id").append('<option value="">Select State</option>');
          						var old_state='<?php echo $datas->orga_state_id;?>';
              						$.each(result.states,function(key,value){
              							if(old_state==value.id){
              								$("#orga_state_id").append('<option value="'+value.id+'" selected >'+value.name+'</option>');
              							}else{
              								$("#orga_state_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
              							}

                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
			}
		    $('#orga_state_id').on('change', function() {
          var state_id = this.value;
          $("#orga_city_id").html('');
				      getOrgaCity(state_id);

      });
			function getOrgaCity(state_id){
        $("#loader-p").show();
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
                      $("#orga_city_id").append('<option value="">Select City</option>');
        						var old_city='<?php echo $datas->orga_city_id;?>';
                    $.each(result.city,function(key,value){
        							if(old_city==value.id){
        								$("#orga_city_id").append('<option value="'+value.id+'" selected>'+value.name+'</option>');
        							}else{
        								$("#orga_city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
        							}

                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
			}

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
           });
           var format = /[!@#$%^&*()_+\=\[\]{};':"\\|,.<>\/?]+/;
           var vehical_reg_num=$('#vehical_reg_num').val();
            if(format.test(vehical_reg_num)){
              alert("Vehicle Number Not Allowed Please check number");
            }
            if (!allAreFilled) {
              $('#error_msg').html('Please fill all mandetory field');
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
        function vehivleValidate(){
          var format = /[!@#$%^&*()_+\=\[\]{};':"\\|,.<>\/?]+/;
          var vehical_reg_num=$('#vehical_reg_num').val();
           if(format.test(vehical_reg_num)){
               alert("Vehicle Number Not Allowed Please check number");
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
