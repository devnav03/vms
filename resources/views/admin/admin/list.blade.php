@extends('admin.layout.master')
@section('title','Admin :: Employee List')
@section('content')
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Employee List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Employee</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
    </section>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <div class="container" style="padding: 18px;">
    <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add New</a>
    <a href="{{ adminRoute('bulk_insert')}}"  class="btn btn-primary" style="float: right; margin: 10px 16px 0px 0px">Bulk Add</a>
        <!-- @can('add')
        <div class="col-md-8 text-right">
            <a href="{{ route('admin.'.request()->segment(2).'.create') }}" class="btn btn-success btn-xs">Add Admin</a>
        </div>
        @endcan -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered dataTableAjax" id="data-table-simple_wrapper" >
                    <thead>
                        <tr>
                            <th>S.no.</th>
                            <th>Officer Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Device Name</th>
							<th>Location Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.dataTableAjax').DataTable({
                "processing": true,
                "serverSide": true,
                'ajax' : {
                    'url':'{{ route('admin.'.request()->segment(2).'.index') }}',
                    data:{'_method':'patch','_token':'{{ csrf_token() }}'},
                    method:'post'
                },
                "columns": [
                    {
                        data: 'SrNo',
                        render: function (data, type, row, meta)
                        {
                            return meta.row + 1;
                        }
                    },
                    { "name": "id" },
                    { "name": "name" },
                    { "name": "name" },
                    { "name": "name" },
                    { "name": "name" },
                    { "name": "name" },
				 	{ "name": "name" },
                    { "name": "action",
                        render: function ( data, type, row ) {
                            if (type === 'display' ) {
                                var btn='';
                                 btn+='<a class="btn btn-xs mdi-editor-border-color" style="padding: 0px 6px; background-color:#0007d8;" href="{{ request()->url() }}/'+row[0]+'/edit"></a> ';
                                 btn+='<button type="button" onclick="distroy(this)" style="padding: 0px 6px; background-color:#09ecd8;" data-action="{{ url("admin-employee-destroy") }}/'+row[0]+'/" class="btn btn-xs mdi-content-clear"></button>';
                                return btn;
                            }
                            return data;
                        },
                    }
                ]
            });
        });
    </script>
@endpush
@push('scripts')
   <script type="text/javascript">
       $('td').on('click', '.delete', function (e) {
            var form =  document.createElement("form");
            var node = document.createElement("input");
            form.action = $(this).attr('action');
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
        });
   </script>
    <script type="text/javascript">

   $(document).ready(function() {
    $('#data-table-simple_wrapper').DataTable();
    } );


    function distroy(element){
        if (confirm('Are you sure to delete Employee?')){
            window.location.href =$(element).attr('data-action');
        }
        return false;
    }
    


</script>
@endpush
