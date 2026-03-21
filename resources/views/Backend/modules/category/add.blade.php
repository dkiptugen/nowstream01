@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="h3 mb-1">Categories</h1>
                <div class="text-muted">Add Category</div>
            </div>

        </div>
        <div class="card shadow-lg border">
            <div class="card-body">
                <form action="{{ route('backend.category.store') }}" class="form create-form" method="post"
                      id="addCat"> @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md"><label class="form-label">Category Name</label> <input type="text"
                                                                                                          class="form-control"
                                                                                                          name="cat_name"
                                                                                                          placeholder="Name of the category"
                                                                                                          autofocus=""/>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col"><label class="form-label">Listorder</label> <input type="number" min="1"
                                                                                            class="form-control"
                                                                                            name="list_order"
                                                                                            autofocus=""/></div>
                        <div class="col">
                            <label class="form-label">Parent Category</label> <select
                                class="form-select js-choice" name="p_cat" autofocus="">
                                <option value="0">No Parent</option> @foreach($cat as $val)
                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach </select></div>
                    </div>
                    <div class="mb-3"><label for="description" class="form-label">Description</label> <textarea
                            name="description" id="description" class="editor form-control" data-ckeditor data-ckeditor-height="250px"></textarea></div>
                    <div class="mb-3"><label for="updatekeyword" class="form-label">Keywords</label> <input type="text"
                                                                                                            class="form-control tags-input"
                                                                                                            id="updatekeyword"
                                                                                                            name="keywords"
                                                                                                            placeholder="Keywords separated by ;"
                                                                                                            autofocus="">
                    </div>
                    <div class="mb-3"><label class="form-check form-check-inline"> <input class="form-check-input"
                                                                                          type="checkbox" name="topmenu"
                                                                                          checked value="1"> <span
                                class="form-check-label"> Top Menu </span> </label></div>
                    <div class="d-flex justify-content-end g-3 mb-3">
                        <button class="btn btn-dark-blue ms-auto" name="submit" value="publish" type="submit">
                            SAVE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('header') @endsection
@section('footer') @endsection
