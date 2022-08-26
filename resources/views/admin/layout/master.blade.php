<!DOCTYPE html>

<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="msapplication-tap-highlight" content="no">

    <meta name="description" content="">

    <meta name="keywords" content="">

    <title>@yield('title','Admin')</title>



    <!-- Favicons-->

    <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">

    <!-- Favicons-->

    <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">

    <!-- For iPhone -->

    <meta name="msapplication-TileColor" content="#00bcd4">

    <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">

    <!-- For Windows Phone -->

    <!-- CORE CSS-->

    <link href="{{ asset('admin-asset/css/materialize.css')}}" type="text/css" rel="stylesheet" media="screen,projection">

    <link href="{{ asset('admin-asset/css/style.css')}}" type="text/css" rel="stylesheet" media="screen,projection">

      <link href="https://cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection">





    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->

    <link href="{{ asset('admin-asset/js/plugins/perfect-scrollbar/perfect-scrollbar.css')}}" type="text/css" rel="stylesheet" media="screen,projection">

    <link href="{{ asset('admin-asset/js/plugins/jvectormap/jquery-jvectormap.css')}}" type="text/css" rel="stylesheet" media="screen,projection">
    
    <link href="{{ asset('admin-asset/js/plugins/chartist-js/chartist.min.css')}}" type="text/css" rel="stylesheet" media="screen,projection">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css"/>

    @stack('links')

</head>

<body>

  
    <!-- End Page Loading -->

    @include('admin.include.topbar')

       <!-- START MAIN -->

    <div id="main">

        <!-- START WRAPPER -->

        <div class="wrapper">

            <!-- sidebar left start-->

            @include('admin.include.leftmenu')

            <!-- sidebar left end-->

            @yield('content')

        </div>

    </div>

   <!-- ================================================

    Scripts

    ================================================ -->



    <script src="{{ asset('admin-asset/js/jquery-1.10.2.min.js') }}"></script>



    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- Date Range -->
    <!-- <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" /> -->
    
    <!-- Include Date Range Picker -->
    <!-- <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" /> -->

    <!--materialize js-->

    <script type="text/javascript" src="{{ asset('admin-asset/js/materialize.min.js')}}"></script>

    <!--scrollbar-->

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

    <!-- chartist -->

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/chartist-js/chartist.min.js')}}"></script>



    <!-- chartjs -->

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/chartjs/chart.min.js')}}"></script>

		{{--<script type="text/javascript" src="{{ asset('admin-asset/js/plugins/chartjs/chart-script.js')}}"></script>--}}



    <!-- sparkline -->

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/sparkline/sparkline-script.js')}}"></script>



    <!--jvectormap-->

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins/jvectormap/vectormap-script.js')}}"></script>





    <!--plugins.js - Some Specific JS codes for Plugin Settings-->

    <script type="text/javascript" src="{{ asset('admin-asset/js/plugins.js')}}"></script>



<script src="{{ asset('admin-asset/js/scripts.js') }}"></script>
 




        <!--common scripts for all pages-->

@if (Session::has('message'))

    <script type="text/javascript">

        Command: toastr["{{Session::get('class')}}"](" {{Session::get('message')}}")

    </script>

@endif
@stack('scripts')


<script type="text/javascript">


 $.fn.DataTable.ext.pager.numbers_length = 5;

    $(document).ready(function() {

        $('.datatable').DataTable();

    });

  //   $('.select2').select2();



</script>

<script type="text/javascript">

function deleteData(url,callback=null){

    if (confirm('Are you sure to delete this data')){

        $.ajax({

            url:url,

            method: 'post',

            data:{'_method':'DELETE','_token':'{{ csrf_token() }}'},

            dataType:'json',

            success:function(response){



                if(response.class){

                    Command: toastr[response.class](response.message);



                }

                if(document.getElementsByClassName('dataTableAjax')){

                    $('.dataTableAjax').DataTable().draw();

                }

                if(document.getElementsByClassName('datatable')){

                    setTimeout(function(){

                        window.location.reload();

                    }, 300)

                    $('.datatable').DataTable().draw();



                }

                if(callback)

                    callback(callback);

            }

        });

    }

    return false;

}

function deleteForm(url){

    if (confirm('Are you sure to delete this data')){

        var form =  document.createElement("form");

        var node = document.createElement("input");

        form.action = url;

        form.method = 'POST' ;

        node.name  = '_method';

        node.value = 'delete';

        form.appendChild(node.cloneNode());

        node.name  = '_token';

        node.value = '{{ csrf_token() }}';

        form.appendChild(node.cloneNode());

        form.style.display = "none";

        document.body.appendChild(form);

        form.submit();

        document.body.removeChild(form);

    }

}





function select2(url,selected=null){

    var select2Ajax = $("#select2Ajax").select2({

        ajax: {

            url: url,

            dataType: 'json',

            quietMillis: 100,

            data: function(term, page) {

                return {

                    limit: 20,

                    q: term,

                    _method: 'patch',

                    _token: '{{ csrf_token() }}'

                };

            },



        },

        initSelection: function(element, callback) {

            callback(selected?selected:[]);

        },

        minimumInputLength: 1,

        templateResult: function(repo){

            return repo.name

        },

        templateSelection: function (repo) {

            return repo.name;

        }



      });

        }
        $(".modal-close").click(function(){
        	$(".modalpanic").css("display", "none");

        });

window.onclick = function(event) {
       $(".modalpanic").css("display", "none");

}
</script>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6155bce1d326717cb684169a/1fgrejl5m';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
