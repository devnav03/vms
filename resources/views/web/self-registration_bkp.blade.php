@extends('web.layouts.inner-master')
@section('content')
<style type="text/css">
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
      margin-top: 15px;
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
</style>

    <div class="clear" style="background-color: #eddd;">
        <ul id="nav" class="flush-left" style="height: 25px;">
            <li><a class="home" href="{{route('web.home')}}">Home</a></li>
            <li><a class="active new" href="{{route('web.self-registration')}}">New Visit</a></li>
            <li><a class="status" href="{{route('web.re-visit')}}">Re-Visit</a></li>
            <li><a href="{{route('web.status')}}" class="status">Know Visit Status</a></li>
            <!-- <li><a href="{{route('web.download')}}" class="status">Download Slip</a></li> -->
            <li class="nav-item "><a class="nav-link" href="{{route('web.qr-code')}}">Scan QR Code</a></li>
			  @auth
				  <li class="nav-item"><a class="nav-link" href="{{url('guard/dashboard')}}">Guard Dashboard</a></li>				  
				  <li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.logout')}}">Logout</a></li>			  
				  
				  @elseguest
					<li class="nav-item"><a class="nav-link" href="{{route('web.gaurd.login')}}">Guard Login</a></li>
				  @endauth
        </ul>
    </div>
    <div id="content">
        {{Form::open(['route'=>['add.self.registration'],  'method' => 'post', 'enctype'=>'multipart/form-data'])}}
            <div id="reg">
              <!-- <div style="text-align: right; padding: 0px 20px 0px 0px; margin-top: 26px; font-size: 16px;">
                  <span><b>New Visit</b></span>
                  <p style="font-size: 12px;">Please fill the below details</p>
              </div> -->
              <div class="container">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                      <div class="stepwizard-step col-xs-3">
                          <a  type="button" id="visit_information_step" class="btn btn-default btn-circle">1</a>
                          <p><small>Visiter Information</small></p>
                      </div>
                        <div class="stepwizard-step col-xs-3">
                            <a  type="button" id="visit_address_step" class="btn btn-success btn-circle" disabled="disabled">2</a>
                            <p><small>Visit Address</small></p>
                        </div>

                        <div class="stepwizard-step col-xs-3">
                            <a  type="button" id="organization_information_step" class="btn btn-default btn-circle" disabled="disabled">3</a>
                            <p><small>Visiter Organization Address</small></p>
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
              <div class="container" id="visit_information" style="width:93%">
                  <div class="heading_dtl">
                     <span>Vistor Details</span>
                  </div>
                  <div class="row">
                      <div class="login-box col-md-4">
                          <div>
                              <label for="name">Visitor Name:</label>
                              <input placeholder="Enter name" type="text" name="name" size="30" value="{{old('name')}}" class="form-control nowarn" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" required>
                              @if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
      					          </div>
                      </div>
                      <div class="login-box col-md-4">
                          <div>
                              <label for="email">Visitor Email:</label>
                              <input placeholder="eg:- email@gmail.com" type="email" name="email" size="30" value="{{old('email')}}" class="form-control nowarn" >
                          </div>
                      </div>
                      <div class="login-box col-md-4">
                          <div>
                              <label for="phone">Visitor Phone:</label>
                              <input placeholder="Enter Phone" type="text" id="mobile" name="mobile" value="{{old('mobile')}}"  size="30" class="form-control nowarn"  maxlength="10" required>
                              @if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
                              <p id="mobile_error" style="color:red"></p>
      					          </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="login-box col-md-4">
                        <div>
                            <label for="gender">Select Gender: </label>
                            <select name="gender" class="form-control nowarn" required>
                                <option value="{{old('gender')}}">Select Gender</option>
                                <option value="male" {{@old('gender') =='male'?'selected':''}}>Male </option>
                                <option value="female" {{@old('gendergender') =='female'?'selected':''}}>Female</option>
                                <option value="other" {{@old('gender') =='other'?'selected':''}}>Other</option>
                            </select>
                            @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
                        </div>
                    </div>
                    <div class="login-box col-md-4">
                        <label for="service">Document type: </label>
                        <select name="document_type" class="form-control nowarn" required>
                            <option value="{{old('document_type')}}">Select document</option>
                            <option value="dl" {{@old('document_type') =='dl'?'selected':''}}>Driving Licence</option>
                            <option value="adhar_card" {{@old('document_type') =='adhar_card'?'selected':''}}>Adhar Card</option>
                            <option value="govt_id_pf" {{@old('document_type') =='govt_id_pf'?'selected':''}}>Govt Identity Proof </option>
                        </select>
                        @if($errors->has('document_type'))<p class="text-danger">{{ $errors->first('document_type') }}</p> @endif

                    </div>
                    <div class="login-box col-md-4">

                      <label for="adhar">Attached Document:</label>
                      <input placeholder="Enter Adhar attachment" type="file" name="attachmant" value="{{old('attachmant')}}" class="form-control nowarn" required>
                      @if($errors->has('attachmant'))<p class="text-danger">{{ $errors->first('attachmant') }}</p> @endif
                  </div>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
                      <div>
                          <label for="adhar">Id Proof No.:</label>
                          <input placeholder="Enter id no." type="text" name="adhar_no" size="30" value="{{old('adhar_no')}}" class="form-control nowarn" maxlength="16" required>
                          @if($errors->has('adhar_no'))<p class="text-danger">{{ $errors->first('adhar_no') }}</p> @endif
                      </div>
                  </div>
                  <div class="login-box col-md-4">
                      <div>
                          <label for="visite_time">Visit Date Time:</label>
                          <input type="datetime-local" name="visite_time" size="30" value="{{old('visite_time')}}" class="form-control nowarn" required>
                          @if($errors->has('visite_time'))<p class="text-danger">{{ $errors->first('visite_time') }}</p> @endif
                      </div>
                  </div>
                  <div class="login-box col-md-4">
                    <label for="vehical_type">Vehicle Type:</label>
                          <select name="vehical_type" class="form-control nowarn">
                              <option value="{{old('vehical_type')}}">Select Vehical type</option>
                              <option value="2 wheeler" {{@old('vehical_type') =='2 wheeler'?'selected':''}}>2 wheeler</option>
                              <option value="4 wheeler" {{@old('vehical_type') =='4 wheeler'?'selected':''}}>4 wheeler</option>
                          </select>
                          @if($errors->has('vehical_type'))<p class="text-danger">{{ $errors->first('vehical_type') }}</p> @endif
                  </div>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
                        <label for="vehical_reg_num">Vehicle Registration Number:</label>
                      <input placeholder="DL-8C-0535" type="text" onfocusout="vehivleValidate();" id="vehical_reg_num" name="vehical_reg_num"  size="30" value="{{old('vehical_reg_num')}}" class="form-control nowarn" >
                      @if($errors->has('vehical_reg_num'))<p class="text-danger">{{ $errors->first('vehical_reg_num') }}</p> @endif
                  </div>
                  <!-- pattern="^[A-Z]{2}[-][0-9]{1,2}[-][A-Z]{1,2}[-][0-9]{3,4}$" -->
                  <!-- pattern="/^[a-zA-Z0-9.-]*$/" -->
                  <div class="login-box col-md-4">
                    <label for="visite_duration">Visit Duration:</label>
                       <select name="visite_duration" id="visite_duration" class="form-control nowarn" required>
                           <option value="{{old('visite_duration')}}">Visit Duration</option>
                           <option value="15" {{@old('visite_duration') =='15'?'selected':''}}>15 Min </option>
                           <option value="30" {{@old('visite_duration') =='30'?'selected':''}}>30 Min </option>
                           <option value="45" {{@old('visite_duration') =='45'?'selected':''}}>45 Min</option>
                           <option value="60" {{@old('visite_duration') =='60'?'selected':''}}>1 Hour</option>
                       </select>
                       @if($errors->has('visite_duration'))<p class="text-danger">{{ $errors->first('visite_duration') }}</p> @endif
                  </div>
                  <div class="login-box col-md-4">
                    <label for="country_id">Select Country: </label>
                    <select name="country_id" id="country_id" class="form-control nowarn" required>
                      <option value="{{old('country_id')}}">Select Country name</option>
                      @foreach($get_country as $country)
                      <option value="{{$country->id}}" {{@old('country_id') ==$country->id?'selected':''}} >{{$country->name}}</option>
                      @endforeach
                    </select>
                    @if($errors->has('country_id'))<p class="text-danger">{{ $errors->first('country_id') }}</p> @endif
                  </div>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
                  <label for="state_id">Select State: </label>
                      <select name="state_id" id="state_id" class="form-control nowarn" required>
                        <option value="{{old('state_id')}}">Select State Name</option>
                      </select>
                      @if($errors->has('state_id'))<p class="text-danger">{{ $errors->first('state_id') }}</p> @endif
                  </div>
                  <div class="login-box col-md-4">
                    <label for="city_id">Select City: </label>
                          <select name="city_id" id="city_id" class="form-control nowarn" required>
                            <option value="{{old('city_id')}}">Select City Name</option>
                          </select>
                          @if($errors->has('city_id'))<p class="text-danger">{{ $errors->first('city_id') }}</p> @endif
                  </div>
                  <div class="login-box col-md-4">
                    <label for="pincode">Pincode:</label>
                        <input placeholder="Enter Pincode" type="text" name="pincode" size="30" value="{{old('pincode')}}" class="form-control nowarn" onkeypress="return (event.charCode > 47 && event.charCode < 58)" required>
                        @if($errors->has('pincode'))<p class="text-danger">{{ $errors->first('pincode') }}</p> @endif
          				</div>

        				</div>
                <div class="row">
                  <div class="login-box col-md-4">
                      <label for="address_1">Address-1:</label>
                      <textarea row="5" col="5" class="form-control nowarn" name="address_1" required>{{old('address_1')}}</textarea>
                      @if($errors->has('address_1'))<p class="text-danger">{{ $errors->first('address_1') }}</p> @endif
            			</div>
                  <div class="login-box col-md-4">
                    <label for="address_2">Address-2:</label>
                    <textarea row="5" col="5" class="form-control nowarn" name="address_2" required>{{old('address_2')}}</textarea>
                    @if($errors->has('address_2'))<p class="text-danger">{{ $errors->first('address_2') }}</p> @endif
            		  </div>
                </div>
                <!-- <div class="row">
                  <div class="login-box col-md-4">
                      <label for="employee_type">Employee Type:</label>
                      <select class="form-control" name="employee_type" id="employee_type">
                        <option value="permanent">Permanent</option>
                        <option value="temprary">Temprary</option>
                        <option value="contract_base">Contract Base</option>
                      </select>
                      @if($errors->has('employee_type'))<p class="text-danger">{{ $errors->first('employee_type') }}</p> @endif
            			</div>
                  <div id="from_date">
                    <div class="login-box col-md-4">
                      <label for="employee_from_date">From Date:</label>
                      <input type="datetime-local" class="form-control" name="employee_from_date" value="">
                      @if($errors->has('employee_from_date'))<p class="text-danger">{{ $errors->first('employee_from_date') }}</p> @endif
              		  </div>
                    <div class="login-box col-md-4">
                      <label for="employee_till_date">Till Date:</label>
                      <input type="datetime-local"  class="form-control" name="employee_till_date" value="">
                      @if($errors->has('employee_till_date'))<p class="text-danger">{{ $errors->first('employee_till_date') }}</p> @endif
              		  </div>
                  </div>
                </div> -->

              </div>
              <div class="container" id="visit_address" style="width:93%">
                <div class="heading_dtl">
                   <span>Visit Address</span>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
        						 <label for="location_id">Select Location: </label>
        						<select name="location_id" id="location_id" class="form-control nowarn" required>
        							<option value="{{old('location_id')}}">Select Location name</option>
        							@foreach($locations as $location)
        							<option value="{{$location->id}}" {{@old('location_id') ==$location->id?'selected':''}}>{{$location->name}}</option>
        							@endforeach
        						</select>
        						@if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
        					</div>
                  <div  class="login-box col-md-4">
        						 <label for="building_id">Select Building: </label>
        						<select name="building_id" id="building_id" class="form-control nowarn" required style="display:block;">
        						</select>
        					</div>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
        					<label for="department_id">Select Department: </label>
        						<select name="department_id" id="department_id" class="form-control nowarn" required style="display:block;">
        						</select>
        					</div>
                  <div class="login-box col-md-4">
                     <div>
                         <label for="officer">Select Officer: </label>
                         <select name="officer" id="officer_id" class="form-control nowarn" required>
                           <option value="{{old('officer')}}">Select Officer name</option>
                         </select>
                         @if($errors->has('officer'))<p class="text-danger">{{ $errors->first('officer') }}</p> @endif
                     </div>
                 </div>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
                    <label for="service">Purpose Of Visit: </label>
                    <select name="services" class="form-control nowarn" required>
                        <option value="{{old('services')}}">Select Services</option>
                        <option value="Official" {{@old('services') =='Official'?'selected':''}}>Official</option>
                        <option value="Personal" {{@old('services') =='Personal'?'selected':''}}>Personal</option>
                        <option value="Adhar Services Complaint" {{@old('Adhar Services Complaint') =='Official'?'selected':''}}>Adhar Services Complaint </option>
                        <option value="Birth Certificate" {{@old('services') =='Birth Certificate'?'selected':''}}>Birth Certificate</option>
                        <option value="Death Certificate" {{@old('services') =='Death Certificate'?'selected':''}}>Death Certificate</option>
                    </select>
                    @if($errors->has('services'))<p class="text-danger">{{ $errors->first('services') }}</p> @endif
                  </div>
                </div>
              </div>
              <div class="container" id="organization_information" style="width:93%">
                <div class="heading_dtl">
                   <span>Visiter Organization Address</span>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
                      <label for="organization_name">Organization Name:</label>
                      <input placeholder="Enter Organization Name" type="text" name="organization_name"  value="{{old('organization_name')}}" class="form-control nowarn">
                      @if($errors->has('organization_name'))<p class="text-danger">{{ $errors->first('organization_name') }}</p> @endif
                  </div>
                  <div class="login-box col-md-4">
                    <label for="orga_country_id">Select Organization Country: </label>
                    <select name="orga_country_id" id="orga_country_id" class="form-control nowarn" required>
                      <option value="{{old('orga_country_id')}}">Select Country name</option>
                      @foreach($get_country as $country)
                      <option value="{{$country->id}}" {{@old('orga_country_id') ==$country->id?'selected':''}}>{{$country->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="login-box col-md-4">
                    <label for="orga_state_id">Select Organization State: </label>
                    <select name="orga_state_id" id="orga_state_id" class="form-control nowarn" required>
                      <option value="{{old('orga_state_id')}}">Select  State Name</option>
                    </select>
                    @if($errors->has('orga_state_id'))<p class="text-danger">{{ $errors->first('orga_state_id') }}</p> @endif
                  </div>
                </div>
                <div class="row">
                  <div class="login-box col-md-4">
                    <label for="orga_city_id">Select Organization City: </label>
                    <select name="orga_city_id" id="orga_city_id" class="form-control nowarn" required>
                      <option value="{{old('orga_city_id')}}">Select City Name</option>
                    </select>
                    @if($errors->has('orga_city_id'))<p class="text-danger">{{ $errors->first('orga_city_id') }}</p> @endif
                </div>
                <div class="login-box col-md-4">
                  <label for="orga_pincode">Organization Pincode:</label>
                  <input placeholder="Enter Pincode" type="text" name="orga_pincode" size="30" value="{{old('orga_pincode')}}" class="form-control nowarn" onkeypress="return (event.charCode > 47 && event.charCode < 58)" required>
                  @if($errors->has('orga_pincode'))<p class="text-danger">{{ $errors->first('orga_pincode') }}</p> @endif
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="">Image Mode</label>
                  <select class="form-control" name="image_mode" onchange="imageMode(this.value);">
                    <option value="folder">Folder</option>
                    <option value="camera">camera</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div  id="camera_mode_div">
                  <div class="login-box">
                      <label style="margin-left: 15px;">Image</label>
                      <div id="my_camera"  style="margin-left: 20%;"></div>
                      <input class="btn btn-primary" type="button" value="Take Snapshot" onClick="take_snapshot()" style="width: 100%; margin-left: 15px;background-color: #107cab;">
                      <input type="hidden" name="image" value="{{old('image')}}" class="image-tag">
                      @if($errors->has('image'))
                      <p class="text-danger">{{ $errors->first('image') }}</p>
                      @endif
                  </div>
                  <div class="login-box">
                      <div id="results" style="color:#107cab; font-size: 14px;">Your captured image will appear here...</div>
                  </div>
                </div>

                  <div class="login-box" id="folder_mode_div">
                    <label for="">select Image</label>
                    <input type="file" name="image" id="image_1" value="{{old('image')}}" onchange="imageUploaded()" class="form-control">
                  </div>
                </div>

              </div>
              <div class="container" id="Asset_div" style="width:93%">
                <div class="heading_dtl">
                   <span>Asset</span>
                </div>
                <div class="row add_assets">
                   <div class="col-md-12">
                        <div class="login-box col-md-3">
                            <label>Name </label>
                            <input placeholder="Enter Name" type="text" name="assets_name[]" value="" class="form-control nowarn">
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
                        <span style="color: #2e98c5; font-size: 16px;"><input type="checkbox"  name="carrying_device" id="carrying_device" style="width: 60px;" class="nowarn"><b><u>Are you carrying storage device?</u></b> </span>
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
                            <label for="vaccine">Have you taken the vaccine? : </label>
                            <select name="vaccine" class="form-control nowarn" required>
                                <option value="{{old('vaccine')}}">Select Option</option>
                                <option value="yes">Yes </option>
                                <option value="no">No</option>
                            </select>
                            @if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
                        </div>
                    </div>
                    <div class="login-box">
                        <div>
                            <label for="symptoms">Are you currently experiencing any following symptoms? : </label>
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
                <div class="row">
                    <div class="login-box">
                        <div>
                            <label for="states">Have your travelled in past 14 days to any of the states? : </label>
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
                            <label for="patient">Did you get in touch with any Covid positive patient ? :</label>
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
                            <label for="temprature">Current Body Temprature:</label>
                            <input placeholder="Enter Current Body Temprature" type="text" name="temprature" size="30" value="{{old('temprature')}}" class="form-control nowarn" maxlength="5" required>
                            @if($errors->has('temprature'))<p class="text-danger">{{ $errors->first('temprature') }}</p> @endif
                        </div>
                    </div>
                </div>
              </div>
              <div class="container" id="visit_type_div" style="width:93%">
                <div class="heading_dtl">
                   <span>Visit Type</span>
                </div>
                <div class="row">
                    <div class="login-box">
                        <div>
                            <label for="visit_type">Visit Type: </label>
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

            <div style="text-align:right;">
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
                          <label for="">select Image</label>
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
                          <label for="">select Image</label>
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
                          <label for="">select Image</label>
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
                          <label for="">select Image</label>
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
                          <label for="">select Image</label>
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
                          <label for="">select Image</label>
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
                          <label for="">select Image</label>
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
            width: 350,
            height: 200,
            image_format: 'jpeg',
            jpeg_quality: 500
        });


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
        $('#department_id').on('change', function() {
                var depart_id = this.value;
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
                        $.each(result.states,function(key,value){
                            $("#officer_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                        $('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
            });

		$('#country_id').on('change', function() {
                var country_id = this.value;
                $("#city_id").html('');
				$("#state_id").html('');
                $.ajax({
                    url:"{{route('web.get.state')}}",
                    type: "POST",
                    data: {
                    country_id: country_id,
                    _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result){
                        $.each(result.states,function(key,value){
                            $("#state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
            });
		$('#state_id').on('change', function() {
                var state_id = this.value;
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
                        $.each(result.city,function(key,value){
                            $("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
            });
			$('#location_id').on('change', function() {
			  var location_id = this.value;
			  $("#building_id").html('');
			  $("#department_id").html('');
			  $("#officer_id").html('');
			  getBuilding(location_id);
			});
			function getBuilding(location_id){
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
					  $.each(result,function(key,value){
					  $("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
					  if(key==0){
						getDepartment(value.id);
						  }
					  });

					 }
				 });
			}
		function getDepartment(building_id){
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
						  $.each(result,function(key,value){
							$("#department_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
						  });
					  }
			});
		}
		$('#orga_country_id').on('change', function() {
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
                        $.each(result.states,function(key,value){
                            $("#orga_state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                        //$('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
            });
		$('#orga_state_id').on('change', function() {
                var state_id = this.value;
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
                        $.each(result.city,function(key,value){
                            $("#orga_city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });

                    }
                });
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
       function imageUploaded() {
         var file = document.querySelector(
         'input[id=image_1]')['files'][0];
         var reader = new FileReader();

         reader.onload = function () {
           base64String = reader.result.replace("data:", "")
               .replace(/^.+,/, "");

           imageBase64Stringsep = base64String;

           $('#image_1').val(base64String);
       }
       reader.readAsDataURL(file);
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
     $("#mobile").focusout(function(){
       var mobile = $("#mobile").val();

       $.ajax({
           url:"{{route('web.get.mobiledetails')}}",
           type: "POST",
           data: {
           mobile: mobile,
           _token: '{{csrf_token()}}'
           },
           dataType : 'json',
           success: function(result){

             if(result.status=="success"){
               $('#mobile_error').html(result.message);
             }else{
               $('#mobile_error').html(result.message);
             }
           }
       });
     });
     function vehivleValidate(){
       var format = /[!@#$%^&*()_+\=\[\]{};':"\\|,.<>\/?]+/;
       var vehical_reg_num=$('#vehical_reg_num').val();
        if(format.test(vehical_reg_num)){
            alert("Vehicle Number Not Allowed Please check number");
          }
     }
    </script>

@endpush
