@extends('Backend.includes.layout')
@section('content')
    <div class="row">
        <div class="col ">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Tvs</h1>
                    <div class="text-muted">Edit Television</div>
                </div>
            </div>
            <div class="card shadow-lg border">
                <div class="card-body">
                    <form action="{{ route('backend.tv.update', ['tv' => $tv->uuid]) }}" class="form create-form" method="post"  enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required
                                   value="{{ old('title',$tv->title) }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea rows="4" name="description" id="description" data-ckeditor
                                      class="form-control">{{ old('description',$tv->description) }}</textarea>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <div class="col-2 col-md-1">
                            <img src="{{ $tv->thumbnail_url }}" alt="{{ $tv->title }}" class="img-fluid img-thumbnail" width="150"
                                 height="150">
                            </div>
                            <div class="form-input col-10 col-md-11">
                                <label for="thumbnail" class="form-label">Thumbnail</label> <input
                                    type="file" name="thumbnail" id=""
                                    class="form-control @error('thumbnail') is-invalid @enderror"> <small
                                    class="text-muted">Should be 150x150PX</small>
                                @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 g-3">
                            <div class="col">
                                <label for="country" class="form-label">Country</label>
                                <select name="region_id" id="country" class="js-choice form-control">
                                    @foreach($regions as $region)
                                        <option
                                            value="{{ $region->id }}" @selected($tv->region_id == $region->id)>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="language" class="form-label">Language</label>
                                <select name="language_id" id="language" class="js-choice form-control">
                                    @foreach($languages as $language)
                                        <option
                                            value="{{ $language->id }}" @selected($tv->language_id == $language->id)>{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3 g-3">
                            <div class="col-md-4 col-12">
                                <label for="category" class="form-label">Category</label>
                                <select name="category_id" id="category" class="js-choice form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->uuid }}" @selected($tv->category_id == $category->uuid)> {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 col-12">
                                <label for="genre" class="control-label col-form-label">Genres</label>
                                <input type="text" name="genre" id="genre" class="form-control tags-input" value="{{ old('genre', collect($tv->genre ?? [])->implode(',')) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stream_url" class="form-label">Stream Url</label>
                            <input type="text" name="stream_url" id="stream_url" class="form-control"
                                   value="{{ old('stream_url',$tv->stream_url) }}"/>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-primary">Edit TV</button>
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
