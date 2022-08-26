@extends('admin.layout.master')
@section('title','Admin :: Repeat Visitor List')
@section('head')

<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Repeat Visitor List
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
                <h5 class="breadcrumbs-title">Repeat Visitor List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Repeat Visitor</a>
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
                    <!--<th style="padding-right: 0px;">S.no.</th>-->
                    <th style="padding-right: 0px;">#Visitor Id</th>
                    <th style="padding-right: 0px;">Name</th>
                    <!-- <th style="padding-right: 0px;">Image</th> -->
                    <th style="padding-right: 0px;">Email</th>
                    <th style="padding-right: 0px;">Mobile No</th>
                    <th style="padding-right: 0px;">Last Visit</th>
                    <th style="padding-right: 0px;">Total Visit</th>
                    <th style="padding-right: 0px; width: 251px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($all_reports as $key => $report)
                @php
                $total_visit=count($all_reports[$key]);
                @endphp
                    <tr>
                        <td>{{$all_reports[$key][$total_visit-1]['refer_code']}}</td>
                        <td>{{$all_reports[$key][$total_visit-1]['name']}}</td>
                        <td>{{$all_reports[$key][$total_visit-1]['email']}}</td>
                        <td>{{$all_reports[$key][$total_visit-1]['mobile']}}</td>
                        <td>{{$all_reports[$key][$total_visit-1]['visite_time']}}</td>
                        <td>{{$total_visit}}</td>
                        <td><a href="{{route('admin.repeat.visitor.details',$key)}}">Check Details</a></td>
                        
                    </tr>
                @endforeach
                
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
        $('#data-table-simple_wrapper').DataTable();
    });

</script>


@endpush
