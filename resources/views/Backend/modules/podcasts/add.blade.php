@extends('Backend.includes.layout')
@section('content')
    <div class="col-12">
        <div class="card card-top-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title my-0 h5 text-primary">Add Podcast</h3>

            </div>
            <div class="card-body">
                <form action="{{ route('podcast.store') }}" method="post" class="form form-horizontal create-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>

                        <div class="col">
                            <label for="category" class="control-label">Category</label>
                            <select name="category[]" id="category" class="form-control select2">
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group form-row">
                        <div class="col">
                            <label for="stream_link" class="control-label">Stream Link</label>
                            <input type="text" name="stream_link" id="stream_link" class="form-control">
                        </div>
                        <div class="col">
                            <label for="house" class="control-label">Type</label>
                            <select name="house" id="house" class="form-control select2">
                                <option value="1">In house</option>
                                <option value="0">External</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="radio-select2">Related Radio</label>
                        <select id="radio-select2" name="radio" class="form-control">
                            <option value="">Select radio</option>
                            @foreach($radios as $radio)
                                <option value="{{ $radio->id }}">{{ $radio->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <div class="col">
                            <label for="image" class="control-label">Thumbnail</label>
                            <input type="file" name="logo" id="image" class="form-control-file">
                        </div>
                        <div class="col">
                            <label for="genre" class="control-label">Genre</label>
                            <input type="text" name="genre" id="genre" class="form-control tags-input">
                        </div>

                    </div>
                    <div class="form-group form-row">
                        <div class="col">
                            <label for="region" class="control-label">Region</label>
                            <select name="region" id="region" class="form-control select2">
                                @foreach($region as $reg)
                                    <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="content_rating" class="control-label">Content Rating</label>
                            <select name="content_rating" id="content_rating" class="form-control select2">
                                <option value="adult">Adult</option>
                                <option value="ge">GE</option>
                            </select>
                        </div>

                    </div>


                    <div class="form-group">
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="status" checked value="1">
                            <span class="form-check-label">
                                    Active
                            </span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="has_schedule" checked value="1">
                            <span class="form-check-label">
                                    Has Schedule
                            </span>
                        </label>
                    </div>
                    <div class="form-group form-row">
                        <button class="btn btn-primary btn-sm ml-auto" name="submit" value="publish"
                                type="submit">SAVE</button>
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
