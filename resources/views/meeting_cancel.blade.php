<html>
<head>
    <title>{{ $meeting_title }}</title>
</head>
  <body style="padding:10px;border:1px solid #ddcfcf">
	  <p>Your meeting {{ $meeting_title }} at ~{{ date('h:i A', strtotime($from_date)) }} has been cancelled.<br>
	
	  Thanks<br>
	  ITDA</p>
        
     
    </body>
</html>