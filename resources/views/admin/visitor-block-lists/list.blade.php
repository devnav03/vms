@extends('admin.layout.master')
@section('title','Admin :: Blocked Visitors List')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Blocked Visitors List
            </h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="pull-right">
                <a href="{{ route('admin.'.request()->segment(2).'.create') }}" class="btn btn-primary"></a>
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
                <h5 class="breadcrumbs-title"> Blocked Visitors List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Blocked Visitors</a>
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
    <!--<a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add Visitor</a>-->
    <div class="table-responsive">
        <table class="display dataTableAjax user-list-table" id="data-table-simple_wrapper" width="100%">
            <thead>
                <tr>
                    <th style="padding-right: 0px;">S.no.</th>
                    <th style="padding-right: 0px;">#Visitor Id</th>
                    <th style="padding-right: 0px;">Name</th>
                    <th style="padding-right: 0px;">Image</th>
                    <th style="padding-right: 0px;">Added By</th>
                    <th style="padding-right: 0px;">Email</th>
                    <th style="padding-right: 0px;">Mobile No</th>
                    <th style="padding-right: 0px;">Services</th>
                    <th style="padding-right: 0px;">Status</th>
                    <th style="padding-right: 0px; width: 251px;">Action</th>
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
                    d.dateFrom = $('#dateFrom').val();
                    d.dateTo = $('#dateTo').val();
                    d.activation_date_from = $('#activation_date_from').val();
                    d.activation_date_to = $('#activation_date_to').val();
                    d.mobile = $('#mobile').val();
                    d.name = $('#name').val();
                    d.level_id = $('#level_id').val();
                    d.status = $('#status').val();
                    d.user_type = $('#user_type').val();
                    d.plan_detail_id = $('#plan_detail_id').val();
                    d.team_promotional_level = $('#team_promotional_level').val();
                    d.kyc_status = $('#kyc_status').val();
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
                {
                    data: 'SrNo',
                    render: function (data, type, row, meta)
                    {
                        return meta.row + 1;
                    }
                },
                { "data": "refer_code" },
                { "data": "name",
                    render: function ( data, type, row ) {
                            return '<a style="cursor: pointer; color: #0087fb" onclick="userLogin('+row.id+')">'+row.name+'</a>';
                    },
                },
                { "data": "image",
                    render: function ( data, type, row ) {
                            return '<img src='+row.image+' style="width: 100px;height: 100px;">';
                    },
                },
                { "data": "parent_detail" },
                { "data": "email" },
                { "data": "mobile" },
                { "data": "services" },
                { "data": "status" },
                // { "data": "Appoint_status" },
                { "data": "edit",
                    render: function ( data, type, row ) {
                        if (type === 'display' ) {
                            var btn='';
                            btn+='<a class="btn btn-warning btn-xs mdi-editor-border-color" style="background-color:#0007d8; padding: 0px 6px; margin-right: 5px;" href="{{ request()->url() }}/'+row.id+'/edit"></a>';
                            if( row.appo_status!=1)
                            {
                                btn+='<button type="button" onclick="changestatus(this)" data-action="{{ route('admin.'.request()->segment(2).'.update','') }}/'+row.id+'" class="btn btn-xs btn-danger mdi-image-panorama-fisheye" style="background-color: #297324; padding: 0px 6px; margin-right: 5px;" title="Unblock"></button>';
                            }
                            else
                            {
                                btn+='<button type="button" onclick="changestatus(this)" data-action="{{ route('admin.'.request()->segment(2).'.update','') }}/'+row.id+'" class="btn btn-xs btn-check mdi-content-block" style="background-color: #dc0047; padding: 0px 6px; margin-right: 5px;" title="Block"></button>';
                            }
                            btn+='<a class="btn btn-xs mdi-social-notifications" style="padding: 0px 6px; background-color:#09ecd8;" href="{{ request()->url() }}/'+row.id+'/Panic"></a>';
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
function userLogin(data){
    $.ajax({
            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
            url:'{{url('/admin-panel/user-login')}}/'+data+'',
            method: 'GET',
            dataType:'json',
            success:function(response){
                if(response.error == false){
                    window.open("{{route('user.dashboard')}}", '_blank');
               }else{
                    toastr.error(response.msg);
               }
            }
        });
}
</script>
<script type="text/javascript">

   $(document).ready(function() {
    $('#data-table-simple_wrapper').DataTable();
} );
</script>
@endpush
