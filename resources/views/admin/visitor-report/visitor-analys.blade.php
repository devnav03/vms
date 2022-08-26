@extends('admin.layout.master')
@section('title','Admin :: Visitor History')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-3">
            <h3 class="m-b-less">
            Visitor History
            </h3>
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
                <h5 class="breadcrumbs-title"> Visitor History</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a href="#">Visitor History</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
    </section>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <div class="col s12">
      <div class="transaction_summary">
         {{Form::open(['url'=>'admin-panel/visitor-report/serach?report='.$report, 'class'=>'form-inline'])}}
         <div class="row" style="padding: 15px 15px 0px 15px;">
           <div class="col s5">
               <label>Department</label>
               <div class="form-group">
                   <select class="form-control" name="department_id" style="display:none">
                     <option value="">All Department</option>
                     @foreach ($department as $key => $value)
                     <option value="{{$value['id']}}">{{$value['name']}}</option>
                     @endforeach
                   </select>
               </div>
           </div>
           <div class="col s5">
               <label>Status</label>
               <div class="form-group">
                   <select class="form-control" name="status" style="display:none">
                     <option value="">All Status</option>
                     <option value="0">Pending</option>
                     <option value="2">Block</option>
                     <option value="1">Approve</option>

                   </select>
               </div>
           </div>
        </div>
        <div class="row">
            <div class="col s6">
                <div class="clearfix mt-3">
                <button type="submit" name="search" id="searchBtn" value="Search" class="btn btn-primary pull-right" style="margin-top: 15px;">Search</button>
                <a href="javascript:history.back()"><button type="button"  class="btn btn-primary pull-right" style="margin-top: 15px;">Go Back</button></a>
                </div>
            </div>
          </div>

        </div>
         {{Form::close()}}
    </div>
</div>
    {{-- <div class="clearfix"></div> --}}
    <div class="col s12" style="padding: 0px 15px 0px 15px">
    <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>SR.</th>
                    <th> Visitor Id</th>
                    <th> Visitor Name</th>
                    <th> Officer Name</th>
                    <th> Department</th>
                    <th> In Time</th>
                    <th> Out Time</th>
                    <th> Current Status</th>
                    <th> Status</th>
                    <th> Date</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($record))
                @foreach($record as $key =>$res)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{@$res['refer_code']}}</td>
                      <td>{{@$res['name']}}</td>
                    <td>{{@$res['OfficerDetail']['name']}}</td>
                    <td>{{@$res['OfficerDepartment']['name']}}</td>
                    <td>{{@@$res['in_time']}}</td>
                    <td>{{@@$res['out_time']}}</td>
                    @if(@@$res['out_status']=="Yes" && @$res['in_status']=="Yes")
                      <td>OUT</td>
                    @elseif(@@$res['in_status']=="Yes" && @$res['out_status']=="No")
                      <td>IN</td>
                      @else
                        <td>Not Come</td>
                    @endif
                    @if(@$res['status']==0)
                        <td style="color:#00bcd4;">Pending</td>
                    @elseif(@$res['status']==1)
                        <td style="color:#04e60d;">Approve</td>
                        @else
                        <td style="color:#f70303;">Block</td>
                    @endif

                    <td>{{@$res['created_at']}}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
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
    $('#example').DataTable();
} );


</script>

@endpush
