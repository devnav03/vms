@extends('admin.layout.master')

@section('title','Admin :: Building List')

@section('head')

<div class="page-head">

    <div class="row">

        <div class="col-md-4">

            <h3 class="m-b-less">Building List</h3>

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

                <h5 class="breadcrumbs-title">Building List</h5>

                <ol class="breadcrumb">

                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

                  </li>

                  <li><a>Buildings</a>

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

                        <th>Name</th>

						<th>QR Code</th>
                        
						<th>Location</th>
						<th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($buildings as $buildingKey => $building)

                    <tr>

                        <td>{{@$buildingKey+1}}</td>

                        <td>{{ @$building->name }}</td>
						@php
							$building_id=$building->id;
							$url=url('/visitor/registration/building/'.base64_encode($building_id));
						
						@endphp
						<td class="modal-trigger"  href="#qr_model_{{$building_id}}">{!! QrCode::size(50)->generate($url); !!}</td>
                        <div id="qr_model_{{$building_id}}" class="modal" style="max-height:100% !important;" download> 
                            <div class="modal-content">
                                <a class="modal-close waves-effect waves-green btn-flat">Close</a>
                                <div style="text-align:center;">
                                    {!! QrCode::size(200)->generate($url); !!}
                                </div>
                                <h4 style="text-align:center;font-size:19px;">Building Name: {{ @$building->name }}</h4>
                                <h4 style="text-align:center;font-size:19px;">Location Name: {{ @$building['getLocation']->name }}</h4>
                                <p style="text-align: right; font-size: 12px;">powered by: vztor.in/p>
                                <button id="cmd{{$building_id}}"></button>
                            </div>
                           
                        </div>
					   <td>{{ @$building['getLocation']->name }}</td>
      						@if($building->status==1)
      							<td>Active</td>
      						@else
      							<td>DeActive</td>
      						@endif
                        <td width="10%">

                            <a href="{{ adminRoute('edit',$building)}}" class="btn btn-primary btn-xs mdi-editor-border-color" style="padding: 0px 6px; background-color: #0007d8;"></a>

                            <button onclick="deleteForm('{{ adminRoute('destroy',$building) }}')" class="btn btn-danger btn-xs mdi-content-clear" style="padding: 0px 6px;"></button>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            </div>

        </div>

@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> -->

    <script type="text/javascript">

       $(document).ready(function() {

            $('#data-table-simple_wrapper').DataTable();

    } );

    var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function (element, renderer) {
                return true;
            }
        };

        $('#cmd13').click(function () {
            doc.fromHTML($('#qr_model_13').html(), 15, 15, {
                'width': 170,
                    'elementHandlers': specialElementHandlers
            });
            doc.save('sample-file.pdf');
        });

    </script>

@endpush
