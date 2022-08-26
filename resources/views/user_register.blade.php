<html>
<head>
    <title>Account Successfully Created</title>
</head>
<body style="padding:10px;border:1px solid #ddcfcf">
    <h2>Dear {{ $name }},</h2>
    <br>
	We have just created your employee account with username: {{ $email }} and  password : {{ $password }}<br>
    Please <a target="blank" href="@if($designation == 'Guard') https://vztor.in/ITDA/public/guard/login @else https://vztor.in/ITDA/public/admin-panel @endif">click here</a> to login into the portal
<br>
Thanks VMS
</body>
</html>