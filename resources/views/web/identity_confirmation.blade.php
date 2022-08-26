@extends('web.layouts.inner-master')
@section('content')
@php
$document = \Session::get('document');
$document_type = \Session::get('document_type');
$document_number = \Session::get('document_number');
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
                        <form method="POST" action="{{ route('whom-to-meet') }}">
                        {{ csrf_field() }}  
                        <div class="row">
                            @if($document)
                            <div class="col-md-12 text-center">
                                <img src="{!! asset('uploads/img/'.$document) !!}" style="width: 50%">
                            </div>
                            <div class="col-md-12 text-center mt-4 mb-2">
                                <!-- <button type="button" class="bg-success text-white"><i class="bi bi-check-lg"></i> Success</button> -->
                            </div>
                            @else
                            <div class="col-md-12 text-center">
                                <img src="{!! asset('assets/images/document.png') !!}" style="width: 50%">
                            </div>
                            @endif

                        </div>
                        
                       <div class="row">
                        <div class="col-md-12 text-center">
                                <button type="button" class="active">
                                    @if($document_type == 'dl') Driving Licence @endif
                                    @if($document_type == 'adhar_card') Aadhar Card @endif
                                    @if($document_type == 'govt_id_pf') Govt Identity Proof @endif
                                    @if($document_type == 'Pancard') Pancard @endif
                                 </button>
                            </div>
                            <div class="col-md-12 text-center mt-2 otp_text">Add the document's serial number here.</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3"></div>
                            <div class="col-md-1"><i class="bi bi-person-badge icn"></i></div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <input placeholder="Document Number" type="text" value="{{ $document_number }}" name="document_number" required="true" class="form-contro">
                            </div>
                            </div> 
                            <div class="col-md-2"></div>
                        </div>
                        <br>
                        <br>
                        <div class="row pagination" style="float: right;">
                            <ul>
                                <li><a href="{{ route('identity-proof') }}"><i class="bi bi-arrow-left-circle"></i></a></li>
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

