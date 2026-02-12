@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="card card-border-primary">
            <div class="card-header">
                <h5 class="card-title h5 text-primary">Edit Category</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('backend.category.update',$category->uuid) }}" class="form form-horizontal create-form" method="post" id="updateCat" >
                    @csrf
                    @method('put')
                    <div class="form-group form-row">
                        <div class="col-12 col-md">
                            <label class="control-label">Category Name</label>
                            <input type="text" class="form-control" name="cat_name" placeholder="Name of the category" autofocus="" value="{{ $category->name }}" />
                        </div>


                    </div>

                    <div class="form-group form-row">
                        <div class="col">
                            <label class="control-label">listorder</label>
                            <input type="number" min="1" class="form-control" name="list_order" autofocus="" value="{{ $category->listorder }}"  />
                        </div>
                        <div class="col">
                            <label class="control-label">Parent Category</label>
                            <select class="form-control select2" name="p_cat" autofocus="">
                                <option value="0">No Parent</option>
                                @foreach($cat as $val)
                                    <option value="{{ $val->id }}" @if($val->id == $category->parent_id ) selected @endif>{{  $val->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" id="description" name="description editor" class="form-control editor">{{ $category->description }}</textarea>
                    </div>
                    <div class="form-group">
                            <label for="updatekeyword" class="control-label">Keywords</label>
                            <input type="text" class="form-control tags-input" id="updatekeyword" name="keywords" placeholder="Keywords separated by comma" value="{{ $keywords }}" autofocus="">
                   </div>
                    <div class="form-group">
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="topmenu" @if($category->top_menu) checked @endif value="1">
                            <span class="form-check-label">
                                Top Menu
                            </span>
                        </label>


                    </div>
                    <div class="form-group form-row">
                        <button class="btn btn-primary btn-sm ml-auto"  name="submit"  value="publish"  type="submit">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('header')

@endsection
@section('footer')

@endsection
