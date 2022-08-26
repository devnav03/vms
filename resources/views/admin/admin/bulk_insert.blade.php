@extends('admin.layout.master')
@section('title','Admin :: Bulk Insert')
@section('content')
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Bulk Insert</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Bulk Insert</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
    </section>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <div class="container" style="padding: 18px;">
      <a href="{{ adminRoute('sampleDownload')}}"  class="btn btn-primary" style="float: right; margin: 10px 16px 0px 0px">Download Sample CSV</a>
    </div>
    <div class="container" style="padding: 18px;">
      <form class="" action="{{ adminRoute('bulk_store')}}" enctype="multipart/form-data" method="post">
        @csrf
        <input type="file" name="employee_sample" value="upload csv">
        <input type="submit" name="" class="form-control" value="upload">
      </form>

    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

@endpush
@push('scripts')

@endpush
