@extends('includes.body')
@section('content')
<div class="col-12 mb-3">
    <div class="card ">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Category</h5>
        </div>
        <div class="card-body">

            <form action="{{ route('category.store') }}" class="form form-horizontal create-form" method="post" id="addCat" >
                @csrf
                <div class="form-group row">
                    <div class="col-12 col-md">
                        <label class="control-label">Category Name</label>
                        <input type="text" class="form-control" name="cat_name" placeholder="Name of the category" autofocus=""  />
                    </div>


                </div>

                <div class="form-group form-row">
                    <div class="col">
                        <label class="control-label">listorder</label>
                        <input type="number" min="1" class="form-control" name="list_order" autofocus=""  />
                    </div>
                    <div class="col">
                        <label class="control-label">Parent Category</label>
                        <select class="form-control select2" name="p_cat" autofocus="">
                            <option value="0">No Parent</option>

                            @foreach($cat as $val)
                               <option value="{{ $val->id }}">{{  $val->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="control-label">Description</label>
                    <textarea name="description" id="description" name="description" class="editor form-control"></textarea>
                </div>
                <div class="form-group ">
                    <label for="updatekeyword" class="control-label">Keywords</label>
                    <input type="text" class="form-control tags-input" id="updatekeyword" name="keywords" placeholder="Keywords separated by ;" autofocus="">
                </div>
                <div class="form-group">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="topmenu" checked value="1">
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
