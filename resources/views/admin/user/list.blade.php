@extends('admin.layout.master')
@section('title','Admin :: Visitor List')
@section('head')

<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Visitor List
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
	<!-- Modal Structure -->
  <div id="modal1" class="modal modalpanic" style="background-color: #ff4081;">
    <div class="modal-content" align="center">
      <i class="mdi-social-notifications" style="font-size: 1100%; color:white"></i>
      <p style="font-size: 45px; color:white">Panic Alert</p>
    </div>
    <div class="modal-footer" style="background-color: #ff4081;">
      <a class="waves-effect waves-light btn modal-trigger modal-close">Close</a>
    </div>
  </div>


    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Visitor List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Visitor</a>
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
    <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add New</a>
    <div class="table-responsive">
        <table class="display dataTableAjax" id="data-table-simple_wrapper" width="100%">
            <thead>
                <tr>
                    <!--<th style="padding-right: 0px;">S.no.</th>-->
                    <th style="padding-right: 0px; width: 100px;">#Visitor Id</th>
                    <th style="padding-right: 0px; width: 100px;">Name</th>
                    <th style="padding-right: 0px; width: 100px;">Image</th>
                    <th style="padding-right: 0px; width: 100px;">Added By</th>
                    <th style="padding-right: 0px;">Email</th>
                    <th style="padding-right: 0px; width: 100px;">Mobile No</th>
                    <th style="padding-right: 0px; width: 100px;">Services</th>
                    <th style="padding-right: 0px; width: 150px;">Status</th>
                    <th style="padding-right: 0px; width: 201px;">Action</th>
                </tr>
            </thead>

        </table>
        </div>
    </div>

@endsection
@push('scripts')

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
                // {
                //     data: 'SrNo',
                //     render: function (data, type, row, meta)
                //     {
                //         return meta.row + 1;
                //     }
                // },
                { "data": "refer_code" },
                { "data": "name"},
                { "data": "image",
                    render: function ( data, type, row ) {
                            return '<img src='+row.image+' style="width: 50px;height: 50px;border-radius: 50%;">';
                    },
                },
                { "data": "parent_detail" },
                { "data": "email" },
                { "data": "mobile" },
                { "data": "services" },
                { "data": "status"
          //       ,
					// render: function(data, type, row){
					// 	if(type ==='display'){
					// 		var stt ='<select style="display: block; margin: 0px 0px -14px -6px;" class="form-control status" id="select_status'+row.id+'" onchange="changestatusNew('+row.id+')">'+
					// 		'<option value="">change status</option>'+
					// 		'<option value="0">Pending</option>'+
					// 		'<option value="1">Approve</option>'+
					// 		'<option value="2">Block</option>'+
					// 		'</select><p id="status_'+row.id+'">'+row.status+'</p>';
          //
					// 		return stt;
					// 	}
					// }

				},
                { "data": "edit",
                    render: function ( data, type, row ) {
                        if (type === 'display' ) {
                            var btn='';
                            btn+='<a class="btn btn-warning btn-xs mdi-editor-border-color" style="background-color:#0007d8; padding: 0px 6px; margin-right: 5px;" href="{{ request()->url() }}/'+row.id+'/edit"></a>';
                            //if( row.appo_status!=1)
                            //{
                            //    btn+='<button type="button" onclick="changestatus(this)" data-action="{{ route('admin.'.request()->segment(2).'.update','') }}/'+row.id+'" class="btn btn-xs btn-danger mdi-image-panorama-fisheye" style="background-color: #297324; padding: 0px 6px; margin-right: 5px;" title="Block"></button>';
                            //}
                           // else
                            //{
                           //     btn+='<button type="button" onclick="changestatus(this)" data-action="{{ route('admin.'.request()->segment(2).'.update','') }}/'+row.id+'" class="btn btn-xs btn-check mdi-content-block" style="background-color: #dc0047; padding: 0px 6px; margin-right: 5px;" title="Unblock"></button>';
                           // }
                            btn+='<button type="button" onclick="panicAlert(this)" data-action="{{ route('admin.'.request()->segment(2).'.panicAleart') }}/'+row.id+'" class="btn btn-xs mdi-social-notifications" style="padding: 0px 6px; background-color:#09ecd8;"></button>';
                            btn+='<a class="btn btn-warning btn-xs mdi-action-visibility" title="View Slip" target="_blank" style="margin-top: 0px; padding: 0px 0px; margin-left: 4px; width: 26px; color: #fff; font-size: 17px;" href="'+row.slip_id+'"></a>';
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


function changestatusNew(id){
	var status=$('#select_status'+id).val();
	if(status=='0'){
		var stat = '<span style="color:red;">Pending</span>';
	}
	if(status=='1'){
		var stat = '<span style="color:green;">Approve</span>';
	}
	if(status=='2'){
	   var stat = '<span style="color:red;">Block</span>';
	}
	if (confirm('Are you sure to change this current status')){
        $.ajax({
           url:'{{route('change.status')}}',
            method: 'post',
            data:{'_method':'put','_token':'{{ csrf_token() }}','id':id,'status':status,'types':'only_status' },
            dataType:'json',
            success:function(response){
				$('#status_'+id).html(stat);
                Command: toastr[response.class](response.message);

            }
        });
    }
    return false;
}
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


function panicAlert(element){
    if (confirm('Are you sure to send panic alert to your emergency contact number !!')){

        $.ajax({
            url:$(element).attr('data-action'),
            method: 'get',
            data:{'_method':'put','_token':'{{ csrf_token() }}','types':'panic' },
            dataType:'json',
            success:function(response){
                //$('.dataTableAjax').DataTable().draw();
				$(".modal").css("display", "block");
				Command: toastr[response.class](response.message);

				//location.reload();
            }
        });
    }
    return false;
}
</script>
<script type="text/javascript">

   $(document).ready(function() {
        $('#data-table-simple_wrapper').DataTable();
    });

</script>


@endpush
