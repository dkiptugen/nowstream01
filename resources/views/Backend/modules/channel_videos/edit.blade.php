@extends('Backend.includes.layout')

@section('content') 
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Channels</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('channel.video.datatable', $video->channel_id) }}">Channels</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Video</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title m-0 h5">Edit Video</h3>
                    <hr>

                    <!-- Display Success and Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('channel.video.update',[$channel->identifier, $video->id]) }}" method="POST" enctype="multipart/form-data" class="form form-horizontal create-form">
                        @csrf
                        @method('PUT') 
                        <div class="form-group">
                            <label for="title" class="control-label">Video Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-sm @error('title') is-invalid @enderror" value="{{ old('title', $video->title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-2">
                            <label for="description" class="control-label">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="10">{{ old('description', $video->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="event_id" class="control-label">Event</label>
                            <select name="event_id" id="event_id" class="form-control form-control-sm @error('event_id') is-invalid @enderror">
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id', $video->event_id) == $event->id ? 'selected' : '' }}>{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="thumbnail" class="control-label">Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbimage" class="form-control form-control-sm @error('thumbnail') is-invalid @enderror">
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
                        
                        <div class="form-group mt-2">
                            <label for="tags" class="control-label">Tags</label>
                            <select name="tags[]" id="tags" class="form-control form-control-sm @error('tags') is-invalid @enderror" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ is_array($selectedTagIds) && in_array($tag->id, $selectedTagIds) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="video_path" class="control-label">Video</label>
                            <input type="file" name="video_path" id="video_path" class="form-control form-control-sm @error('video_path') is-invalid @enderror">
                            @error('video_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary">Update Video</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tags').select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
        });
    </script>
@endsection
