@extends('admin.layout.master')
@section('title','Edit Employee')
<style>
  #loader-p{

  z-index:999999;
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:url(https://sspl20.com/vivek/vms/public/loading-image.gif) 50% 50% no-repeat;
}
</style>

@section('content')
    <section id="content">
      <div id="loader-p">
      </div>
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Edit Employee</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Employee</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
         <!--start container-->
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="card-panel">
                      <h4 class="header2">Edit Employee</h4>
                      <div class="row">
                         {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update',$admin->id],'method'=>'put','files'=>true]) !!}
                          <div class="row">
                            <div class="input-field col s6">
                              <label for="status">Employee Name</label>
                                {{ Form::text('name',$admin->name,['class'=>'form-control']) }}
                                <p class="text-danger">{{$errors->first('name')}}</p>
                            </div>
                            <div class="input-field col s6">
                              <select name="location_id" id="location_id" class="form-control nowarn" required>
                                <option value="{{old('location_id')}}">Select Location name</option>
                                @foreach($locations as $location)
                                <option value="{{$location->id}}" {{$location->id == $admin->location_id ? 'selected':''}}>{{$location->name}}</option>
                                @endforeach
                              </select>
                              @if($errors->has('location_id'))<p class="text-danger">{{ $errors->first('location_id') }}</p> @endif
                              </div>
                            </div>
                            <div class="row">
                              <div id="building_iddiv" class="input-field col s6">
                              	</div>

                            <div class="input-field col s6" id="department_iddiv">
                            </div>
                          </div>
                          <div class="row">
                            <div id="device_iddiv" class="input-field col s6">
                              </div>
                            <div class="input-field col s6">
                              {{-- {!! Form::label('role','Role', []) !!} --}}
                              {!! Form::select('role',$roles,  @$admin->role_id?$admin->role_id:'8', ['class'=>'form-control','placeholder'=>'Select role']) !!}
                              <p class="text-danger">{{$errors->first('role')}}</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="input-field col s6">
                                <label for="email">Admin Email</label>
                                {{ Form::text('email',$admin->email,['class'=>'form-control']) }}
                                <p class="text-danger">{{$errors->first('email')}}</p>
                            </div>
                            <div class="input-field col s6">
                                <label for="email">Mobile</label>
                                {{ Form::text('mobile',@$admin->mobile,['class'=>'form-control']) }}
                                <p class="text-danger">{{$errors->first('mobile')}}</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="input-field col s6">
                                <label for="name">Admin Password</label>
                                {{ Form::password('password',['class'=>'form-control']) }}
                            </div>
                            <div class="input-field col s6">
                              <label for="password" style='top: -1.1rem'>Ip allow</label>
                              {{-- {!! Form::label('allowip',null, ['class'=>'allowip']) !!} --}}
                              {!! Form::select('allowip',['0'=>'selected Ip','1'=>'all Ip'],@$admin->allowed_ip?$admin->allowed_ip:'1', ['class'=>'form-control']) !!}
                              <p class="text-danger">{{$errors->first('password')}}</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="input-field col s6">
                              <label for="ip address">Ip Address</label>
                              {!! Form::label('Ip address',null, []) !!}
                              {{ Form::text('ip',$admin->ip,['class'=>'form-control']) }}
                              <p class="text-danger">{{$errors->first('ip')}}</p>
                            </div>
                            <div class="input-field col s6">
                                <label for="status" style='top: -1.1rem'>Status</label>
                                {{ Form::select('status_id',['1'=>'Active','2'=>'Deactive'],$admin->status_id,['class'=>'form-control']) }}
                                 {{-- <p class="text-danger">{{$errors->first('status')}}</p> --}}
                            </div>
                          </div>
                          <div class="row">
                              <div class="input-field col s12">
                                <button type="submit" class="btn waves-effect waves-light">Update</button>
                                <a class="btn cyan waves-effect"  style="!important;" id="back">Back</a>
                              </div>
                          </div>
                        {!! Form::close() !!}
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <!--end container-->
    </section>
        <!-- START FOOTER -->
    <footer class="page-footer">
        <div class="footer-copyright">
            <div class="container"> Copyright © 2021.  All rights reserved.
            </div>
        </div>
    </footer>
    <!-- END FOOTER -->
@endsection
@push('scripts')
<script type="text/javascript">
$("#back").click(function(){
    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
  });  $('#location_id').on('change', function() {
    var location_id = this.value;	  $("#building_id").html('');
    $("#department").html('');
    getBuilding(location_id);	});
    function getBuilding(location_id){
      $("#loader-p").show();
      $.ajax({
         url:"{{url('/web-get-building')}}",
         type: "POST",
         data: {
           location_id: location_id,
           _token: '{{csrf_token()}}'
         },
         dataType : 'json',
         success: function(result){
          $("#loader-p").hide();
           	var old_building='<?php echo $admin->building_id;?>';
            $('#building_iddiv').html('<select name="building_id" id="building_id" class="form-control nowarn"  style="display:block;">'+
            '<option value="" >select Building</option>'+
            '</select>');
            $.each(result,function(key,value){
              if(old_building==value.id){
                $("#building_id").append('<option value="'+value.id+'" selected>'+value.name+'</option>');
              }else{
                $("#building_id").append('<option value="'+value.id+'" >'+value.name+'</option>');
              }
              if(key==0){
                getDepartment(value.id);
              }
            });
          }
        });
      }
      function getDepartment(building_id){
        $("#loader-p").show();
        $.ajax({
          url:"{{url('/web-get-department')}}",
          type: "POST",
          data: {
            building_id: building_id,
            _token: '{{csrf_token()}}'
          },
          dataType : 'json',
          success: function(result){
            $("#loader-p").hide();
            var old_department='<?php echo $admin->department_id;?>';
            $('#department_iddiv').html('<select name="department" id="department" onchange="getDevice();" class="form-control nowarn"  style="display:block;">'+
            '<option value="" >select Department</option>'+
            '</select>');
            $.each(result,function(key,value){
              if(old_department==value.id){
                $("#department").append('<option value="'+value.id+'" selected>'+value.name+'</option>');
              }else{
                $("#department").append('<option value="'+value.id+'" >'+value.name+'</option>');
              }
            });
            getDevice();
          }
        });
      }	$('document').ready(function(){
        var old_location='<?php echo $admin->location_id;?>';
        getBuilding(old_location);
        });
      function getDevice(){
        $("#loader-p").show();
        $('#device_id').val();
        var department_id=$('#department').val();
          $.ajax({
            url:"{{url('/web-get-device')}}",
            type: "POST",
            data: {
            department_id: department_id,
            _token: '{{csrf_token()}}'
            },
            dataType : 'json',
            success: function(result){
              $("#loader-p").hide();
                var old_device_id='<?php echo $admin->device_id;?>';
              $('#device_iddiv').html('<select name="device_id" id="device_id"  class="form-control nowarn"  style="display:block;">'+
                '<option value="" >Select Device</option>'+
                '</select>');
              $.each(result,function(key,value){
                
                if(old_device_id==value.device_id){
                    $("#device_id").append('<option value="'+value.device.id+'"selected >'+value.device.name+'</option>');
                }else{
                    $("#device_id").append('<option value="'+value.device.id+'" >'+value.device.name+'</option>');
                }

              });
            }
          });
      }
</script>
@endpush
