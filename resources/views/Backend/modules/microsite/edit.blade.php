@extends('Backend.includes.layout')
@section('content')
    <div class="card card-border-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0 h5 text-primary">Edit Microsite</h3>

        </div>
        <div class="card-body">
            <form action="{{ route('backend.microsite.update',['microsite'=> $microsite->uuid]) }}" method="post"
                  class="form form-horizontal create-form" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $microsite->name }}">
                </div>
                <div class="form-group">
                    <label for="Description" class="control-label">Description</label>
                    <input type="text" name="Description" id="Description" class="form-control editor"
                           value="{{ $microsite->description }}">
                </div>
                <div class="form-group form-row">
                    <div class="col">
                        <label for="colorscheme-primary" class="control-label">Primary Color</label>
                        <input type="color" name="colorscheme[primary]" id="colorscheme-primary" class="form-control"
                               value="{{ $microsite->colorscheme['primary'] }}">
                    </div>
                    <div class="col">
                        <label for="colorscheme-accent" class="control-label">Accent Color</label>
                        <input type="color" name="colorscheme[accent]" id="colorscheme-accent" class="form-control"
                               value="{{ $microsite->colorscheme['accent'] }}">
                    </div>
                </div>
                <div class="form-group d-flex align-items-center">
                    <img src="{{ $microsite->logo }}" alt="logo" height="100" width="100" class="img-thumbnail">
                    <div class="form-group-file">
                        <label for="logo" class="control-label-file">Logo</label>
                        <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
                    </div>

                </div>
                <div class="form-group d-flex align-items-center">
                    <img src="{{ $microsite->cover }}" alt="cover image" height="100" width="100" class="img-thumbnail">
                    <div class="form-group-file">
                        <label for="cover" class="control-label-file">Cover Image</label>
                        <input type="file" name="cover" id="cover" class="form-control-file" accept="image/*">
                    </div>
                </div>
                <div class="form-group d-flex align-items-center">
                    <img src="{{ $microsite->cover }}" alt="logo" height="100" width="100" class="img-thumbnail">
                    <div class="form-group-file">
                        <label for="banner" class="control-label-file">Banner Image</label>
                        <input type="file" name="banner" id="banner" class="form-control-file" accept="image/*">
                    </div>
                </div>
                <div class="form-group">
                    <label for="socialmedia" class="control-label">Social Media Links</label>
                    <input type="text" name="socialmedia" id="socialmedia_links" class="form-control tags-input" value="{{ collect($microsite->social_links)->implode(',') }}">
                </div>
                <div class="form-group form-row">
                    <button class="btn btn-primary btn-sm ml-auto" type="submit">
                        Update
                    </button>
                </div>


            </form>
        </div>
    </div>

@endsection
@section('header')
@endsection
@section('footer')
@endsection
