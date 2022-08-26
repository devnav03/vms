@php
    $transaction_types = App\Model\TransactionType::select('id', 'name')->whereIn('id', [4,7,18,24,26,27,29,30,31,32,33,34])->get();
@endphp
@extends('admin.layout.master')
@section('title','Admin :: All Transactions')
@section('head')
<div class="page-head">
    {{Form::open(['route'=>'admin.'.request()->segment(2).'.store', 'class'=>'form-inline', 'id'=>'generate-income'])}}
    <div class="row">
        <div class="col-md-3">
            <h3 class="m-b-less">
            All Transactions
            </h3>
        </div>
        <div class="col-md-2">
            {{-- <input type="button" id="btnExport" class="btn btn-warning" value="Export Excel" onclick="Export()" /> --}}
        </div>
    </div>
    {{Form::close()}}
</div>
@endsection
@section('content')
  <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> All Transactions Reports</h5>
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
    <div class="col s12" style="padding: 20px;">
    <div class="transaction_summary">
        <div class="row">
            <div class="col s3">
                <div class="summary_container">
                    <h5 class="text-center"><strong>Summary</strong></h5>
                    <div class="summary_details">
                        <p>Amount:</p>
                        <p id="paid_amount"></p>
                    </div>
                    <div class="summary_details">
                        <p>Admin:</p>
                        <p id="admin_charge"></p>
                    </div>
                    <div class="summary_details">
                        <p>Tds:</p>
                        <p id="tds_charge"></p>
                    </div>
                    <div class="summary_details">
                        <p>Total:</p>
                        <p id="total_amount"></p>
                    </div>
                </div>
            </div>
            <div class="col s4">
                <div class="form-group">
                    <select class="form-control" id="transaction_type" name="transaction_type">
                        <option value="0">All Transactions</option>
                        @foreach($transaction_types as $transaction_type)
                            <option value="{{$transaction_type->id}}">{{$transaction_type->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col s3">
                <div class="form-group">
                    <select class="form-control" id="date_type" name="date_type" onchange="checkDateType()">
                        <option value="0">For Today</option>
                        <option value="1">Other Date</option>
                    </select>
                </div>
            </div>

            <div id="select_date">
                <div class="col s3">
                    <label>Date From</label>
                    <div class="form-group">
                        <input type="date" name="date_from" id="date_from" class="form-control">
                        <small class="text-danger date_from"></small>
                    </div>
                </div>
                <div class="col s3">
                    <label>Date To <i>(Optional)</i></label>
                    <div class="form-group">
                        <input type="date" name="date_to" id="date_to" class="form-control"/>
                        <small class="text-danger">{{$errors->first('date_to')}}</small>
                    </div>
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
    <div class="col s12" style="padding: 0px 15px 0px 15px">
    <div class="table-responsive">
        <table class="display dataTableAjax user-list-table " width="100%">
            <thead>
                <tr>
                    <th>Txn Id</th>
                    <th> From</th>
                    <th> Cr/Dr</th>
                    <th> Type</th>
                    <th> To</th>
                    <th> Amount</th>
                    <th> Tds</th>
                    <th> Admin</th>
                    <th> Total</th>
                    <th> Remark</th>
                    <th> Date </th>
                </tr>
            </thead>
        </table>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://rawgit.com/unconditional/jquery-table2excel/master/src/jquery.table2excel.js"></script>

   <script type="text/javascript">
        $(document).ready(function() {    
             var table2 = $('.dataTableAjax').DataTable({
                "processing": true,
                "serverSide": true,
                'ajax' : {
                    'url':'{{ route('admin.'.request()->segment(2).'.index') }}',
                    "method": "POST" ,
                     'pages':1,
                    'data': function (d) { 
                        d._token = '{{ csrf_token() }}';
                        d._method = 'PATCH';    
                        d.date_from = $('#date_from').val(); 
                        d.date_to = $('#date_to').val();                 
                        d.date_type = $('#date_type').val();                 
                        d.transaction_type = $('#transaction_type').val();                 
                    },

                    error:function(error){
                        $.each(error.responseJSON.errors, function(key, value){
                            $('.'+key+'').text(value);
                        });
                    }

                },

                drawCallback: function(result){ 
                    $('small').empty();
                    $('#paid_amount').text(result.json.transaction_detail?result.json.transaction_detail.amount:'');
                    $('#tds_charge').text(result.json.transaction_detail?result.json.transaction_detail.tds_charge:'');
                    $('#admin_charge').text(result.json.transaction_detail?result.json.transaction_detail.admin_charge:'');
                    $('#total_amount').text(result.json.transaction_detail?result.json.transaction_detail.total:'');

                },

                fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    console.log([nRow, aData, iDisplayIndex, iDisplayIndexFull]);
                    if (aData['type'] == 'dr'){
                        $('td', nRow).css('background-color', '#ff000061');
                    }
                   
                },

                "columns": [
                    { "data": "txn_id"},
                    { "data": "from_user" },
                    { "data": "type"},
                    { "data": "transaction_type" },
                    { "data": "to_user" },
                    { "data": "amount" },
                    { "data": "tds_charge" },
                    { "data": "admin_charge" },
                    { "data": "total" },
                    { "data": "remark" },
                    { "data": "date" },
                ]
            });
            $('#searchBtn').click(function(){
                table2.draw();
            });

        });

    </script>

 <script type="text/javascript">

    $('#select_date').hide();
    function checkDateType(){
        if($('#date_type').val() == 1){                      
            $('#select_date').show();
        }else{
            $('#select_date').hide();
        }
    }

function Export() {
    $(".dataTableAjax").table2excel({
        filename: "repurchase_income"
    });
}

 
</script>
@endpush