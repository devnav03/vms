@extends('web.layouts.inner-master')
@section('content')
@php
$date_of_birth = \Session::get('date_of_birth');
$nationality = \Session::get('nationality');
$state_name = \Session::get('state');
$city_name = \Session::get('city');
$address = \Session::get('address');
$pincode = \Session::get('pincode');

@endphp
<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="{!! asset('assets/images/home.png') !!}">
            </div>
            <div class="col-md-11 seceen_two_right3">
                <div class="row">
                    <div class="col-md-2">
                        <h1>Step 01 - 02</h1>
                        <p>Information Registration</p>
                        
                        <div class="bottm_text3">Enter your details</div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-7">
                    <form action="{{ route('business-information') }}" enctype="multipart/form-data" method="POST">
                            {{ csrf_field() }}
                            <div class="form-container">
                                <div class="row">
                                    @if($list->dob == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-calendar-event icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_padd">
                                                <div class="label"><b>Your d.o.b</b></div>
                                                <input value="{{ $date_of_birth }}" required="true" name="date_of_birth" max="<?= date('Y-m-d'); ?>" type="date" class="validate">
                                            </div> 
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->nationality == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-flag icn"></i>
                                            </div>
                                            <div class="col-md-11 clm_padd">
                                                    <div class="label"><b>Your nationality</b></div>
                                                    <select required="true" name="nationality" onChange="getState(this.value);">
                                                        <option value="">Select One</option>
                                                        @foreach($countries as $country)
                                                        <option @if($nationality == $country->id) selected=""  @endif value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->state == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-geo-alt icn"></i>
                                            </div>
                                            <div class="col-md-11 clm_padd">
                                                    <div class="label"><b>Your state</b></div>
                                                    <select name="state" required="true" onChange="getCity(this.value);" id="state-list">
                                                        <option value="">Select One</option>
                                                        @foreach($states as $state)
                                                        <option @if($state_name == $state->id) selected=""  @endif value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> 
                                    </div>
                                    @endif
                                    @if($list->city == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-building icn"></i>
                                            </div>
                                            <div class="col-md-11 clm_padd">
                                                    <div class="label"><b>Your city</b></div>
                                                    <select name="city" required="true" id="city-list">
                                                        <option value="">Select One</option>
                                                        @foreach($cities as $city)
                                                        <option @if($city_name == $city->id) selected=""  @endif value="{{ $city->id }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->address == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-map icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                <textarea id="add" required="true" name="address">{{ $address }}</textarea>
                                                <label for="add" class="addr">Your address</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->pincode == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-pin-map icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_padd">
                                                    <input id="pin" value="{{ $pincode }}" name="pincode" required="true" type="number" class="form-contro">
                                                    <label for="pin">Your pincode</label>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="row pagination">
                            <div class="col-md-5"></div>
                            <ul class="col-md-7">
                                <li><a href="{{ route('profile-image') }}"><i class="bi bi-arrow-left-circle"></i></a></li>
                                <li><button class="sub_btn" type="submit"><i style="color: #FA931A" class="bi bi-arrow-right-circle"></i></button></li>
                            </ul>
                        </div>
                        </form>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>
    </section>

@endsection

