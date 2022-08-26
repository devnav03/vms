@extends('web.layouts.inner-master')
@section('content')
@php
$email = \Session::get('email');
$name = \Session::get('name');
$mobile = \Session::get('mobile');
$gender = \Session::get('gender');

@endphp
<style>
.hide{
    display:none;
}    
    
</style>
<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="{!! asset('assets/images/home.png') !!}">
            </div>
            <div class="col-md-11 seceen_two_right">
                <div class="row">
                    <div class="col-md-3">
                        <h1>Step 01</h1>
                        <p>Registration</p>
                        <div class="bottm_text">Tell us about Yourself</div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        
                    <form method="POST" enctype="multipart/form-data" action="{{ route('add.self.registration') }}">
                            <div class="form-container">
                                @if($list->name == 1)
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-person icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input required="true" id="last_name" value="{{ $name }}" name="name" type="text" class="validate">
                                        <label for="last_name">Enter your full name</label>
                                        @if($errors->has('name'))
                                            <span class="text-danger">{{$errors->first('name')}}</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($list->email == 1)
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-envelope icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input required="true" value="{{ $email }}" name="email" id="email" type="email" class="validate">
                                        <label for="email">Enter your email</label>
                                        @if($errors->has('email'))
                                            <span class="text-danger">{{$errors->first('email')}}</span>
                                        @endif
                                        @if(session()->has('email_exist'))
                                        <div style="color: #f00; font-size: 12px; padding-top: 2px;">Email Already exist</div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($list->gender == 1)
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-gender-female icn"></i>
                                    </div>
                                    <div class="col-md-11">
                                        <label for="gender"><b>Gender</b></label>
                                        <select required="true" name="gender">
                                        <option value="">Select</option>
                                        <option @if($gender == 'Male') selected="" @endif value="Male">Male</option>
                                        <option @if($gender == 'Female') selected="" @endif value="Female">Female</option>
                                        <option @if($gender == 'Other') selected="" @endif value="Other">Other</option>
                                        </select>
                                        @if($errors->has('gender'))
                                            <span class="text-danger">{{$errors->first('gender')}}</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($list->mobile == 1)
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-tablet icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input required="true" id="phone" value="{{ $mobile }}" name="mobile" type="text">
                                        <label for="phone">Enter your mobile no</label>
                                        <div class="already_exist" style="color: #f00; font-size: 12px; padding-top: 2px;"></div>
                                        <div class="otp_sent" style="color: green; font-size: 12px; padding-top: 2px;"></div> 
                                        <div class="valid_no" style="color: #f00; font-size: 12px; padding-top: 2px;"></div> 
                                        <div id="recaptcha-container"></div>
                                        <!--<button type="button" class="btn btn-info" onclick="otpSend();">Send OTP</button> -->
                                        @if($errors->has('mobile'))
                                            <span class="text-danger">{{$errors->first('mobile')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-pin-map icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input name="otp" id="otp" required="true" type="number">
                                        <label for="otp">Enter OTP received</label>
                                        <div class="not_verify" style="color: #f00; font-size: 12px; padding-top: 2px;"></div>
                                        <div class="otp_verify" style="color: green; font-size: 12px; padding-top: 2px;"></div>
                                        <div class="alert alert-danger hide" id="error-message"></div>
                                        <div class="alert alert-success hide" id="sent-message"></div>
                                        
                                        @if(session()->has('otp_not_match'))
                                        <div class="errors_otp" style="color: #f00; font-size: 12px; padding-top: 2px;">Invalid OTP</div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        
                        <br>
                        <br>
                        @if($list->mobile == 1)
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-6 otp_text">Enter the OTP that was <br> received on your mobile device.</div> 
                            <div class="col-md-5"><button onclick="ResendOTP(this.value); return false;" class="bg-danger text-white">Resend OTP</button>
                            <div class="otp_resent" style="color: green;"></div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        @endif
                        <div class="row pagination">
                            <div class="col-md-5"></div>
                            <ul class="col-md-7">
                                <li><a href="/"><i class="bi bi-arrow-left-circle"></i></a></li>
                                <li><button type="submit" class="sub_btn"><i class="bi bi-arrow-right-circle" style="color: #FA931A"></i></button></li>

                               <!--  <li><a href="{{ route('profile-image') }}" ><i class="bi bi-arrow-right-circle" style="color: #FA931A"></i></a></li> -->
                            </ul>
                        </div>
                        {{ csrf_field() }}
                        </form>  
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>
    </section>

@endsection

