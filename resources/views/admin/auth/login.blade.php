<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body {
            color: #c0c7de;
            direction: ltr;
            background: #252d47;
            background: -moz-radial-gradient(center, ellipse cover, #232b44 0%, #191e30 100%);
            background: -webkit-radial-gradient(center, ellipse cover, #232b44 0%, #191e30 100%);
            background: radial-gradient(ellipse at center, #232b44 0%, #191e30 100%);
            font-weight: 400;
            background-attachment: fixed;
        }

        .background {
            top: 0;
            position: absolute;
            z-index: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            margin: 0;
            background: #000;
        }

        .background img {
            opacity: 0.4;
            min-width: 100%;
            height: auto;
            min-height: 100%;
        }

        .wrapper-content-sign-in {
            width: 100%;
            margin: 0 auto;
            padding: 40px 10px 40px 10px;
            min-height: 100%;
            height: auto;
            z-index: 1;
            position: relative;
        }

        .sign-in-wrapper .form-signin input[type="text"],
        .sign-in-wrapper .form-signin input[type="password"] {
            height: 45px;
            border-radius: 25px;
            border: 1px solid rgba(0, 0, 0, 0.3);
            padding-left: 60px;
        }

        .sign-in-wrapper .form-signin input[type="text"]:focus::placeholder,
        .sign-in-wrapper .form-signin input[type="password"]:focus::placeholder {
            opacity: 0;
        }

        .sign-in-wrapper .form-signin .form-group {
            position: relative;
        }

        .sign-in-wrapper .form-signin .form-group .fa {
            position: absolute;
            top: 50%;
            left: 15px;
            width: 30px;
            transform: translateY(-50%);
            font-size: 20px;
            color: rgba(0, 0, 0, 0.7);
            padding-right: 10px;
            border-right: 2px solid rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            border: 0;
            min-width: 200px;
            margin-top: 20px;
            -webkit-border-radius: 50px !important;
            -moz-border-radius: 50px !important;
            -ms-border-radius: 50px !important;
            border-radius: 50px !important;
            -webkit-box-shadow: inset 0 1px 1px 0px rgba(255, 255, 255, 0.5);
            -moz-box-shadow: inset 0 1px 1px 0px rgba(255, 255, 255, 0.5);
            -ms-box-shadow: inset 0 1px 1px 0px rgba(255, 255, 255, 0.5);
            box-shadow: inset 0 1px 1px 0px rgba(255, 255, 255, 0.5);
        }

        .form-signin {
            padding: 100px 20px;
            background: #fff;
        }

        .sign-in-wrapper {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        @media (min-width: 992px) {
            .sign-in-wrapper {
                margin-top: 150px;
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            body {
                background: #252e4e;
            }

            .background {
                display: none;
            }

            .wrapper-content-sign-in {
                display: none;
            }

            .form-signin {
                border-radius: 15px;
                overflow: hidden;
                padding-top: 40px;
                padding-bottom: 50px;
                background-image: linear-gradient(#161c33, #33435D);
            }

            .form-signin h2 {
                margin: 0;
                font-size: 25px;
                color: #fff !important;
            }

            .form-signin small {
                color: rgba(255, 255, 255, 0.7);
                text-align: center;
                margin-bottom: 40px;
                display: block;
                font-size: 14px;
                font-weight: 500;
            }

            .sign-in-wrapper .form-signin input[type="text"],
            .sign-in-wrapper .form-signin input[type="password"] {
                height: 45px;
                border-radius: 0px;
                border: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.6);
                padding-left: 0px;
                margin-left: 44px;
                background: transparent;
                width: calc(100% - 60px);
                color: #fff;
            }

            .sign-in-wrapper .form-signin input[type="text"]::placeholder,
            .sign-in-wrapper .form-signin input[type="password"]::placeholder {
                color: #fff;
            }

            .sign-in-wrapper .form-signin .form-group .fa {
                border: 0;
                color: #fff;
            }
        }

    </style>
</head>

<body>
    <div class="wrapper-content">
        <div class="sign-in-wrapper pb-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="row justify-content-center no-gutters">
                            <div class="col-md-6">
                                <div class="wrapper-content-sign-in"> <br>
                                    {{-- <img src="#" width="100px" class="d-block mx-auto mb-3" alt=""> --}}
                                    <p class="text-center text-white" style="font-size: 50px;font-weight:700">Visitor Management System </p>
                                </div>
                                <figure class="background"> <img src="{{asset('admin-asset/images/avatar.jpg')}}" alt="sign in"> </figure>
                            </div>
                            <div class="col-md-6">
                                {!! Form::open(['class'=>'form-signin','route'=>'admin.login.post']) !!}
                                <h2 class="text-dark mb-4 pb-3 text-center d-none d-md-block">Sign in</h2>
                                <div class="text-center d-md-none">
                                    <img src="#" width="100px" class="d-block mx-auto mb-3" alt="">
                                    <h2 class="text-dark text-center">ADMIN PANEL</h2>
                                    <small>Control panel login</small>
                                </div>
                                <div class="form-group">
                                    <i class="fa fa-user"></i>
                                    {!! Form::text('email', '', ['class'=>'form-control', 'placeholder'=>'Email', 'autofocus']) !!}
                                </div>
                                <div class="form-group">
                                    <i class="fa fa-key"></i>
                                    {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password']) !!}
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg btn-primary btn-round">Sign in</button>
                                    <br>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- font awesome -->
    <script src="https://use.fontawesome.com/05ea06f073.js"></script>
</body>

</html>
