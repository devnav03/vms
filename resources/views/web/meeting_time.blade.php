@extends('web.layouts.inner-master')
@section('content')
@php
$visite_duration = \Session::get('visite_duration');
$visite_time = \Session::get('visite_time');
$topic = \Session::get('topic');
@endphp
    <section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="{!! asset('assets/images/home.png') !!}">
            </div>
            <div class="col-md-11 seceen_two_right3">
                        <!-- <div class="skip">Skip</div> -->
                <div class="row">
                    <div class="col-md-3">
                        <h1>Step 02</h1>
                        <p>Set a meeting time</p>
                        
                        <div class="bottm_text3">Time for visitors</div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('add.self.registration.success') }}"> 
                            {{ csrf_field() }}
                            <div class="form-container">
                                <div class="row">
                                    @if($list->duration == 1)
                                    <!-- <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-clock icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                <div class="label"><b>Duration</b></div>
                                                <div class="tagline">Please choose the length of your meeting.</div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-person icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                <div class="label">Duration</b></div>
                                                <select name="visite_duration" id="visite_duration" required="">
                                                <option value="">Visit Duration</option>
                                                <option @if($visite_duration == '15') selected="" @endif value="15">15 Min </option>
                                                <option @if($visite_duration == '30') selected="" @endif value="30">30 Min </option>
                                                <option @if($visite_duration == '45') selected="" @endif value="45">45 Min</option>
                                                <option @if($visite_duration == '60') selected="" @endif value="60">1 Hour</option>
                                                <option @if($visite_duration == '90') selected="" @endif value="90">1.5 Hour</option>
                                                <option @if($visite_duration == '120') selected="" @endif value="120">2 Hour</option>
                                                <option @if($visite_duration == '240') selected="" @endif value="240">4 Hour</option>
                                                <option @if($visite_duration == '1440') selected="" @endif value="1440">Full Day</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                    @if($list->schduler == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-calendar icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                            <div class="label"><b>Date time schduler</b></div>
                                            <input type="datetime-local" min="<?= date('Y-m-d H:i'); ?>" value="{{ $visite_time }}" name="visite_time" required="" class="validate">
                                            <div class="tagline">Please set your appointment date and time.</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($list->topic == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <i class="bi bi-hash icn"></i>
                                            </div>
                                            <div class="input-field col-md-11 clm_dc">
                                                    <input id="pin" value="{{ $topic }}" required="true" name="topic" type="text" class="form-contro">
                                                    <label for="pin" class="addr">Topic</label>
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
                                <li><a href="{{ route('purpose-of-visit') }}"><i class="bi bi-arrow-left-circle"></i></a></li>
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

