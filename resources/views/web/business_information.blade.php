@extends('web.layouts.inner-master')
@section('content')
@php
$business_name = \Session::get('business_name');
$firm_address = \Session::get('firm_address');
$firm_email = \Session::get('firm_email');
$firm_contact = \Session::get('firm_contact');
$firm_pincode = \Session::get('firm_pincode');
$firm_id = \Session::get('firm_id');
$signature = \Session::get('signature');
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
                        <form action="{{ route('identity-proof') }}" enctype="multipart/form-data" method="POST">
                            {{ csrf_field() }}
                            <div class="form-container">
                                <div class="row">
                                    @if($list->business_name == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-briefcase icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                    <input type="text" value="{{ $business_name }}" name="business_name" id="bus">
                                                    <label for="bus" class="addr">Name of your business</label>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->firm_address == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-map icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                <textarea id="add" name="firm_address">{{ $firm_address }}</textarea>
                                                <label for="add" class="addr">Address of your firm</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->firm_email == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-envelope icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                <input type="email" name="firm_email" value="{{ $firm_email }}" id="email" class="form-contro">
                                                <label for="email" class="addr">Firm email</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->firm_contact == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-tablet icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_padd">
                                                <input id="tel" type="text" value="{{ $firm_contact }}" name="firm_contact" class="form-contro">
                                                <label for="tel">Firm contact</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->firm_pincode == 1)
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-pin-map icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_padd">
                                                <input id="pin" name="firm_pincode" value="{{ $firm_pincode }}" type="number" class="form-contro">
                                                <label for="pin">Firm pincode</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->firm_id == 1) 
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-person-badge icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_padd">
                                                <input id="fm" value="{{ $firm_id }}" name="firm_id" type="text" class="form-contro">
                                                <label for="fm">Firm id</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->signature_id == 1) 
                                    <div class="col-md-6 col1">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-pen icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_padd">
                                                <div class="label"><b>Signature</b></div>
                                                <input type="file" id="imgInp" name="signature" class="form-contro">
                                                @if($signature)
                                                <img src="{!! asset('uploads/img/'.$signature) !!}" id="blah" style="max-width: 100px; margin-top: 10px;">
                                                @else
                                                <img src="" id="blah" style="max-width: 100px; margin-top: 10px;">
                                                @endif
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
                                <li><a href="{{ route('information-registration') }}"><i class="bi bi-arrow-left-circle"></i></a></li>
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

