@extends('admin.layout.master')
@section('title','Admin :: Panic Alert List')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less"> Panic Alert List</h3>
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
                <h5 class="breadcrumbs-title"> Panic Alert List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a> Panic Alert</a>
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
                        <th>S.no.</th>
                        <th>Staff Id</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Mobile</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menuKey => $menuValue)
                    <tr>
                        <td>{{$menuKey+1}}</td>
                        <td>ST00{{@$menuValue->officer_id}}</td>
                        <td>{{ @$menuValue->name}}</td>
                        <td>{{ @$menuValue->OfficerDetail->getDepart->name }}</td>
                        <td>{{ @$menuValue->OfficerDetail->mobile }}</td>
                        <td>{{ @$menuValue->OfficerDetail->email }}</td>
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
    } );
    </script>
@endpush
