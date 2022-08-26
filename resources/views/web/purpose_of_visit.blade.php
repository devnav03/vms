@extends('web.layouts.inner-master')
@section('content')
@php
$services = \Session::get('services');
@endphp
    <section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="{!! asset('assets/images/home.png') !!}">
            </div>
            <div class="col-md-11 seceen_two_right3">
                        <!-- <div class="skip">Skip</div> -->
                <div class="row">
                    <div class="col-md-2">
                        <h1>Step 03</h1>
                        <p>Visit's purpose</p>
                        
                        <div class="bottm_text3">Purpose of visit?</div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-4 icn_box">
                            <form method="POST" action="{{ route('meeting-time') }}">
                                {{ csrf_field() }}    
                                <label><input @if($services) @if($services == 'Personal') checked="" @endif  @else checked="" @endif id="Personal" type="radio" value="Personal" name="services"><label for="Personal">
                                <img class="inact_icon" src="{!! asset('assets/images/personal.png') !!}">
                                <img class="act_icon" src="{!! asset('assets/images/icons-y.png') !!}">
                                <p>Personal</p> </label>
                                </label>
                            </div>
                            <div class="col-md-4 icn_box">
                                <label><input id="Official" @if($services == 'Official') checked="" @endif type="radio" value="Official" name="services"><label for="Official">
                                <img class="inact_icon" src="{!! asset('assets/images/official.png') !!}">
                                <img class="act_icon" src="{!! asset('assets/images/icons-2-y.png') !!}">
                                <p>Official</p></label></label>
                            </div>
                            <div class="col-md-4 icn_box">
                                <label><input id="Service" @if($services == 'Service') checked="" @endif type="radio" value="Service" name="services"><label for="Service">
                                <img class="inact_icon" src="{!! asset('assets/images/service.png') !!}">
                                <img class="act_icon" src="{!! asset('assets/images/icons-5-y.png') !!}">
                                <p>Service</p></label></label>
                            </div>
                            <div class="col-md-4 icn_box">
                                <label><input id="Interview" @if($services == 'Interview') checked="" @endif type="radio" value="Interview" name="services"><label for="Interview">
                                <img class="inact_icon" src="{!! asset('assets/images/interview.png') !!}">
                                <img class="act_icon" src="{!! asset('assets/images/icons-1-y.png') !!}">
                                <p>Interview</p> </label></label>
                            </div>
                            <div class="col-md-4 icn_box">
                                <label><input id="Meeting" @if($services == 'Meeting') checked="" @endif type="radio" value="Meeting" name="services"><label for="Meeting">
                                <img class="inact_icon" src="{!! asset('assets/images/meeting.png') !!}">
                                <img class="act_icon" src="{!! asset('assets/images/icons-3-y.png') !!}">
                                <p>Meeting</p></label></label>
                            </div>
                            <div class="col-md-4 icn_box">
                                <label><input id="Deliver" type="radio" @if($services == 'Deliver') checked="" @endif value="Deliver" name="services"><label for="Deliver">
                                <img class="inact_icon" src="{!! asset('assets/images/deliver.png') !!}">
                                <img class="act_icon" src="{!! asset('assets/images/icons-6-y.png') !!}">
                                <p>Deliver</p></label></label>
                            </div>
                            <!-- <div class="col-md-12 icn_box">
                                <img src="{!! asset('assets/images/others.png') !!}" style="width: 8%;">
                                <p>Others</p>
                            </div> -->
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="row pagination">
                            <div class="col-md-5"></div>
                            <ul class="col-md-7">
                                <li><a href="{{ route('whom-to-meet') }}"><i class="bi bi-arrow-left-circle"></i></a></li>
                                <li><button type="submit" class="sub_btn"><i class="bi bi-arrow-right-circle"></i></button></li>
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

