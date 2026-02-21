@extends('Backend.includes.layout')
@section('content')
    <div class="card card-border-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0 h5 text-primary">Add Microsite</h3>

        </div>
        <div class="card-body">
            <form action="{{ route('backend.microsite.store') }}" method="post" class="form form-horizontal create-form"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="Description" class="control-label">Description</label>
                    <input type="text" name="Description" id="Description" class="form-control editor">
                </div>
                <div class="form-group form-row">
                    <div class="col">
                        <label for="primary-color" class="control-label">Primary Color</label>
                        <div class="input-group" id="primary-color">
                            <input type="color" value="#007bff" id="primary-color-picker">
                            <input type="text" name="colorscheme[primary]" id="primary-color-text"
                                   class="form-control" value="#007bff">
                        </div>
                    </div>
                    <div class="col">
                        <label for="accent-color" class="control-label">Accent Color</label>
                        <div class="input-group" id="accent-color">
                            <input type="color" id="accent-color-picker"  value="#EEEEEE">
                            <input type="text" name="colorscheme[accent]" id="accent-color-text" class="form-control"
                                   value="#EEEEEE">
                        </div>
                    </div>
                </div>
                <div class="form-row form-group align-items-center">
                    <div class="col form-group-file">
                        <label for="logo" class="control-label-file">Logo</label>
                        <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
                        <small class="text text-muted">Image should be 512x512 pixels</small>
                    </div>
                    <div class="col form-group-file">
                        <label for="cover" class="control-label-file">Cover Image</label>
                        <input type="file" name="cover" id="cover" class="form-control-file" accept="image/*">
                        <small class="text text-muted">Image should be 1024x672 pixels</small>
                    </div>
                    <div class="col form-group-file">
                        <label for="banner" class="control-label-file">Banner Image</label>
                        <input type="file" name="banner" id="banner" class="form-control-file" accept="image/*">
                        <small class="text text-muted">Image should be 728x90 pixels</small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="socialmedia" class="control-label">Social Media Links</label>
                    <input type="text" name="social_links" id="socialmedia_links" class="form-control tags-input">
                </div>
                <div class="form-group form-row">
                    <button class="btn btn-primary btn-sm ml-auto" name="submit" value="publish"
                            type="submit">SAVE
                    </button>
                </div>


            </form>
        </div>
    </div>

@endsection
@section('header')
@endsection
@section('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function syncColorInputs(pickerId, textId) {
                const picker = document.getElementById(pickerId);
                const text   = document.getElementById(textId);

                if (!picker || !text) return;

                // Picker → Text
                picker.addEventListener('input', function () {
                    text.value = picker.value.toUpperCase();
                });

                // Text → Picker
                text.addEventListener('input', function () {
                    let value = text.value.trim();

                    // Ensure starts with #
                    if (!value.startsWith('#')) {
                        value = '#' + value;
                    }

                    // Validate hex color
                    if (/^#([0-9A-F]{3}){1,2}$/i.test(value)) {
                        picker.value = value;
                        text.value = value.toUpperCase();
                    }
                });
            }

            syncColorInputs('primary-color-picker', 'primary-color-text');
            syncColorInputs('accent-color-picker', 'accent-color-text');

        });
    </script>
@endsection
