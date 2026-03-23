@extends('Backend.includes.layout')

@section('content')
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="h3 mb-1">Episodes</h1>
                <div class="text-muted">Update this episode.</div>
            </div>
        </div>

        <div class="card shadow-lg border">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Episode</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.podcast.episode.update', ['podcast' => $podcast->uuid, 'episode' => $episode->uuid]) }}" class="form create-form" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $episode->title) }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control editor">{{ old('description', $episode->description) }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label for="episode_file" class="form-label">Episode File</label>
                            <input type="file" name="file" id="episode_file" class="form-control" accept="audio/*">
                            <small class="text-muted">Should be an mp3 file</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $episode->status))>
                            <span class="form-check-label">Active</span>
                        </label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary btn-sm" name="submit" value="publish" type="submit">Update Episode</button>
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
