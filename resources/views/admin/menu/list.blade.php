@extends('admin.layout.master')
@section('title','Admin :: Menus List')
@push('links')
<!--nestable-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/js/nestable/jquery.nestable.css') }}" />
@endpush
@section('content')
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Menus List</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a>Menus</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
         <!--start container-->
        <div class="container">
             <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add New</a>
            <div class="row">
                <div class="col s12 m12 l12">
                  <div class="dd" id="nestable_list_2">
            <ol class="dd-list">
                @foreach($menus as $menu)
                <li class="dd-item dd3-item" data-id="{{ $menu->id }}">
                    <div class="dd-handle dd3-handle"></div>
                    <div class="dd3-content" style="padding-top: 4px;">{{ $menu->title }}
                        {{-- @can('edit') --}}
                        <span style="float: right;">
                        <a href="{{ adminRoute('edit',$menu->id)}}" class="btn btn-link pull-right mdi-editor-border-color" style="padding: 0px 6px; background-color: #0007d8;"></a>
                        {{-- @endcan --}}
                        {{-- @can('delete') --}}
                        @if($menu->childs->isEmpty())
                        <button onclick="deleteData('{{ adminRoute('destroy',$menu->id)}}',function(){window.location.reload(); })" class="btn btn btn-link pull-right mdi-content-clear" style="padding: 0px 6px;"></button>
                        {{-- @endcan --}}
                        @endif
                        </span>
                    </div>
                    @if($menu->childs->count())
                    <ol class="dd-list" id="{{ $menu->id }}">
                        @foreach($menu->childs as $child)
                        <li class="dd-item dd3-item" data-id="{{ $child->id }}">
                            <div class="dd-handle dd3-handle"></div>
                            <div class="dd3-content" style="padding-top: 4px;">{{ $child->title }}
                                {{-- @can('edit') --}}
                                <span style="float: right;">
                                <a href="{{ adminRoute('edit',$child->id)}}" class="btn btn-link pull-right mdi-editor-border-color" style="padding: 0px 6px; background-color: #0007d8;"></a>
                                {{-- @endcan --}}
                                {{-- @can('delete') --}}
                                <button onclick="deleteData('{{ adminRoute('destroy',$child->id)}}',function(){window.location.reload(); })" class="btn btn-link pull-right mdi-content-clear" style="padding: 0px 6px; margin-top: -3px;"></button>
                                {{-- @endcan --}}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                    @endif
                </li>
                @endforeach
            </ol>
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
@section('head')
<div class="page-head">
    <h3 class="m-b-less">
    Menu List
    </h3>
    {{-- @can('add') --}}
    <div class="state-information">
        <a class="btn btn-primary btn-sm" href="{{ adminRoute('create') }}">Add Menu</a>
    </div>
    {{-- @endcan --}}
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="dd" id="nestable_list_2">
            <ol class="dd-list">
                @foreach($menus as $menu)
                <li class="dd-item dd3-item" data-id="{{ $menu->id }}">
                    <div class="dd-handle dd3-handle"></div>
                    <div class="dd3-content">{{ $menu->title }}
                        {{-- @can('edit') --}}
                        <a href="{{ adminRoute('edit',$menu->id)}}" class="btn btn-link pull-right mdi-editor-border-color" style="padding: 0px 6px; margin-top: -2px; background-color: #0007d8;"></a>
                        {{-- @endcan --}}
                        {{-- @can('delete') --}}
                        @if($menu->childs->isEmpty())
                        <span style="margin:0px 10px 0px 10px" class="pull-right">/</span>
                        <button onclick="deleteData('{{ adminRoute('destroy',$menu->id)}}',function(){window.location.reload(); })" class="btn btn btn-link pull-right mdi-content-clear" style="padding: 0px 6px; margin-top: -2px;"></button>
                        {{-- @endcan --}}
                        @endif
                    </div>
                    @if($menu->childs->count())
                    <ol class="dd-list" id="{{ $menu->id }}">
                        @foreach($menu->childs as $child)
                        <li class="dd-item dd3-item" data-id="{{ $child->id }}">
                            <div class="dd-handle dd3-handle"></div>
                            <div class="dd3-content">{{ $child->title }}
                                {{-- @can('edit') --}}
                                <a href="{{ adminRoute('edit',$child->id)}}" class="btn btn-link pull-right mdi-editor-border-color" style="padding: 0px 6px; margin-top: -2px; background-color: #0007d8;"></a>
                                {{-- @endcan --}}
                                {{-- @can('delete') --}}
                                <button onclick="deleteData('{{ adminRoute('destroy',$child->id)}}',function(){window.location.reload(); })" class="btn btn-link pull-right mdi-content-clear" style="padding: 0px 6px; margin-top: -2px;"></button>
                                {{-- @endcan --}}
                            </div>
                        </li>
                        @endforeach
                    </ol>
                    @endif
                </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
<!-- page end-->
@endsection
@push('scripts')
<!--nestable -->
<script src="{{ asset('admin-asset/js/nestable/jquery.nestable.js') }}"></script>
<script>
var updateOutput = function(e) {
    var list = e.length ? e : $(e.target),
        output = list.data('output');
    output.val(JSON.stringify(list.nestable('serialize')));
};
$('#nestable_list_2').nestable({
    group: 1,
    maxDepth: 2
}).on('change', function(e) {
    var list = e.length ? e : $(e.target),
        output = list.data('output');
    var data = list.nestable('serialize');
    {{-- @can('edit') --}}
    $.ajax({
        url: '{{ adminRoute('update','') }}/1',
        data: {
            'data': list.nestable('serialize'),
            '_method': 'put',
            '_token': '{{ csrf_token() }}',
            '_list': 'nestable'
        },
        method: 'post',
        success: function(response) {
            toastr.success(response.message);
            setTimeout(function() {
                window.location.reload();
            }, 500);
        },
        error: function(response) {
            toastr.error(response.message);
        }
    });
    {{-- @endcan --}}
});
</script>
@endpush