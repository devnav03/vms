@extends('web.layouts.inner-master')
@section('content')

<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <a href="/"><img src="{!! asset('assets/images/home.png') !!}"></a>
            </div>
            <div class="col-md-11 seceen_two_right2">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h1>OTP</h1>
                        <div class="gap"></div>
                        <div class="bottm_text">Know Visit Status</div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-7">
                        
                        <form action="{{ route('web.check.status') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-container">
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-tablet icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input id="phone" type="text" class="validate">
                                        <label for="phone" class="">Enter your mobile no</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <i class="bi bi-pin-map icn"></i>
                                    </div>
                                    <div class="input-field col-md-11">
                                        <input id="otp" type="text" class="validate">
                                        <label for="otp" class="">Enter OTP received</label>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="row">
                            <div class="col-md-12 otp_text mb-4">Enter the OTP that was received on your mobile device.</div>
                            <div class="col-md-12 text-end"><button type="button" class="bg-danger text-white">Resend OTP</button></div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="row pagination">
                            <div class="col-md-5"></div>
                            <ul class="col-md-7">
                                <li><a href="/"><i class="bi bi-arrow-left-circle"></i></a></li>
                                <li><a href="#" style="color: #FA931A"><i class="bi bi-arrow-right-circle"></i></a></li>
                            </ul>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


        <!-- <div id="content" align="center">
            {{Form::open(['route'=>['web.check.status'],  'method' => 'post', 'enctype'=>'multipart/form-data'])}}
            <div class="row" id="reg">
              <div class="heading_dtl">
                 <span>Know Visit Status</span>
              </div>

                <p>Please provide your mobile number to access your visit status details.</p>
                <hr/>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <div>
                        <label for="mobile">Mobile Number:<span class="text-danger">*</span></label>
                        <input placeholder="Enter your register mobile number" type="text" name="mobile" size="30" value="{{old('mobile')}}" class="nowarn form-control" maxlength="10">
                    </div>
                </div>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <p style="text-align: -webkit-center;">
                        <input  type="submit" value="Submit" class="btn btn-primary" style="width: 70px; margin-top: 24px;">
                    </p>
                </div>
            </div> -->

     <!--      <div id="otp_check">
            <div class="row">
              <div class="heading_dtl">
                 <span>Otp Verify</span>
              </div>
                <p>Please verify your otp to check your status.</p>
                <p class="text-success text-center">{{\Session::get('message')}}</p>
                <hr/>
                <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <div>
                        <label for="otp">Enter otp:<span class="text-danger">*</span></label>
                        <input type="text" class="nowarn form-control" value="{{old('otp')}}" name="otp" placeholder="Enter your otp" maxlength="6">
                        <small class="text-danger">{{$errors->first('otp')}}</small>
                    </div>
                </div>
              <div class="login-box col-md-6 col-sm-12 col-xs-12">
                    <p style="text-align: -webkit-center;">
                        <input  type="submit" value="Submit" class="btn btn-primary" style="width: 70px; margin-top: 24px;">
                    </p>
                  </div>
                </div>
            </div> -->
    <!--       {{Form::close()}}
      </div>
    </div> -->
@endsection

@push('scripts')
    <script>
        $('#otp_check').hide();
        @if(session()->has('otp_true'))
            $('#reg').hide();
            $('#otp_check').show();
        @endif
    </script>
@endpush
