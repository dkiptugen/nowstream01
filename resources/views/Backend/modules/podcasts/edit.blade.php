@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="card card-border-primary">
            <div class="card-header">
                <h3 class="card-title my-0 h5 text-primary">Edit Podcast</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.podcast.update',$podcast->uuid) }}" method="post" class="form form-horizontal create-form" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="form-group form-row">
                        <div class="col">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $podcast->title }}">
                        </div>
                        <div class="col">
                            <label for="category" class="control-label">Category</label>
                            <select name="category[]" id="category" class="form-control select2" multiple>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->uuid }}" @if(in_array($cat->uuid,$podcast->categories->pluck('uuid')->toArray())) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" id="description" class="form-control editor">{{ $podcast->description }}</textarea>
                    </div>
                    <div class="form-group form-row">
                        <div class="col">
                            <label for="stream_link" class="control-label">Stream Link</label>
                            <input type="text" name="stream_link" id="stream_link" class="form-control" value="{{ $podcast->podcast_link }}">
                        </div>
                        <div class="col">
                            <label for="house" class="control-label">Type</label>
                            <select name="house" id="house" class="form-control select2">
                                <option value="1" @if($podcast->house == 1) selected @endif>Inhouse</option>
                                <option value="0" @if($podcast->house == 0) selected @endif>External</option>
                                <option value="2" @if($podcast->house == 2) selected @endif>Self Onboarding</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group form-row align-items-center">
                        <div class="col d-flex align-items-center">
                            <img src="{{ $podcast->thumbnail }}" alt="" height="80" width="80" class="mr-2 rounded">
                            <div class="">
                                <label for="image" class="control-label">Thumbnail</label>
                                <input type="file" name="logo" id="image" class="form-control-file">
                            </div>

                        </div>
                        <div class="col">
                            <label for="genre" class="control-label">Genre</label>
                            <input type="text" name="genre" id="genre" class="form-control tags-input" value="{{ implode(',',$podcast->tags->pluck('name')->toArray()) }}">
                        </div>


                    </div>

                    <div class="form-group form-row">
                        <div class="col">
                            <label for="region" class="control-label">Region </label>
                            <select name="region" id="region" class="form-control select2">
                                @foreach($region as $reg)
                                    <option value="{{ $reg->id }}" @if($podcast->region_id == $reg->id) selected @endif>{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="content_rating" class="control-label">Content Rating</label>
                            <select name="content_rating" id="content_rating" class="form-control select2">
                                <option value="adult" @selected($podcast->is_explici)>Adult</option>
                                <option value="ge" @selected(!$podcast->is_explicit ) >GE</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="status" @if($podcast->status == 1)checked @endif value="1">
                            <span class="form-check-label">
                                    Active
                                </span>
                        </label>

                    </div>
                    <div class="form-group form-row">
                        <button class="btn btn-primary btn-sm ml-auto"  name="submit"  value="publish"  type="submit">Edit</button>
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
