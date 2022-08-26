@extends('web.layouts.inner-master')
@section('content')
    <section class="guest">
        <div class="container">
            <div class="row" style="align-items: center;">
                <div class="col-md-6 guest_image">
                    <img class="img_one" src="{!! asset('assets/images/Vector.png') !!}">
                    <img class="img_two" src="{!! asset('assets/images/Vector2.png') !!}">
                </div>
                <div class="col-md-6 guest_content">
                    <h1>Hello Guest!</h1>
                    <p style="font-size: 20px">Good Day!<br>Welcome to the Vztor<br>Management System</p>
                    <ul>
                        <li><a href="{{route('web.self-registration')}}"><button type="button" class="active1"><img src="{!! asset('assets/images/visiter.png') !!}" style="width: 20%;"> New Visitor</button></a></li>
                        <!-- <li><a href="{{route('web.status')}}"><button type="button"><img src="{!! asset('assets/images/status.png') !!}" style="width: 20%;"> Know Status</button></a></li><br>
                        <li><a href="{{route('web.re-visit')}}"><button type="button" class="disabl"><img src="{!! asset('assets/images/revisit.png') !!}" style="width: 20%;"> Revisit ? &nbsp; &nbsp; </button></a></li>
                        <li><a href="{{route('web.gaurd.login')}}"><button type="button"><img src="{!! asset('assets/images/gaurd.png') !!}" style="width: 20%;"> Gaurd Login</button></a></li> -->
                    </ul>
                    <br>                    
                </div>
            </div>
        </div>
    </section>
@endsection
