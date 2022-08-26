<!DOCTYPE html>
<html class=" ">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <app-title></app-title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- CORE CSS FRAMEWORK - START -->
    <link href="{{asset('admin/plugins/pace/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('admin/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/plugins/bootstrap/css/bootstrap-theme.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/css/animate.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS FRAMEWORK - END -->

    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
    <!-- CORE CSS TEMPLATE - START -->
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('admin/css/responsive.css')}}" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS TEMPLATE - END -->


    <!-- CORE CSS TEMPLATE - END -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->

<!-- BEGIN BODY -->
    <body class=" ">

        <app-top></app-top>
        <!-- START CONTAINER -->
        <div class="page-container row-fluid">

            <app-menu></app-menu>
            <!-- START CONTENT -->
            <section id="main-content" class="">
            	<app-root></app-root>                
            </section>
            <!-- END CONTENT -->
              
        </div>
        <!-- END CONTAINER -->

    
        <!-- CORE JS FRAMEWORK - START --> 
        <script src="{{asset('admin/js/jquery-1.11.2.min.js')}}" type="text/javascript"></script> 
        <script type="text/javascript" src="{{asset('admin/js/app.js')}}"></script>
        <script src="{{asset('admin/js/jquery.easing.min.js')}}" type="text/javascript"></script> 
        <script src="{{asset('admin/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script> 
        <script src="{{asset('admin/plugins/pace/pace.min.js')}}" type="text/javascript"></script>  
        <script src="{{asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}" type="text/javascript"></script> 
        <script src="{{asset('admin/plugins/viewport/viewportchecker.js')}}" type="text/javascript"></script>  
        <script src="{{asset('admin/js/scripts.js')}}" type="text/javascript"></script> 
        <!-- END CORE TEMPLATE JS - END --> 
        <script src="{{asset('admin')}}" type="text/javascript"></script>
        <!-- Sidebar Graph - END -->     
</body>

</html>