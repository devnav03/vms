@extends('admin.layout.master')
@section('title','Edit Bread')
@section('head')
<div class="page-head">
    <h3 class="m-b-less">
        Edit Bread
    </h3>
</div>
@endsection
@section('content')
<section class="wrapper main-wrapper">
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title">Edit Bread</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a>Bread</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update',$menu->table_name],'method'=>'put','id'=>'breadForm']) !!}
    <div class="form-group" style="padding: 20px;">
        <label for="">Name</label>
        {!! Form::text('name', ($menu->title)?$menu->title:title_case(str_replace('_', ' ', $menu->table_name)) , ['class'=>'form-control']) !!}
    </div>
    <div class="form-group" style="padding: 20px;">
        <label for="">Icon</label>
        {!! Form::text('icon', $menu->icon , ['class'=>'form-control']) !!}
    </div>
    <div class="form-group" style="padding: 20px;">
        <label for="">Controller Name</label>
        {!! Form::text('controller', ($menu->controller)?$menu->controller:str_replace('_','',title_case(str_singular($menu->table_name))).'Controller' , ['class'=>'form-control']) !!}
    </div>
    <div class="form-group pull-right" style="padding-left: 20px;">
        <button type="submit" onclick="submitForm()" class="btn btn-primary" >Edit</button>
        <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
    </div>
    <br>
    <br>
    <label for="" style="padding: 20px; font-size: 25px; color: black;">Permissions</label>
    <br><br>
    <div class="form-group" id="permissions" style="padding: 20px;">
        @foreach(['browse','read','add','edit','delete'] as $per)
        <span style="border:1px solid #aaa; padding:10px;margin-right: 30px; background: #2cc5c5;">
            <label style="color: white; font-size: 19px;">{{ ucwords($per) }}  </label>
             &nbsp;&nbsp;{!! Form::checkbox('permissions[]', $per.'_'.$menu->table_name, in_array( $per.'_'.$menu->table_name,$perm), ['class'=>'js-switch-sm']) !!} 
        </span>
        @endforeach
        <button style="margin-left:50px;" class="btn btn-sm pull-right btn-link"  type="button" data-toggle="modal" data-target="#addPermission">Add More</button>
    </div>
   {{--  <div class="form-group">
        {!! Form::label('show_as_menu', 'Show As Menu', []) !!}
        {!! Form::checkbox('show_as_menu', 1, ($menu->status)?$menu->status:0, []) !!}
    </div> --}}
    {!! Form::close() !!}
</section>
<!-- Modal -->
<div id="addPermission" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <label>Permission Name</label>
                <input type="text" name="per" class="form-control" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="addPermission(this)" class="btn btn-default" >Add</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    function addPermission(el){
        var val = $(el).closest('.modal-content').find('input').val();
        var html = ' <span style="border:1px solid #aaa; padding:10px;margin-right: 30px;text-transform:capitalize;">'+
            '<label>'+val+'  </label>'+
             '&nbsp;&nbsp;<input type="checkbox" style="margin-top: 10px" name="permissions[]"  value="'+slug(val)+'_{{ $menu->table_name }}" class="js-switch-sm"> '+
        '</span>';
        $('#permissions').append(html);
        $('#addPermission').modal('hide');
        $(el).closest('.modal-content').find('input').val('');
        switchBtn(); 
    }
    function slug(string){
       return  string.toLowerCase().split(' ').filter(function(n){ return n != '' }).join('_');
    }
    function switchBtn(){
            var elems2 = $('input[data-switchery!="true"].js-switch-sm');
            for (var i = 0; i < elems2.length; i++) {
                new Switchery(elems2[i],{ size: 'small' });
            }
    }
    function submitForm(){
        $('#breadForm').submit();
    }
</script>
@endpush
@push('scripts')
 <script type="text/javascript">
 $("#back").click(function(){
    window.location.href="{{ route('admin.'.request()->segment(2).'.index') }}"
  });
</script>
@endpush