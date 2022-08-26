<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Visitor</title>
</head>

<body>  
   <h3>Dear Sir/Madam, </h3>
	<p>VMS has scheduled a new meeting with visitor {{$vis_name}} (Mob: {{@$user_mobile}}) on {{$m_time}}.<br> Kindly login to check or <a href="{{route('visitor.approve')}}/{{$encryptString}}/{{@$officer_id}}" style="background:#337ab7;padding: 10px; color: #ffffff;text-decoration: none; border-radius: 10px" target="_blank">Click here</a> to approve.</p>	
    <img src="{!! asset('uploads/img/'.$image) !!}" width="300"><br>
    <strong>THANKS</strong><br>
    <strong>VMS Team</strong> <br>
</body>
</html>
