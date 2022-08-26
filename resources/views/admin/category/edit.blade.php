@extends('admin.layout.master')

@section('title','Edit Category')

@section('head')

<div class="page-head">

    <h3 class="m-b-less">

    Edit Category

    </h3>

</div>

@endsection

@section('content')

<div class="">

    <div class="col-md-12">

        {!! Form::open(['route'=>['admin.'.request()->segment(2).'.update',$category->id],'method'=>'put', 'files'=>true]) !!}

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    {{ Form::text('name',$category->name,['class'=>'form-control']) }}
                    <p class="text-danger">{{$errors->first('name')}}</p>
                </div>
            </div>
           
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    {{ Form::text('sort_order',$category->sort_order,['class'=>'form-control']) }}

                    <p class="text-danger">{{$errors->first('sort_order')}}</p>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    <label for="Description">Description</label>
                    
                    {!! Form::textarea('description', $category->description, ['class'=>'form-control','rows'=>3]) !!}
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
        <div class="col-md-4 form-group">

                {!! Form::label('category_image', 'Category Image', ['class'=>'control-label']) !!}

                <span style="color: red"> *</span>

                    <input name="category_image" type="file" onchange="uploadImage(event)"/>

                    <img id="category_image" width="20%" height="20%" align='middle' src="{{asset('assets/image/kyc-image.png')}}"  />

                <p class="text-danger">{{ $errors->first('category_image') }}</p>

            </div>

             <div class="col-md-4">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                    @foreach ($all_status as $p_status) {     
                    @if ($p_status['id']==$category->status)
                    <option value="{{$p_status['id']}}" selected="selected">{{$p_status['name']}}</option>
                    @else

                   <option value="{{$p_status['id']}}" >{{$p_status['name']}}</option>
                    @endif
                    @endforeach
                </select>
                   
                    
                </div>
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