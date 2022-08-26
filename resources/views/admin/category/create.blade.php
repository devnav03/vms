@extends('admin.layout.master')
@section('title','Create Category')
@section('head')
<div class="page-head">
    <h3 class="m-b-less">
    Create Category
    </h3>
</div>
@endsection
@section('content')
<div class="">
    <div class="col-md-12">
     {!! Form::open(['route'=>['admin.'.request()->segment(2).'.store'],'files'=>true]) !!}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    {{ Form::text('name',null,['class'=>'form-control']) }}
                    <p class="text-danger">{{$errors->first('name')}}</p>
                </div>
            </div>
           
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    {{ Form::text('sort_order',0,['class'=>'form-control']) }}

                    <p class="text-danger">{{$errors->first('sort_order')}}</p>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    <label for="Description">Description</label>
                    
                    {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>3]) !!}
                    <p class="text-danger">{{$errors->first('description')}}</p>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="parent">Parent Category</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                    <option value="0">No Parent</option>
                    @foreach ($all_category as $key => $p_category) {                       
                        <option value="{{$p_category->id}}">{{$p_category->name}}</option>
                      
                @endforeach
                </select>
                   
                    
                </div>
            </div>
        <div class="col-md-6 form-group">

                {!! Form::label('category_image', 'Category Image', ['class'=>'control-label']) !!}

                <span style="color: red"> *</span>

                    <input name="category_image" type="file" onchange="uploadImage(event)"/>

                    <img id="category_image" width="20%" height="20%" align='middle' src="{{asset('assets/image/kyc-image.png')}}"  />

                <p class="text-danger">{{ $errors->first('category_image') }}</p>

            </div>

        
            
        </div>


       
         <div class="row">

            <div class="col-md-4">
            <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Submit</button>
            </div>
        </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1//js/froala_editor.pkgd.min.js"></script>
<script>

   function uploadImage(event) {

        if(event.target.name == 'category_image'){

            category_image.src = URL.createObjectURL(event.target.files[0]);

        }

    }

  </script>

@endpush