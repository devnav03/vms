@extends('admin.layout.master')
@section('title','Admin :: Visitor List')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Grievance And Query List
            </h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="pull-right">
                <a href="{{ route('admin.'.request()->segment(2).'.create') }}" class="btn btn-primary">Add Grievance And Query</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Grievance And Query List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a href="#">{{ request()->segment(2)}}</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
    </section>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <div class='col-md-12'>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12" style="padding: 15px">
    <div class="table-responsive">
        <table class="display dataTableAjax user-list-table" id="data-table-simple_wrapper" width="100%">
            <thead>
                <tr>
                    <th>S. No.</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
           
        </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
   @push('scripts')
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
                    { "data": "sn" },
                    { "data": "type" },
                    { "data": "user_detail" },
                    { "data": "mobile" },
                    { "data": "email" },
                    { "data": "status" },
                    { "data": "edit",  
                        render: function ( data, type, row ) {
                            console.log(row);
                            if (type === 'display' ) {
                                var btn='';
                                 btn+='<a class="btn btn-warning btn-xs" href="{{ request()->url() }}/'+row.id+'/edit"><i class="fa fa-pencil"></i></a> ';
                               
                                return btn;
                            }
                            return data;
                        },
                    }                
                ]
            });

           
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
        $('#data-table-simple_wrapper').DataTable();
    } );
</script>
@endpush