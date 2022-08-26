@extends('admin.layout.master')
@section('title','Admin :: Manage Meetings')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less"> Manage Meetings</h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
    </div>
</div>
@endsection
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Manage Meetings</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a> Manage Meetings</a>
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
            <table class="display dataTableAjax user-list-table" id="data-table-simple_wrapper" width="100%">
                <thead>
                    <tr>
                        <th>S.no.</th>
                        <th>Room</th>
                        <th>Meeting Title</th>
                        <th>Assigned By</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $key => $row)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ @$row->confrence->room }}</td>
                        <td>{{ @$row->meeting_title }}</td>
                        <td>{{ @$row->assigned_by }}</td>
                        <td>{{ @$row->from_date }}</td>
                        <td>{{ @$row->to_date }}</td>
                        <td width="10%">
                            <a href="{{ url('admin-panel/get-attendance-histories',$row->confrence->id)}}" class="btn btn-success btn-xs" style="padding: 0px 6px; background-color: #f9b452;"><i class="fa fa-eye" style="font-size:12px"></i></a>
                            <button onclick="deleteForm('{{ adminRoute('destroy',$row) }}')" class="btn btn-danger btn-xs mdi-content-clear" style="padding: 0px 6px;"></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
@endsection
@push('scripts')
    <script type="text/javascript">
       $(document).ready(function() {
            $('#data-table-simple_wrapper').DataTable();
    } );
    </script>
@endpush
