@extends('Backend.includes.layout')
@section('content')
    <div class="row">
        <div class="col ">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">TVs</h1>
                    <div class="text-muted">Add Tv.</div>
                </div>
            </div>
            <div class="card shadow-lg border">
                <div class="card-body">
                    <form action="{{ route('backend.tv.store') }}" class="form " method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea rows="4" data-ckeditor name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3 ">


                            <label for="thumbnail" class="form-label">Thumbnail</label> <input
                                type="file" name="thumbnail" id=""
                                class="form-control @error('thumbnail') is-invalid @enderror"> <small
                                class="text-muted">Should be 150x150PX</small>
                            @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="row mb-3 g-3">
                            <div class="col">
                                <label for="country" class="form-label">Country</label>
                                <select name="region_id" id="country" class="js-choice form-control">
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="language" class="form-label">Language</label>
                                <select name="language_id" id="language" class="js-choice form-control">
                                    @foreach($languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3 g-3">
                            <div class="col-md-4 col-12">
                                <label for="category" class="form-label">Category</label>
                                <select name="category_id" id="category" class="js-choice form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->uuid }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 col-12">
                                <label for="genres" class="control-label col-form-label">Genres</label>
                                <input type="text" name="genres" id="genres" class="form-control tags-input">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stream_url" class="form-label">Stream Url</label>
                            <input type="text" name="stream_url" id="stream_url" class="form-control" />
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" class="btn  btn-primary">Add TV</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('header')
@endsection
@section('footer')
@endsection
