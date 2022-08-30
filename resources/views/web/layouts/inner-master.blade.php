<!DOCTYPE html><html lang="en_US"><head>
  <meta charset="utf-8">    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Visitor Management System | Visitor Registration Page</title>
  <meta name="description" content="Visitor Management System">
  <meta name="keywords" content="Visitor Management System">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('assets/css/osticket.css?cb6766e')}}"/>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css'>
  <link rel="stylesheet" href="{{asset('assets/assets/default/css/print.css?cb6766e')}}" media="print"/>
  <link rel="stylesheet" href="{{asset('assets/scp/css/typeahead.css?cb6766e')}}" media="screen" />
  <link type="text/css" href="{{asset('assets/css/ui-lightness/jquery-ui-1.10.3.custom.min.css?cb6766e')}}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/inter-ui/3.19.3/inter.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('assets/css/jquery-ui-timepicker-addon.css?cb6766e')}}" media="all"/>
  <link rel="stylesheet" href="{{asset('assets/css/thread.css?cb6766e')}}"/>
  <link rel="stylesheet" href="{{asset('assets/css/redactor.css?cb6766e')}}"/>
  <link type="text/css" rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css?cb6766e')}}"/>
  <link type="text/css" rel="stylesheet" href="{{asset('assets/css/flags.css?cb6766e')}}"/>
  <link type="text/css" rel="stylesheet" href="{{asset('assets/css/rtl.css?cb6766e')}}"/>
  <link type="text/css" rel="stylesheet" href="{{asset('assets/css/select2.min.css?cb6766e')}}"/>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css"/>
  <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/bootstrap-icons/bootstrap-icons.css')}}" /> 
  <link rel="stylesheet" href="{{asset('assets/aos/aos.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/glightbox/css/glightbox.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/remixicon/remixicon.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/swiper/swiper-bundle.min.css')}}" />


  
  <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!--   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#datePicker").datetimepicker({
        format: 'DD/MM/YYYY HH:mm:ss',
        
       defaultDate: new Date(),
    });
});    

</script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="{{asset('assets/assets/default/css/theme.css?cb6766e')}}"/>
</head>
<body>
     
             @yield('content')

           <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js'></script><script  src="./script.js"></script>

<script type="text/javascript" src="{{asset('assets/purecounter/purecounter_vanilla.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/aos/aos.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/glightbox/js/glightbox.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/isotope-layout/isotope.pkgd.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/main.js')}}"></script>


<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-auth.js"></script>
    <script type="text/javascript">
        const config = {

            apiKey: "AIzaSyD2N4wFh5t26_tZI9b0AtSZ-P2zfWB7Hn8",
            authDomain: "vztor-255b0.firebaseapp.com",
            databaseURL: "https://vztor-255b0.firebaseio.com",
            projectId: "vztor-255b0",
            storageBucket: "vztor-255b0.appspot.com",
            messagingSenderId: "1038036305715",
            appId: "1:1038036305715:web:8b333ee03dcc6c0d5d6046",
            measurementId: "G-SYLVZH2TQ5"
        };
        
        firebase.initializeApp(config);
    </script>


<script type="text/javascript">  
        // reCAPTCHA widget    
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible',
            'callback': (response) => {
                // reCAPTCHA solved, allow signInWithPhoneNumber.
                onSignInSubmit();
            }
        });

        function otpSend() {
            var signInWithPhoneNumber = document.getElementById('phone').value;
            const appVerifier = window.recaptchaVerifier;
            firebase.auth().signInWithPhoneNumber(signInWithPhoneNumber, appVerifier)
                .then((confirmationResult) => {
                    // SMS sent. Prompt user to type the code from the message, then sign the
                    // user in with confirmationResult.confirm(code).
                    window.confirmationResult = confirmationResult;
                    document.getElementById("sent-message").innerHTML = "Message sent succesfully.";
                    document.getElementById("sent-message").classList.add("d-block");
                }).catch((error) => {
                    // alert(error.message);
                    document.getElementById("error-message").innerHTML = error.message;
                    document.getElementById("error-message").classList.add("d-block");
                });
        }

        function otpVerify() {
            var code = document.getElementById('otp-code').value;
            confirmationResult.confirm(code).then(function (result) {
                // User signed in successfully.
                var user = result.user;

                document.getElementById("sent-message").innerHTML = "You are succesfully logged in.";
                document.getElementById("sent-message").classList.add("d-block");
      
            }).catch(function (error) {
                document.getElementById("error-message").innerHTML = error.message;
                document.getElementById("error-message").classList.add("d-block");
            });
        }
    </script>

<script type="text/javascript">

$('#location_id').on('change', function() {

        var location_id = this.value;
        $("#building_id").html('');
        $("#department_id").html('');
        // $("#officer_id").html('');
        getBuilding(location_id);
      });
      function getBuilding(location_id){
          $("#department_id").html('');
          // $("#officer_id").html('');
                 $("#loader-p").show();
        
          $.ajax({
            url:"{{url('/web-get-building-front')}}",
            type: "POST",
            data: {
            location_id: location_id,
            _token: '{{csrf_token()}}'
            },
          dataType : 'json',
          success: function(result){
          //  alert(result);
             $("#loader-p").hide();
            $("#building_id").append('<option value="">Select Building</option>');
            $.each(result,function(key,value){
            $("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
            if(key==0){
            getDepartment(value.id);
              }
            });

           }
         });
      }
    function getDepartment(building_id){
      // $("#officer_id").html('');
      $("#loader-p").show();
       $.ajax({
            url:"{{url('/web-get-department')}}",
            type: "POST",
            data: {
            building_id: building_id,
            _token: '{{csrf_token()}}'
            },
            dataType : 'json',
            success: function(result){
              $("#loader-p").hide();
              $("#department_id").append('<option value="">Select Department</option>');
              $.each(result,function(key,value){
              $("#department_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
              // if(key==0){
              //   getOffice(value.id);
              // }
              });
            }
      });
    }

$('input[name=mobile]').on('keyup' , function() { 
    var mobile = $("input[name=mobile]").val();
    if( mobile.length == 10 ) {
        $.ajax({
            type: "GET",
            url: "{{ route('otp-sent') }}",
            data: {'mobile' : $("input[name=mobile]").val()},
            success: function(data){
                if(data.status == 'Fail'){
                    $(".already_exist").html('Mobile no is already exist');
                    $(".valid_no").html('');
                    $(".otp").val('');
                    $(".otp_sent").html('');
                } else{
                    $(".otp_sent").html('OTP sent...');
                    $(".valid_no").html('');
                    $(".already_exist").html('');
                }
            }
        });
    } else {
        $(".valid_no").html('Enter a valid mobile no');
        $(".otp").val('');
        $(".already_exist").html('');
        $(".otp_sent").html('');
    }
}); 


$('input[name=otp]').on('keyup' , function() { 
    var otp = $("input[name=otp]").val();
    var mobile = $("input[name=mobile]").val();
    if( otp.length == 6 ) {
        $.ajax({
            type: "GET",
            url: "{{ route('otp-match') }}",
            data: {'otp' : otp, 'mobile' : mobile },  
            success: function(data){
                if(data.status == 'Fail'){
                    $(".not_verify").html('Invalid OTP');
                    $(".otp_verify").html('');
                    $(".errors_otp").html('');
                } else{
                    $(".not_verify").html('');
                    $(".otp_verify").html('OTP Varify');
                    $(".errors_otp").html('');
                }
            }
        });
    } else {
        $(".not_verify").html('');
        $(".otp_verify").html('');
    }
}); 


function ResendOTP(val) {
  var mobile = $("input[name=mobile]").val();
  if( mobile.length == 10 ) {
    $.ajax({
        type: "GET",
        url: "{{ route('resend-otp') }}",
        data: {'mobile' : mobile},
        success: function(data){
          if(data.status == 'Fail'){
            $(".otp_resent").html('');
            $(".valid_no").html('Mobile no is already exist'); 
          } else {
            $(".otp_resent").html('OTP sent successfully');
            $(".valid_no").html('');
          }
        }
    });

  } else {
    $(".valid_no").html('Enter a valid mobile no');
    $(".otp_resent").html('');
  }
} 

imgInp.onchange = evt => {
  const [file] = imgInp.files
  if (file) {
    blah.src = URL.createObjectURL(file)
    $('.suc_btn').show();
    $('.ped_btn').hide();
  }
}

function getState(val) {
  $.ajax({
    type: "GET",
    url: "{{ route('getState') }}",
    data: {'country_id' : val},
    success: function(data){
        $("#state-list").html(data);
    }
  });
}

function getCity(val) {
  $.ajax({
    type: "GET",
    url: "{{ route('getCity') }}",
    data: {'state_id' : val},
    success: function(data){
        $("#city-list").html(data);
    }
  });
}

</script>

<!-- <script type="module">

  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.9.3/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.9.3/firebase-analytics.js";

  const firebaseConfig = {
    apiKey: "AIzaSyD2N4wFh5t26_tZI9b0AtSZ-P2zfWB7Hn8",
    authDomain: "vztor-255b0.firebaseapp.com",
    projectId: "vztor-255b0",
    storageBucket: "vztor-255b0.appspot.com",
    messagingSenderId: "1038036305715",
    appId: "1:1038036305715:web:8b333ee03dcc6c0d5d6046",
    measurementId: "G-SYLVZH2TQ5"
  };

  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script> -->





  @if(Session::has('message'))
       <script type="text/javascript">
        Command: toastr["{{Session::get('class')}}"](" {{Session::get('message')}}")
      </script>
  @endif    <script type="text/javascript" src="{{asset('assets/js/select2.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js">
  </script>
  @stack('scripts')
</body>
</html>
