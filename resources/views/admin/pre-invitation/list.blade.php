@extends('admin.layout.master')
@section('title','Admin :: Pre Invitation List')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Pre Invitation List
            </h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="pull-right">
                <a href="{{ route('admin.'.request()->segment(2).'.create') }}" class="btn btn-primary">Add New</a>
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
                <h5 class="breadcrumbs-title"> Pre Invitation List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Pre Invitation</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
    </section>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <div class='col-md-12' style="padding: 65px 0px 0px 65px;">
        <div class="transaction_summary">
            <div class="row">    
                <div class="col s4">    
                    <div class="form-group">    
                        <select class="form-control" id="visit_type" name="visit_type">    
                            <option value="0">All Type Visitor</option>
                            <option value="1">Old Visitor</option>
                            <option value="2">Upcoming visitor</option>    
                        </select>    
                    </div>    
                </div> 
    
                <div class="col s1">    
                    <div class="clearfix s3">    
                        <button type="button" name="search" id="searchBtn" value="Search" class="btn btn-primary pull-right">Search</button>    
                    </div>    
                </div>
    
            </div>
    
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12" style="padding: 15px">
    <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add New</a>
    <div class="table-responsive">
        <table class="display dataTableAjax user-list-table" id="data-table-simple_wrapper" width="100%">
            <thead>
                <tr>
                    <th style="padding-right: 0px;">S.no.</th>
                    <th style="padding-right: 0px;">#Visitor Id</th>
                    <th style="padding-right: 0px;">Name</th>
                    <th style="padding-right: 0px;">Mobile No</th>
                    <th style="padding-right: 0px;">Pre Invitation Date & Time</th>
                    <th style="padding-right: 0px;">Action</th>
                </tr>
            </thead>

        </table>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> -->
   <script type="text/javascript">
        $(document).ready(function() {
             var table2 = $('.dataTableAjax').DataTable({
                "processing": true,
                "serverSide": true,
                'ajax' : {
                    'url':'{{ route('admin.'.request()->segment(2).'.index') }}',
                    "method": "POST" ,
                     'pages':1,
                     data:{
                      '_method':'patch',
                      '_token':'{{ csrf_token() }}'
                    },
                    'data': function (d) {
                        d.visit_type = $('#visit_type').val();
                        d._token = '{{ csrf_token() }}';
                        d._method = 'PATCH';
                    }
                },
                 fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    if ( aData[8] == 0 )
                    {
                        $('td', nRow).css('background-color', '#f3cfbd');
                    }
                },
                "columns": [
                    {data: 'SrNo',
                       render: function (data, type, row, meta) {
                            return meta.row + 1;
                       }
                    },
                    { "data": "refer_code" },
                    { "data": "name"},
                    { "data": "mobile" },
                    { "data": "pre_visit_date_time" },
                    // { "data": "status" },
                    // { "data": "Appoint_status" },
                    { "data": "edit",
                        render: function ( data, type, row ) {
                            if (type === 'display' ) {
                                var btn='';
                                btn+='<a class="btn btn-warning btn-xs mdi-editor-border-color" style="padding: 0px 6px; background-color:#0007d8; margin-right: 5px;" href="{{ request()->url() }}/'+row.id+'/edit"></a>';
                                btn+='<a class="btn btn-warning btn-xs" style="padding: 0px 6px; background-color:#297324; margin-right: 5px;" href="{{ request()->url() }}/'+row.id+'/re-invite">Re-Invite</a>';
                                // if( row.appo_status!=1)
                                // {
                                //     btn+='<button type="button" onclick="changestatus(this)" data-action="{{ route('admin.'.request()->segment(2).'.update','') }}/'+row.id+'" class="btn btn-xs btn-danger mdi-image-panorama-fisheye" style="background-color: #297324; padding: 0px 6px; margin-right: 5px;" title="Do Active"></button>';
                                // }
                                // else
                                // {
                                //     btn+='<button type="button" onclick="changestatus(this)" data-action="{{ route('admin.'.request()->segment(2).'.update','') }}/'+row.id+'" class="btn btn-xs btn-check mdi-content-block" style="background-color: #dc0047; padding: 0px 6px; margin-right: 5px;" title="Do Inactive"></button>';
                                // }
                                //btn+='<a class="btn btn-xs mdi-social-notifications" style="background-color:#09ecd8; padding: 0px 6px;" href="{{ request()->url() }}/'+row.id+'/Panic"></a>';
                                return btn;
                            }
                            return data;
                        },
                    }
                ]
            });
            $('#searchBtn').click(function(){
                table2.draw();
            });
        });
    </script>
 <script type="text/javascript">
function changestatus(element){
    if (confirm('Are you sure to change this current status')){
        $.ajax({
            url:$(element).attr('data-action'),
            method: 'post',
            data:{'_method':'put','_token':'{{ csrf_token() }}','types':'status' },
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
<script type="text/javascript">

   $(document).ready(function() {
    $('#data-table-simple_wrapper').DataTable();
} );
</script>
@endpush
