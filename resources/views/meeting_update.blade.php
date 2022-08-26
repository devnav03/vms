<html>
<head>
    <title>{{ $meeting_title }}</title>
</head>
  <body style="padding:10px;border:1px solid #ddcfcf">
	  <p>This event has been changed.<br>
		  <b>{{ $meeting_title }}</b><br>
		  When<br>
		  Changed: {{ date('d M, h:i A', strtotime($from_date)) }} – {{ date('h:i A', strtotime($to_date)) }}.<br>
	
	  Thanks<br>
	  ITDA</p>
        
     
    </body>
</html>