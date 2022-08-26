@extends('admin.layout.master')
@section('title','Admin :: Conference Rooms')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less"> Conference Rooms</h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
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
                <h5 class="breadcrumbs-title"> Conference Rooms</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a> Conference Rooms</a>
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
                        <th>Location</th>
                        <th>Building</th>
                        <th>Department</th>
                        <th>Device</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $key => $row)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $row->room }}</td>
                        <td>{{ @$row->getLocation->name }}</td>
                        <td>{{ @$row->getBuilding->name }}</td>
                        <td>{{ @$row->getDepartment->name }}</td>
                        <td>{{ @$row->getDevice->name }}</td>
                        <td width="10%">
                            <a href="{{ adminRoute('edit',$row)}}" class="btn btn-primary btn-xs mdi-editor-border-color" style="padding: 0px 6px; background-color: #0007d8;"></a>
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
