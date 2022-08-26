
<!-- CORE JS FRAMEWORK - START --> 
<script src="{{asset('admin-asset/js/jquery-1.11.2.min.js')}}" type="text/javascript"></script> 
<script src="{{asset('admin-asset/js/jquery.easing.min.js')}}" type="text/javascript"></script> 
<script src="{{asset('admin-asset/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script> 
<script src="{{asset('admin-asset/plugins/pace/pace.min.js')}}" type="text/javascript"></script>  
<script src="{{asset('admin-asset/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}" type="text/javascript"></script> 
<script src="{{asset('admin-asset/plugins/viewport/viewportchecker.js')}}" type="text/javascript"></script> 
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> --}}
<script src="{{asset('admin-asset/plugins/select2/select2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('admin-asset/plugins/multi-select/js/jquery.multi-select.js')}}" type="text/javascript"></script> 
<script src="{{asset('admin-asset/plugins/multi-select/js/jquery.quicksearch.js')}}" type="text/javascript"></script>
{{-- <script src="{{asset('admin-asset/plugins/typeahead/typeahead.bundle.js')}}" type="text/javascript"></script> --}}
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="{{asset('admin-asset/js/scripts.js')}}" type="text/javascript"></script> 

<!-- END CORE TEMPLATE JS - END --> 
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@if (Session::has('message'))
    <script type="text/javascript">
        Command: toastr["{{Session::get('class')}}"](" {{\Session::get('message')}}")
    </script>
@endif

<script type="text/javascript">
	$(document).ready(function() {
	    $('.datatable').DataTable();

       
        
        
	});
     $('.select2').select2();
</script>
<script src="{{asset('admin-asset/js/datatable-init.js')}}" type="text/javascript"></script>
<script type="text/javascript">
function deleteData(element){  
    if (confirm('Are you sure to delete this data')){                      
        $.ajax({
            url:$(element).attr('data-action'),
            method: 'post',
            data:{'_method':'DELETE','_token':'{{ csrf_token() }}'},
            dataType:'json',
            success:function(response){
                $('.dataTableAjax').DataTable().draw();
                Command: toastr[response.class](response.message);
            }
        });
    }
    return false;

}  
</script>