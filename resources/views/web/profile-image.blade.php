@extends('web.layouts.inner-master')
@section('content')
@php
$image_name = \Session::get('image_name');
$name = \Session::get('name');
@endphp

<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <img src="{!! asset('assets/images/home.png') !!}">
            </div>
            <div class="col-md-11 seceen_two_right2">
                <div class="row">
                    <div class="col-md-5">
                        <h1>Step 01 - 02</h1>
                        <p>Adding a profile photo</p>
                        <div class="bottm_text2"><span style="color: #FA931A">{{ $name }}!</span><br>Keep that <br>smile on<br> your face, please</div>
                    </div>
                    <div class="col-md-5">
                    <form action="{{ route('information-registration') }}" enctype="multipart/form-data" method="POST">
                            {{ csrf_field() }}
                        <div class="row">
                            @if($image_name)
                            <div class="col-md-12 text-center relative">
                                <input type="file" name="image" class="profile_image" id="imgInp">
                                <img src="{!! asset('uploads/img/'.$image_name) !!}" id="blah" style="border-radius: 50%; height: 250px; width: 250px;">
                            </div>
                            <div class="col-md-12 text-center mt-4 mb-5">
                                <button type="button" class="active ped_btn" style="display: none;"><img src="{!! asset('assets/images/pending.png') !!}" style="width: 20%;margin-right: 0px;"> Pending</button>
                                <button type="button" class="bg-success text-white suc_btn" style="margin-right: 0px;"><i class="bi bi-check-lg"></i> Success</button>
                            </div>
                            @else
                            <div class="col-md-12 text-center relative">
                                <input type="file" name="image" required="true" class="profile_image" id="imgInp">
                                <img src="{!! asset('assets/images/user.png') !!}" id="blah" style="border-radius: 50%; height: 250px; width: 250px;">
                            </div>
                            <div class="col-md-12 text-center mt-4 mb-5">
                                <button type="button" class="active ped_btn" style="margin-right: 0px;"><img src="{!! asset('assets/images/pending.png') !!}" style="width: 20%;"> Pending</button>
                                <button type="button" class="bg-success text-white suc_btn" style="display: none;margin-right: 0px;"><i class="bi bi-check-lg"></i> Success</button>
                            </div>
                            @endif
                        </div>
                        <br>
                        <br>
                         <div class="row pagination">
                            <ul>
                                <li><a href="{{route('web.self-registration')}}"><i class="bi bi-arrow-left-circle"></i></a></li>
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

