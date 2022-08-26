@extends('web.layouts.inner-master')
@section('content')
@php
$location_id = \Session::get('location_id');
$building_id = \Session::get('building_id');
$department_id = \Session::get('department_id');
$officer_id = \Session::get('officer_id');
@endphp
    <section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="{!! asset('assets/images/home.png') !!}">
            </div>
            <div class="col-md-11 seceen_two_right3">
                      <!--   <div class="skip">Skip</div> -->
                <div class="row">
                    <div class="col-md-2">
                        <h1>Step 02</h1>
                        <p>Person's Information</p>
                        
                        <div class="bottm_text3">Whom to meet?</div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-7">
                        <form method="POST" action="{{ route('purpose-of-visit') }}">
                            {{ csrf_field() }}
                            <div class="form-container">
                                <div class="row">
                                    @if($list->meet_region == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-flag icn"></i>
                                            </div>
                                            <div class="col-md-11 clm_padd">
                                                    <div class="label"><b>Location</b></div>
                                                    <select name="location_id" id="location_id" required="true">
                                                        <option value="">Select One</option>
                                                        @foreach($locations as $location)
                                                        <option @if($location_id == $location->id) selected @endif value="{{ $location->id }}">{{ $location->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->meet_address == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-building icn"></i>
                                            </div>
                                            <div class="col-md-11 clm_padd">
                                                    <div class="label"><b>Building number</b></div>
                                                    <select required="true" name="building_id" id="building_id">
                                                        <option value="">Select One</option>
                                                        @foreach($buildings as $building)
                                                            <option @if($building_id == $building->id) selected @endif value="{{ $building->id }}">{{ $building->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> 
                                    </div>
                                    @endif
                                    @if($list->meet_department == 1)
                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-briefcase icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                <div class="label"><b>Department</b></div>
                                                    <select required="true" name="department_id" id="department_id">
                                                        <option value="">Select One</option>
                                                        @foreach($departs as $depart)
                                                            <option @if($department_id == $depart->id) selected @endif value="{{ $depart->id }}">{{ $depart->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
        
                                    @if($list->meet_person == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-person icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                    <div class="label"><b>Person / Officer name</b></div>
                                                    <select required="true" name="officer_id" id="officer_id">
                                                        <option value="">Select One</option>
                                                        @foreach($officers as $officer)
                                                        <option @if($officer_id == $officer->id) selected @endif value="{{ $officer->id }}">{{ $officer->name }}</option>
                                                        @endforeach
                                                        
                                                    </select>
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
                                <li><a href="{{ route('identity-confirmation') }}"><i class="bi bi-arrow-left-circle"></i></a></li>
                                <li><button type="submit" class="sub_btn"><i class="bi bi-arrow-right-circle" style="color: #FA931A"></i></button></li>
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

