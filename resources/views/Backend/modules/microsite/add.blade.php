@extends('Backend.includes.layout')

@section('content')
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1">Brands</h1>
                <div class="text-muted">Add a brand</div>
            </div>
        </div>

        <div class="card shadow border">
            <div class="card-body">
                <form action="{{ route('backend.microsite.store') }}" method="POST" class="create-form" enctype="multipart/form-data">
                    @csrf

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea rows="4" name="description" id="description" class="form-control"></textarea>
                    </div>

                    {{-- COLORS --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="primary-color-text" class="form-label">Primary Color</label>
                            <div class="input-group">
                                <input type="color" value="#007bff" id="primary-color-picker" class="border-0 form-control-color">
                                <input type="text" name="colorscheme[primary]" id="primary-color-text" class="form-control" value="#007BFF">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="accent-color-text" class="form-label">Accent Color</label>
                            <div class="input-group">
                                <input type="color" id="accent-color-picker" value="#EEEEEE" class="border-0 form-control-color">
                                <input type="text" name="colorscheme[accent]" id="accent-color-text" class="form-control" value="#EEEEEE">
                            </div>
                        </div>
                    </div>

                    {{-- FILE UPLOADS --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                            <small class="text-muted">Image should be 512x512 pixels</small>
                        </div>
                        <div class="col-md-4">
                            <label for="cover" class="form-label">Cover Image</label>
                            <input type="file" name="cover" id="cover" class="form-control" accept="image/*">
                            <small class="text-muted">Image should be 1024x672 pixels</small>
                        </div>
                        <div class="col-md-4">
                            <label for="banner" class="form-label">Banner Image</label>
                            <input type="file" name="banner" id="banner" class="form-control" accept="image/*">
                            <small class="text-muted">Image should be 728x90 pixels</small>
                        </div>
                    </div>

                    {{-- TAG INPUTS --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="socialmedia_links" class="form-label">Social Media Links</label>
                            <input type="text" name="socialmedia" id="socialmedia_links" class="form-control tags-input" placeholder="https://x.com/test, https://facebook.com/test">
                        </div>
                        <div class="col-md-4">
                            <label for="keywords" class="form-label">Keywords</label>
                            <input type="text" name="keywords" id="keywords" class="form-control tags-input" placeholder="tech, programming">
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-dark-blue px-4" name="submit" value="publish" type="submit">Save Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

                // Picker updates Text (real-time)
                picker.addEventListener('input', function () {
                    text.value = picker.value.toUpperCase();
                });

                // Text updates Picker (on blur or Enter)
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
                        e.preventDefault(); // Prevent form submission on Enter in this field
                        applyColor();
                    }
                });
            }

            syncColorInputs('primary-color-picker', 'primary-color-text');
            syncColorInputs('accent-color-picker', 'accent-color-text');
        });
    </script>
@endsection
