@extends('Backend.includes.layout')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title m-0 h5">Edit Channel</h3>
                </div>
                <div class="card-body">


                    <form action="{{ route('channel.update',$channel->id) }}" class="form form-horizontal create-form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="channel_name" class="control-label"> Channel Name</label>
                            <input type="text" name="channel_name" id="channel_name" class="form-control" value="{{ $channel->name }}">
                        </div>
                        <div class="form-group ">
                            <label for="channel_description" class="control-label">Description</label>
                            <textarea name="channel_description" id="channel_description" class="form-control editor" rows="10">{{ $channel->description }}</textarea>
                        </div>
                        <div class="form-group  row">
                            <div class="col">
                                <label for="thumbnail" class="control-label">Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail" class="form-control-file">
                                <small class="text-muted">Should be 150x150PX</small>
                            </div>
                            <div class="col">
                                <label for="cover_image" class="control-label">Cover Image</label>
                                <input type="file" name="cover_image" id="cover_image" class="form-control-file">
                                <small class="text-muted">Should be 1024x300PX</small>
                            </div>

                        </div>
                        <div class="form-group d-flex justify-content-end ">
                            <button type="submit" name="" id="" class="btn btn-sm btn-primary">Edit Channel</button>
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
