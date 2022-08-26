<html>
<head>
    <title>{{ $meeting_title }}</title>
</head>
  <body style="padding:10px;border:1px solid #ddcfcf">
      <h1>{{ $meeting_title }}</h1>
        <table style="width: 100%;">
            <tr>
                <td>When</td>
                <td>{{ date('d-M-Y H:i:s', strtotime($from_date)) }} - {{ date('d-M-Y H:i:s', strtotime($to_date)) }}</td>
            </tr>
        </table>
        <br/>
        <p>{{@$descriptions}}</p>
        <br/>
    </body>
</html>