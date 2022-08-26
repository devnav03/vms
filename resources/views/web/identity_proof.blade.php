@extends('web.layouts.inner-master')
@section('content')
@php
$document = \Session::get('document');
$document_type = \Session::get('document_type');
@endphp
<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="{!! asset('assets/images/home.png') !!}">
            </div>
            <div class="col-md-11 seceen_two_right3">
                <div class="row">
                    <div class="col-md-5">
                        <h1>Step 01 - 04</h1>
                        <p>Identity Proof</p>
                        
                        <div class="bottm_text3">Confirmation <br>of your<br>identity</div>
                    </div>
                    <div class="col-md-5">
                    <form action="{{ route('identity-confirmation') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }}    
                        @if($list->document_id == 1)
                        <div class="row">
                            @if($document)
                            <div class="col-md-12 text-center relative">
                                <input type="file" name="document" class="profile_image" id="imgInp">
                                <img src="{!! asset('uploads/img/'.$document) !!}" id="blah" style="width: 50%">
                            </div>
                            <div class="col-md-12 text-center mt-4 mb-5">
                                <button type="button" class="bg-success text-white suc_btn" style="margin-right: 0px;"><i class="bi bi-check-lg"></i> Success</button>
                            </div>
                            @else
                            <div class="col-md-12 text-center relative">
                                <input type="file" name="document" required="true" class="profile_image" id="imgInp">
                                <img src="{!! asset('assets/images/upload.png') !!}" id="blah" style="width: 50%">
                            </div>
                            <div class="col-md-12 text-center mt-4 mb-5">
                                <button type="button" class="active ped_btn"><img src="{!! asset('assets/images/pending.png') !!}" width="20%"> Pending</button>
                                <button type="button" class="bg-success text-white suc_btn" style="display: none;margin-right: 0px;"><i class="bi bi-check-lg"></i> Success</button>
                            </div>
                            @endif
                        </div>
                        @endif 
                        @if($list->document_type == 1)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <select name="document_type" required="true" class="doc_dropdown">
                                    <option value="">For Identification, select a type of document.</option>
                                    <option @if($document_type == 'dl') selected="" @endif value="dl">Driving Licence</option>
                                    <option @if($document_type == 'adhar_card') selected="" @endif value="adhar_card">Aadhar Card</option>
                                    <option @if($document_type == 'govt_id_pf') selected="" @endif value="govt_id_pf">Govt Identity Proof </option>
                                    <option @if($document_type == 'Pancard') selected="" @endif value="Pancard">Pancard</option>
                                </select>

                                <!-- <button type="button" class="bg-secondary text-white">For Identification, select a type of document. <i class="bi bi-caret-down-fill"></i></button> --></div>
                            <div class="col-md-12 text-center mt-3 otp_text">Select a document type to help you identity yourself.</div>
                        </div>
                        @endif
                        <br>
                        <div class="row pagination" style="float: right;">
                            <ul>
                                <li><a href="{{ route('business-information') }}"><i class="bi bi-arrow-left-circle"></i></a></li>
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

