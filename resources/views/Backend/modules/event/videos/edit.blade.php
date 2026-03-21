@extends('Backend.includes.layout')

@section('content')
    <div class="row">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Videos</h1>
                    <div class="text-muted">Update video details.</div>
                </div>
            </div>

            <div class="card shadow-lg border">
                <div class="card-header">
                    <h3 class="card-title m-0 h5">Edit Video</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('backend.video.update', ['video' => $video->uuid]) }}" method="POST" enctype="multipart/form-data" class="form create-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Video Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $video->title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control editor @error('description') is-invalid @enderror" rows="10">{{ old('description', $video->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="event_id" class="form-label">Event</label>
                            <select name="event_id" id="event_id" class="form-select @error('event_id') is-invalid @enderror js-choice">
                                @foreach($events as $event)
                                    <option value="{{ $event->uuid }}" {{ old('event_id', $video->event_uuid) == $event->uuid ? 'selected' : '' }}>{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="thumbimage" class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbimage" class="form-control @error('thumbnail') is-invalid @enderror">
                            @if($video->thumbnail_url)
                                <div class="mt-2">
                                    <img src="{{ $video->thumbnail_url }}" alt="Thumbnail" class="img-fluid" style="max-width: 200px;">
                                </div>
                            @endif
                            <small class="text-muted">Should be 150x150PX</small>
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" name="tags" id="tags" class="form-control tags-input @error('tags') is-invalid @enderror">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="video_path" class="form-label">Video</label>
                            <input type="file" name="video_path" id="video_path" class="form-control @error('video_path') is-invalid @enderror">
                            @error('video_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">Update Video</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
@endsection
