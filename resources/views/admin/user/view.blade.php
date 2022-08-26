
@extends('admin.layout.master')
@section('title','Dashboard')

@section('content')
<section class="wrapper main-wrapper" style=''>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <div class="page-title">
            <div class="pull-left">
                <h1 class="title">View User</h1> </div>
            <div class="pull-right hidden-xs">
                <ol class="breadcrumb">
                    <li>
                         <a href="{{-- {{ route('admin.user.create') }} --}}" class="btn btn-success btn-xs">Add User</a>
                    </li>
                   
                    <li class="active">
                        <strong>View User</strong>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table">
                <tr><td>Name</td> <td>: {{ ($user->name?$user->name:'Empty') }}</td></tr>
                <tr><td>Mobile</td><td>:{{ $user->mobile }}</td></tr>
                <tr><td>Email</td><td>: {{ ($user->email?$user->email:'Empty') }}</td></tr>
                {{-- <tr><td>Password</td><td>:{{ $user->password }}</td></tr> --}}

                <tr><td>Country</td><td>: {{ @$user->country->name }}</td></tr>
                 <tr><td>State</td><td>: {{ (@$user->state->name?$user->state->name:'Empty') }}</td></tr>
                  <tr><td>City</td><td>: {{ (@$user->city->name?$user->city->name:'Empty')}}</td></tr>
            </table>
        </div>
    </div>
    @if ($address->count())
        {{-- expr --}}
    
    <hr>
    <div class="row">
        <h3 class="title" style='text-align:center'>Billing Address</h3>
        <div class="col-md-10 col-md-offset-1">
            <table class="table">
                
                @foreach ($address as $row)
                    <tr><td>Billing Address</td> <td>: {{ ($row->address?$row->address:'Empty') }}</td></tr>
                @endforeach
               
              
               
            </table>
        </div>
    </div>
    @endif
    {{-- @if ($order)
    <hr>

     <h3 style='text-align:center'>Order item Detail</h3>

        <div class="col-md-12">
            <table class="table">
                <thead>
                <tr>
                <th>S.I</th>
                <th>Order Id</th>
                <th>Mode of Payment</th>
                <th>Amount</th>
                <th>Delivery At</th>
                <th>Order Status</th>
              
                <th>View Order</th>    


                </tr>
            </thead>
            <tbody>
                 @php
                      $i=1;
                 @endphp
                 

                    @foreach($order as $data)
                 <tr>
                <td>{{ $i }} </td>
                <td>{{ $data->id }} </td>
                <td>{{ @$data->pay_mode }} </td>
               
                <td>{{ $data->total }}</td>
                <td>{{ @$data->delivery_at }} </td>
                
                <td>@php echo (@$data->payStatus->status==1?"<span style=color:green;>Success </span>":"<span style=color:red;>Failed </span>") @endphp</td>
                <td><a class="btn btn-primary btn-xs modalpop" data-toggle="modal" data-id="{{ $data->id }}" href='#modal-id'>View Order</a></td>
                </tr> 
                @php
                   $i++;
                @endphp 
                   @endforeach 
           
         <tr>
    </tr>
            </tbody>
             
            </table>
        </div>
        <div class="modal fade" id="modal-id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">View Order Detail</h4>
                </div>
                <div class="modal-body">
                    <div role="tabpanel">

                        <ul class="nav nav-tabs" role="tablist">
                           
                        </ul>
                    
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="import">
                            <table class="table table-bordered table-hover ">
                               <thead>
                                   <tr>
                                       <th>OrderId</th>
                                       <th>Product name</th>
                                       <th>Quantity</th>
                                       <th>Price</th>
                                       <th>Subtotal</th>
                                   </tr>
                               </thead>
                               <tbody class="orderitemdetail">
                                
                               </tbody>
                           </table>
                      
                         
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
        @endif --}}

    <div class="row">
        <div class="col-md-12">
            {{-- <a href="{{ route('admin.product.image.add') }}">Add</a> --}}
           {{--  {!! Form::open(['route'=>['admin.user.image.add',$product->id],'files'=>true,'method'=>'put']) !!}
                {!! Form::file('image', ['class'=>'form-controll']) !!}
                <button class="btn btn-primary">Upload</button>
            {!! Form::close() !!}
        </div>
        <div class="col-lg-8 col-lg-offset-1">
           @foreach ($productimages as $image)
               <img src="{{ $image->path }}" width="150px" height="150px" class="img-thumbnail rounded">
           @endforeach
        </div> --}}
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
     $(document).on("click", ".modalpop", function () {
      var OrderId = $(this).data('id');
       $.ajax({
        url:'{{ route('admin.'.request()->segment(2).'.show','') }}/'+OrderId,
        method:'get',
        data:{'_token':'{{ csrf_token() }}','order_status':'true','order_id':OrderId },
        dataType:'json',
        success:function(response){
            $('.orderitem').text(response.message);

              $.each(response, function(key, orderItem) {
                /// do stuff
                 $('.orderitemdetail').append('<tr><td>'+ orderItem.order_id +'</td><td>'+ orderItem.product_name +'</td><td>'+ orderItem.qty +'</td><td>'+'Rs.' + orderItem.product_price +'</td><td>'+ orderItem.sub_total +'</td></tr></tr>');
                // console.log(orderItem.id);
              });
                        // console.log(response);
              $('#modal-id').modal('show');
           // Command: toastr[response.class](response.message);
           
        }
      });
    //  $(".modal-body #bookId").val( OrderId );
  
    });
</script>
  
@endpush
