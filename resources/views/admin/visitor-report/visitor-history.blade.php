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
       {{Form::open(['route'=>'show.history', 'class'=>'form-inline'])}}
       <div class="row" style="padding: 15px 15px 0px 15px;">
         <div class="col s4">
             <label>Location</label>
             <div class="form-group">
                 <select class="form-control" name="location_id" style="display:none">
                   <option value="">All Location</option>
                   @foreach ($locations as $key => $location)
                   <option value="{{$location->id}}">{{$location->name}}</option>
                   @endforeach
                 </select>
             </div>
         </div>
         <div class="col s4">
             <label>Building</label>
             <div class="form-group">
                 <select class="form-control" name="building_id" style="display:none">
                   <option value="">All Building</option>
                   @foreach ($buildings as $key => $building)
                   <option value="{{$building->id}}">{{$building->name}}</option>
                   @endforeach
                 </select>
             </div>
         </div>
         <div class="col s4">
             <label>Officer</label>
             <div class="form-group">
                 <select class="form-control" name="officer_id" style="display:none">
                   <option value="">All Officer</option>
                   @foreach ($get_officers as $key => $officer)
                   <option value="{{$officer->id}}">{{$officer->name}}</option>
                   @endforeach
                 </select>
             </div>
         </div>

         <div class="col s4">
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
         <div class="col s4">
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
        <div class="row" style="padding: 15px 15px 0px 15px;">
            <div id="select_date">
                <div class="col s5">
                    <label>Date From</label>
                    <div class="form-group">
                        <input type="date" name="date_from" id="date_from" class="form-control">
                        <small class="text-danger date_from"></small>
                    </div>
                </div>
                <div class="col s5">
                    <label>Date To <i>(Optional)</i></label>
                    <div class="form-group">
                        <input type="date" name="date_to" id="date_to" class="form-control"/>
                        <small class="text-danger">{{$errors->first('date_to')}}</small>
                    </div>
                </div>
            </div>
            <div class="col s2">
                <div class="clearfix mt-3">
                <button type="submit" name="search" id="searchBtn" value="Search" class="btn btn-primary pull-right" style="margin-top: 15px;">Search</button>
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
                    <th> Location</th>
                    <th> Building</th>
                    <th> Department</th>
                    <th> Officer Name</th>
                    <th> Status</th>
                    <th> Date</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($record))
                @foreach($record as $key =>$res)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$res['refer_code']}}</td>
                    <td>{{$res['name']}}</td>
                    <td>{{$res['location']['name']}}</td>
                    <td>{{$res['building']['name']}}</td>
                    <td>{{$res['officer_department']['name']}}</td>
                    <td>{{$res['officer_detail']['name']}}</td>
                    @if($res['status']==0)
                        <td style="color:#00bcd4;">Pending</td>
                    @elseif($res['status']==1)
                        <td style="color:#04e60d;">Approve</td>
                        @else
                        <td style="color:#f70303;">Block</td>
                    @endif

                    <td>{{$res['created_at']}}</td>
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
