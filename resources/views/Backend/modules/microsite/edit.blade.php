@extends('Backend.includes.layout')
@section('content')
    <div class="card card-border-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0 h5 text-primary">Edit Microsite</h3>

        </div>
        <div class="card-body">
            <form action="{{ route('backend.microsite.update',['microsite'=> $microsite->id]) }}" method="post"
                  class="form form-horizontal create-form" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $microsite->name }}">
                </div>
                <div class="form-group">
                    <label for="Description" class="control-label">Description</label>
                    <input type="text" name="description" id="Description" class="form-control editor"
                           value="{{ $microsite->description }}">
                </div>
                <div class="form-group form-row">
                    <div class="col">
                        <label for="colorscheme-primary" class="control-label">Primary Color</label>
                        <div class="input-group" id="primary-color">
                            <input type="color" value="{{ $microsite->colorscheme['primary'] }}"
                                   id="primary-color-picker" class="border-0">
                            <input type="text" name="colorscheme[primary]" id="primary-color-text"
                                   class="form-control" value="{{ $microsite->colorscheme['primary'] }}">
                        </div>
                    </div>
                    <div class="col">
                        <label for="colorscheme-accent" class="control-label">Accent Color</label>
                        <div class="input-group" id="accent-color">
                            <input type="color" id="accent-color-picker"
                                   value="{{ $microsite->colorscheme['accent'] }}">
                            <input type="text" name="colorscheme[accent]" id="accent-color-text" class="form-control"
                                   value="{{ $microsite->colorscheme['accent'] }}">
                        </div>
                    </div>
                </div>

                <div class="form-group form-row">
                    <div class="col d-flex align-items-center">
                        <img src="{{ $microsite->logo }}" alt="logo" height="100" width="100" class="img-thumbnail">
                        <div class="form-group-file">
                            <label for="logo" class="control-label-file">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <img src="{{ $microsite->cover }}" alt="cover image" height="100" width="100"
                             class="img-thumbnail">
                        <div class="form-group-file">
                            <label for="cover" class="control-label-file">Cover Image</label>
                            <input type="file" name="cover" id="cover" class="form-control-file" accept="image/*">
                        </div>
                    </div>
                    <div class="col d-flex align-items-center">
                        <img src="{{ $microsite->cover }}" alt="logo" height="100" width="100" class="img-thumbnail">
                        <div class="form-group-file">
                            <label for="banner" class="control-label-file">Banner Image</label>
                            <input type="file" name="banner" id="banner" class="form-control-file" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-8">
                        <label for="socialmedia" class="control-label">Social Media Links</label>
                        <input type="text" name="socialmedia" id="socialmedia_links" class="form-control tags-input"
                               value="{{ collect($microsite->social_links)->implode(',') }}"
                               placeholder="Enter links separated by comma eg https://x.com/test, https://facebook.com/test">
                    </div>
                    <div class="col">
                        <label for="keywords" class="control-label">Keywords</label>
                        <input type="text" name="keywords" id="keywords" class="form-control tags-input"
                               value="{{ collect($microsite->keywords)->implode(',') }}"
                               placeholder="Enter keywords separated by comma eg tech, programming,">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="status"
                               @checked($microsite->status)  value="1">
                        <span class="form-check-label">
                                Active
                            </span>
                    </label>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function expandHex(hex) {
                hex = hex.replace('#', '');

                if (hex.length === 3) {
                    return '#' + hex.split('').map(c => c + c).join('');
                }

                if (hex.length === 6) {
                    return '#' + hex;
                }

                return null;
            }

            function syncColorInputs(pickerId, textId) {
                const picker = document.getElementById(pickerId);
                const text = document.getElementById(textId);

                if (!picker || !text) return;

                // Picker → Text (instant)
                picker.addEventListener('input', function () {
                    text.value = picker.value.toUpperCase();
                });

                // Text → Picker (ONLY when finished editing)
                function applyColor() {
                    let value = text.value.trim();

                    if (!value.startsWith('#')) {
                        value = '#' + value;
                    }

                    const expanded = expandHex(value);

                    if (expanded && /^#([0-9A-F]{6})$/i.test(expanded)) {
                        picker.value = expanded;
                        text.value = expanded.toUpperCase();
                    }
                }

                text.addEventListener('blur', applyColor);

                text.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        applyColor();
                    }
                });
            }

            syncColorInputs('primary-color-picker', 'primary-color-text');
            syncColorInputs('accent-color-picker', 'accent-color-text');

        });
    </script>
@endsection
