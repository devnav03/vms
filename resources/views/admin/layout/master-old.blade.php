<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="slick, flat, dashboard, bootstrap, admin, template, theme, responsive, fluid, retina">
    <link rel="shortcut icon" href="javascript:;" type="image/png">
    <link rel="shortcut icon" href="{{ asset('assets/image/icons/T-20-icon.png')}}" type="image/x-icon">
     <link rel="icon" href="{{ asset('assets/image/icons/T-20-icon.png')}}" type="image/x-icon">
    <title>@yield('title','Admin')</title>

    <!--right slidebar-->
    <link href="{{ asset('admin-asset-old/css/slidebars.css') }}" rel="stylesheet">

    <!--switchery-->

    <!--common style-->
    <link href="{{ asset('admin-asset-old/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-asset-old/css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-asset-old/css/layout-theme-two.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-asset-old/css/lightbox.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    @stack('links')
</head>

<body class="sticky-header">

    <section>
        <!-- sidebar left start-->
            @include('admin.include.leftmenu-old')
        <!-- sidebar left end-->

        <!-- body content start-->
        <div class="body-content">

            <!-- header section start-->
                @include('admin.include.topbar-old')
            <!-- header section end-->

            @yield('head')

            <!--body wrapper start-->
            <div class="wrapper">

                @yield('content')
                
            </div>
            <!--body wrapper end-->


            <!--footer section start-->
            <footer>
                2021 &copy; VMS
            </footer>
            <!--footer section end-->


            <!-- Right Slidebar start -->
                @include('admin.include.rightmenu')
            <!-- Right Slidebar end -->

        </div>
        <!-- body content end-->
    </section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ asset('admin-asset-old/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ asset('admin-asset-old/js/jquery-migrate.js') }}"></script>
<script src="{{ asset('admin-asset-old/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-asset-old/js/modernizr.min.js') }}"></script>
<script src="{{ asset('admin-asset-old/js/lightbox.js') }}"></script>
<script src="{{ asset('admin-asset-old/js/dropzone.js') }}"></script>

<!--right slidebar-->
<script src="{{ asset('admin-asset-old/js/slidebars.min.js') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<!--common scripts for all pages-->
<script src="{{ asset('admin-asset/js/scripts.js') }}"></script>
@if (Session::has('message'))
    <script type="text/javascript">
        Command: toastr["{{Session::get('class')}}"](" {{Session::get('message')}}")
    </script>
@endif

<script type="text/javascript">
 $.fn.DataTable.ext.pager.numbers_length = 5;
    $(document).ready(function() {
        $('.datatable').DataTable();
    });
     $('.select2').select2();

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
</script>
@stack('scripts')
</body>
</html>
