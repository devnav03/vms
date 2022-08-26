<!DOCTYPE html>



<html lang="en">







<head>



    <meta charset="UTF-8">



    <title>New Visitor</title>



</head>







<body>



   <h3>Dear Visitor, </h3>

    <p>{{$visitor_name}} ({{$mobile}}) has invited you to visit the {{$building}} ({{$location}}) <strong> on {{$app_date}} at {{$appoint_time}}.</strong></p> 
    <img src="{!! asset('uploads/img/'.$image) !!}" width="300"><br>
    <strong>THANKS</strong><br>
    <strong>VMS Team</strong> <br> 



</body>



</html>