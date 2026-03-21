@extends('Backend.includes.layout') @section('content')
    <div class="row">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-1">Event Video</h1>
                    <div class="text-muted">Add <span class="text text-danger">{{ $event->event_name }} event</span>  video.</div>
                </div>

            </div>
            <div class="card shadow-lg border">
                <div class="card-body"> <!-- Display Success and Error Messages --> @if(session('success'))
                        <div class="alert alert-success"> {{ session('success') }} </div>
                    @endif @if(session('error'))
                        <div class="alert alert-danger"> {{ session('error') }} </div>
                    @endif
                    <form action="{{ route('backend.event.video.store',["event"=>$event->uuid]) }}" method="POST" enctype="multipart/form-data"
                          class="form create-form"> @csrf
                        <div class="mb-3"><label for="title" class="form-label">Video Title</label> <input type="text"
                                                                                                           name="title"
                                                                                                           id="title"
                                                                                                           class="form-control @error('title') is-invalid @enderror"
                                                                                                           value="{{ old('title') }}"> @error('title')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror </div>
                        <div class="mb-3"><label for="description" class="form-label">Description</label> <textarea
                                name="description" id="description"
                                class="form-control editor @error('description') is-invalid @enderror"
                                rows="10">{{ old('description') }}</textarea> @error('description')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror </div>

                        <div class="mb-3"><label for="tags" class="form-label">Tags</label> <input type="text"
                                                                                                   name="tags[]"
                                                                                                   id="tags"
                                                                                                   class="form-control tagsiput @error('tags') is-invalid @enderror"/> @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror </div>
                        <div class="row g-3 mb-3">
                            <div class="col"><label for="thumbnail" class="form-label">Thumbnail</label> <input
                                    type="file" name="thumbnail" id=""
                                    class="form-control @error('thumbnail') is-invalid @enderror"> <small
                                    class="text-muted">Should be 150x150PX</small> @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div> @enderror </div>
                            <div class="col"><label for="video_path" class="form-label">Video</label> <input type="file"
                                                                                                             name="video_path"
                                                                                                             id="video_path"
                                                                                                             class="form-control @error('video_path') is-invalid @enderror"> @error('video_path')
                                <div class="invalid-feedback">{{ $message }}</div> @enderror </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-dark-blue">Add Video</button>
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
