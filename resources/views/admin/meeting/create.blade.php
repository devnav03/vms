@extends('admin.layout.master')
<script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
<style>
    .label-info{background-color: #5bc0de;padding: 2px 2px;border-radius: 2px;font-size: 12px;}.label-info[href]:focus,.label-info[href]:hover{background-color:#31b0d5}.label-warning{background-color:#f0ad4e}.label-warning[href]:focus,.label-warning[href]:hover{background-color:#ec971f}.label-danger{background-color:#d9534f}.label-danger[href]:focus,.label-danger[href]:hover{background-color:#c9302c}.badge{display:inline-block;min-width:10px;padding:3px 7px;font-size:12px;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:middle;background-color:#777;border-radius:10px}.badge:empty{display:none}.btn .badge{position:relative;top:-1px}.btn-group-xs>.btn .badge,.btn-xs .badge{top:0;padding:1px 5px}a.badge:focus,a.badge:hover{color:#fff;text-decoration:none;cursor:pointer}.select-dropdown{display: none;}
</style>
<link rel="stylesheet" href="{{asset('admin-asset/select/dist/bootstrap-tagsinput.css')}}">
    
@section('title','Admin :: Create Meeting ')
@section('head')

<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Create Meeting
            </h3>
        </div>
    </div>
</div>
@endsection
@section('content')
<section class="wrapper main-wrapper">
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title">Create Meeting</h5>
            <ol class="breadcrumb">
              <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
              </li>
              <li><a> Create Meeting</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="row" style="padding: 20px;">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                {{-- <div class="panel-heading">
                    <h1 class="panel-title" style="padding: 10px">Create Meeting</h1>
                </div> --}}
                <div class="panel-body">
                    <div class="row col s12">
                        {!! Form::open(['route'=>'admin.'.request()->segment(2).'.store','class'=>'validate cmxform','files'=>true]) !!}  
                        <div class="form-group col s6">
                            <div class="col-md-3">{!! Form::label('name', 'Add title', ['class'=>'control-label']) !!}</div>
                            <div class="col-md-9">
                                {!! Form::text('meeting_title', '', ['class'=>'form-control','required']) !!}
                                @if($errors->has('meeting_title'))<p class="text-danger">{{ $errors->first('meeting_title') }}</p> @endif
                            </div>                                
                        </div> 
                        <div class="form-group col s6">
                            <div class="col-md-3">
                            {!! Form::label('name', 'Select Room', ['class'=>'control-label']) !!}
                            </div>
                            <div class="col-md-9">
                                <select name="room_id" id="room_id" class="form-control nowarn" required>
                                <option value="">Select Rooms</option>
                                @foreach($menus as $row)
                                <option value="{{@$row->id}}" >{{@$row->room}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('room_id'))<p class="text-danger">{{ $errors->first('room_id') }}</p> @endif
                            </div>                               
                        </div> 
                        <div class="form-group col s6">
                            <div class="col-md-3">
                                {!! Form::label('name', 'From Date', ['class'=>'control-label']) !!}
                            </div>
                            <div class="col-md-9">
                                <input type="datetime-local" id="from_date" class="form-control" name="from_date">
                                @if($errors->has('from_date'))<p class="text-danger">{{ $errors->first('from_date') }}</p> @endif
                            </div>                            
                        </div> 
                        <div class="form-group col s6">
                            <div class="col-md-3">
                                {!! Form::label('name', 'To Date', ['class'=>'control-label']) !!}
                            </div>
                            <div class="col-md-9">
                                <input type="datetime-local" id="to_date" class="form-control" name="to_date">
                                @if($errors->has('to_date'))<p class="text-danger">{{ $errors->first('to_date') }}</p> @endif
                            </div>                            
                        </div>
                        <div class="form-group col s6">
                            <div class="col-md-3">{!! Form::label('name', 'Assigned By', ['class'=>'control-label']) !!}</div>
                            <div class="col-md-9">
                                {!! Form::text('assigned_by', $user_details->name, ['class'=>'form-control','required','readonly']) !!}
                                @if($errors->has('assigned_by'))<p class="text-danger">{{ $errors->first('assigned_by') }}</p> @endif
                            </div>                                
                        </div>  
                        
                        <div class="form-group col s6">
                            <div class="col-md-3">{!! Form::label('name', 'Add Guests', ['class'=>'control-label']) !!}</div>
                            <div class="col-md-9">
                                
                                    <input type="text" id="search_user" name="meeting_members" class="form-control" value="
                                    @foreach($userdetails as $rows)
                                        {{ @$rows->name}} ( {{$rows->email}} ),
                                    @endforeach
                                    "  data-role="tagsinput" style="width:100%"/>
                                
                                @if($errors->has('guests'))<p class="text-danger">{{ $errors->first('guests') }}</p> @endif

                                <ul id="get_search_user"></ul>
                            </div>                                
                        </div>  
                        <div class="form-group col s12">
                            <div class="col-md-3">{!! Form::label('name', 'Add Description', ['class'=>'control-label']) !!}</div>
                            <div class="col-md-9">
                                
                                <textarea type="hiden" name="descriptions"></textarea>
                                
                                @if($errors->has('descriptions'))<p class="text-danger">{{ $errors->first('descriptions') }}</p> @endif
                            </div>                                
                        </div>  
                    </div>
                    <div  class="row col s12" style="margin-top: 10px;">
                        <div class="form-group "style="float: right;" >
                            <button class="btn btn-primary pull-right">Create</button>
                            <a style="background: #25cfea;" class="btn btn-default pull-right" id="back">Back</a>
                        </div> 
                    </div>
                    <div  class="row col s12" style="margin-top: 10px;">
                        <input type="text" id="input_data"/>
                        
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $("#search_user").keyup(function(){
        if ($("#search_user").val().length>1) {
            $.ajax({
                url:"{{route('admin.ajax-get-users')}}",
                type: "GET",
                data: {
                    keyword: $("#search_user").val(),
                    _token: '{{csrf_token()}}'
                },
                async: false,
                success: function(result){
                    $("#get_search_user").html(result);
                }
            });
        }
    });
});
function add_users(id)
{
    $.ajax({
        
        type: "GET",
        data: {
            keyword: $("#search_user").val(),
            _token: '{{csrf_token()}}'
        },
        async: false,
        success: function(result){
            $("#get_search_user").html(result);
        }
    });
}
</script>
<script>
    CKEDITOR.replace('descriptions');
</script>

<script src="{{asset('admin-asset/select/dist/bootstrap-tagsinput.min.js')}}"></script>

    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script> -->
    
    <!-- <script src="{{asset('admin-asset/select/assets/app.js')}}"></script> -->
    
    <!-- <script>
        var cities = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: 'http://localhost/vms/public/admin-asset/select/assets/cities.json'
        });
        cities.initialize();

        var elt = $('#input_data');
        elt.tagsinput({
        itemValue: 'value',
        itemText: 'text',
        typeaheadjs: {
            name: 'cities',
            displayKey: 'text',
            source: cities.ttAdapter()
        }
        });
        elt.tagsinput('add', { "value": 1 , "text": "Amsterdam"   , "continent": "Europe"    });
        elt.tagsinput('add', { "value": 4 , "text": "Washington"  , "continent": "America"   });
        elt.tagsinput('add', { "value": 7 , "text": "Sydney"      , "continent": "Australia" });
        elt.tagsinput('add', { "value": 10, "text": "Beijing"     , "continent": "Asia"      });
        elt.tagsinput('add', { "value": 13, "text": "Cairo"       , "continent": "Africa"    });
    </script> -->
@endpush

