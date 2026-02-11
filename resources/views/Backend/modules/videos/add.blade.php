@extends('Backend.includes.layout')

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Videos</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('backend.admin_dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Channels</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title m-0 h5">Add Video</h3>
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

                    <form action="{{ route('backend.video.store') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal create-form">
    @csrf

                        <div class="form-group">
        <label for="title" class="control-label">Video Title</label>
        <input type="text" name="title" id="title" class="form-control form-control-sm @error('title') is-invalid @enderror" value="{{ old('title') }}">
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
    </div>

    <div class="form-group mt-2">
        <label for="description" class="control-label">Description</label>
        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="10">{{ old('description') }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mt-2">
        <label for="event_id" class="control-label">Event</label>
        <select name="event_id" id="event_id" class="form-control  @error('event_id') is-invalid @enderror select2">
            @foreach($events as $event)
                <option value="{{ $event->uuid }}" {{ old('event_id') == $event->uuid ? 'selected' : '' }}>{{ $event->event_name }}</option>
            @endforeach
        </select>
        @error('event_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mt-2">
        <label for="tags" class="control-label">Tags</label>
        <select name="tags[]" id="tags" class="form-control form-control-sm @error('tags') is-invalid @enderror" multiple="multiple">
            <!-- Add options here if needed or leave it empty for dynamic tags -->
        </select>
        @error('tags')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

            <div class="form-group mt-2 row">
                        <div class="col">
                            <label for="thumbnail" class="control-label">Thumbnail</label>
                            <input type="file" name="thumbnail" id="" class="form-control form-control-sm @error('thumbnail') is-invalid @enderror">
                            <small class="text-muted">Should be 150x150PX</small>
                            @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="video_path" class="control-label">Video</label>
                            <input type="file" name="video_path" id="video_path" class="form-control form-control-sm @error('video_path') is-invalid @enderror">
                            @error('video_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
    <div class="form-group d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-sm btn-primary">Add Video</button>
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
    <script type="text/javascript">
    $(document).ready(function() {
        $('#tags').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
    });
</script>

@endsection
