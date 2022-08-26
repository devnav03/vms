@extends('admin.layout.master')

@section('title','Admin :: Category List')

@section('head')

<div class="page-head">

    <div class="row">

        <div class="col-md-6">

            <h3 class="m-b-less">

                Category List

            </h3>

        </div>

        <div class="col-md-6">

            <a href="{{ adminRoute('create')}}" class="btn btn-success btn-sm pull-right">Add Caregory</a>

        </div>

    </div>

</div>

@endsection

@section('content')

    <div class="clearfix"></div>

    <div class="col-md-12 ">

    <div class="table-responsive" style="padding: 20px 0px 40px">

        <table class="display dataTableAjax user-list-table " width="100%">
            <thead>
                <tr>
                    <th>Sn.</th>

                     <th> Category Name  </th>
                     <th>Status</th>
                     <th>sort Order</th>
                    <th>Image</th>
                    <th> Category Description </th>
                    <th> Create Date  </th>
                   <th>Action</th>
                </tr>

            </thead>

           

        </table>

        </div>

    </div>

@endsection

@push('scripts')





   <script type="text/javascript">

        $(document).ready(function() {    

             var table2 = $('.dataTableAjax').DataTable({

                "processing": true,

                "serverSide": true,

                'ajax' : {

                    'url':'{{ route('admin.'.request()->segment(2).'.index') }}',

                    "method": "POST" ,

                     'pages':1,

                     data:{

                      '_method':'patch',

                      '_token':'{{ csrf_token() }}'

                    },

                    'data': function (d) { 



                        // d.dateFrom = $('#dateFrom').val(); 

                        // d.dateTo = $('#dateTo').val(); 

                        // d.name = $('#name').val(); 

                        // d.status = $('#status').val(); 

                        d._token = '{{ csrf_token() }}';

                        d._method = 'PATCH';

                    

                    }                   

                },

                 fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

                    if ( aData[8] == 0 )

                    {

                        $('td', nRow).css('background-color', '#f3cfbd');

                    }

                   

                },

                "columns": [

                   
                    { "data": "sn" },


                    { "data": "name" },
                    { "data": "status" },
                    { "data": "sort_order" },

                    { "data": "image",  

                        render: function ( data, type, row ) {

                                return '<img width="60px" height="40px" src="'+row.image+'"/>';

                        },

                    },
                 
                 
                    { "data": "description" },
                    { "data": "created_at" },
                    { "data": "edit",  

                        render: function ( data, type, row ) {

                            if (type === 'display' ) {

                                var btn='';

                                btn+='<a class="btn btn-warning btn-xs" href="{{ request()->url() }}/'+row.id+'/edit"><i class="fa fa-pencil"></i></a> ';



                                 // btn+='<a class="btn btn-primary btn-xs" href="{{ adminRoute('show','') }}/'+row.id+'"><i class="fa fa-eye"></i></a> ';



                                 btn+='<button type="button" onclick="deleteData(this.id)" id="{{ route('admin.'.request()->segment(2).'.destroy','') }}/'+row.id+'" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';



                                return btn;

                            }

                            return data;

                        },

                    }    
                                  

                ]

            });

            $('#searchBtn').click(function(){

                table2.draw();

            });



        });



    </script>

@endpush