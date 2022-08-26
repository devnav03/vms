<html>
<head>
    <title>{{ $meeting_title }}</title>
</head>
  <body style="padding:10px;border:1px solid #ddcfcf">
	  <p><b>Join your meeting at ~{{ date('h:i A', strtotime($from_date)) }} • {{ $meeting_title }}</b></p><br>
	 <p>Hi Test,<br>
	  You have a {{ $meeting_title }} conference meeting at {{ date('h:i A', strtotime($from_date)) }}. Please join on time.<br>

	  Thanks<br>
	  ITDA</p>
        
     
    </body>
</html>