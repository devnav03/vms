@extends('admin.layout.master')
@section('title','Admin :: Get Attendance Histories')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-3">
            <h3 class="m-b-less">
            Get Attendance Histories
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
              <div class="col s6 m6 l6">
                <h5 class="breadcrumbs-title"> Get Attendance Histories</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a href="#">Get Attendance Histories</a>
                  </li>
                </ol>
              </div>
              <div class="col s6 m6 l6">
                  <input type="button" class="btn btn-primary" onclick="DataSyncFromLive();" value="Live Sync" style="float:right">
              </div>
            </div>
          </div>
        </div>
        
    </section>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <div class="col s12">
    <div class="transaction_summary">
    {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','class'=>'validate cmxform','files'=>true]) !!}  
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
                    <th>Sr.No.</th>
                    <th> Visitor Id</th>
                    <th> Visitor Name</th>
                    <th> Office Name</th>
                    <th> Department</th>
                    <th> Date</th>
                    <th> In Time</th>
                    <th> In Device</th>
                    <th> Out Time</th>
                    <th> Out Device</th>
                    {{--<th> Attendance</th>--}}
                </tr>
            </thead>
            <tbody>
                @if(!empty($record))
                @foreach($record as $key =>$res)
                <tr>
                    <td>{{@$key+1}}</td>
                    <td>{{@$res->employee_id}}</td>
                    <td>{{@$res->employee_name}}</td>
                    <td>{{@$res->office}}</td>
                    <td>{{@$res->department}}</td>
                    <td>{{@$res->date}}</td>
                    <td>{{@$res->in_time}}</td>
                    <td>{{@$res->in_device}}</td>
                    <td>{{@$res->out_time}}</td>
                    <td>{{@$res->out_device}}</td>
                    {{--<td>{{@$res->attendance}}</td>--}}
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
    function  DataSyncFromLive(){
        let timerInterval
            Swal.fire({
            title: 'Please Wait!',
            html: 'Please wait your request is being processed <b></b> milliseconds.',
            timer: 20000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
                }, 10000)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    // console.log('I was closed by the timer')
                }
            });
      
        $.ajax({
            type:'get',
            url:'{{url("admin-panel/visitor_report_sync")}}',
            dataType:'json',
            success:function(getdata) {
                // Command: toastr[getdata.class](getdata.message);
                    Swal.fire({
                        // position: 'top-end',
                        icon: 'success',
                        title: 'Your Data has been synced successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }


    </script>

    @endpush
