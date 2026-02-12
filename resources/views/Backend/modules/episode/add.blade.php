@extends('includes.body')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Add Episode</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.podcast.episode.store',['podcast'=>$podcast->uuid]) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="" class="control-label">Title</label>
                        <input type="text" class="form-control">
                    </div>
                     <div class="form-group">
                        <label for="" class="control-label">Description</label>
                         <textarea class="form-control editor"></textarea>
                    </div>
                    <div class="form-group form-row">
                        <div class="col">
                            <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="thumbnail">
                                  <label class="custom-file-label" for="thumbnail">Thumbnail</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="customFile" accept="audio/*">
                                  <label class="custom-file-label" for="customFile">Episode file</label>
                            </div>
                            <small id="passwordHelpBlock" class="form-text text-muted">Should be an mp3 file</small>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="status"
                                   value="1">
                            <span class="form-check-label">
                                    Active
                                </span>
                        </label>

                    </div>
                    <div class="form-group form-row">
                        <button class="btn btn-primary btn-sm ml-auto" name="submit" value="publish"
                                type="submit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('header')

@endsection
@section('footer')
