@extends('Backend.includes.layout')
@section('content')
    <div class="col">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1">Brands</h1>
                <div class="text-muted">Edit a brand</div>
            </div>
        </div>
        <div class="card shadow-lg border">
            <div class="card-body">
                <form action="{{ route('backend.microsite.update',['microsite'=> $microsite->uuid]) }}" method="post"
                      class="form create-form" enctype="multipart/form-data"> @csrf @method('put')
                    <div class="mb-3"><label for="name" class="form-label">Name</label> <input type="text" name="name"
                                                                                               id="name"
                                                                                               class="form-control"
                                                                                               value="{{ $microsite->name }}">
                    </div>
                    <div class="mb-3"><label for="Description" class="form-label">Description</label> <input type="text"
                                                                                                             name="description"
                                                                                                             id="Description"
                                                                                                             class="form-control editor"
                                                                                                             value="{{ $microsite->description }}">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col"><label for="colorscheme-primary" class="form-label">Primary Color</label>
                            <div class="input-group" id="primary-color"><input type="color"
                                                                               value="{{ $microsite->colorscheme['primary']??'' }}"
                                                                               id="primary-color-picker" class="border-0">
                                <input type="text" name="colorscheme[primary]" id="primary-color-text" class="form-control"
                                       value="{{ $microsite->colorscheme['primary']??'' }}"></div>
                        </div>
                        <div class="col"><label for="colorscheme-accent" class="form-label">Accent Color</label>
                            <div class="input-group" id="accent-color"><input type="color" id="accent-color-picker"
                                                                              value="{{ $microsite->colorscheme['accent']??'' }}">
                                <input type="text" name="colorscheme[accent]" id="accent-color-text" class="form-control"
                                       value="{{ $microsite->colorscheme['accent']??"" }}"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col d-flex align-items-center"><img src="{{ $microsite->logo }}" alt="logo" height="100"
                                                                        width="100" class="img-thumbnail">
                            <div class="g-3 ms-3"><label for="logo" class="form-label">Logo</label> <input type="file"
                                                                                                           name="logo" id="logo"
                                                                                                           class="form-control"
                                                                                                           accept="image/*">
                            </div>
                        </div>
                        <div class="col d-flex align-items-center"><img src="{{ $microsite->cover }}" alt="cover image"
                                                                        height="100" width="100" class="img-thumbnail">
                            <div class="g-3 ms-3"><label for="cover" class="form-label">Cover Image</label> <input type="file"
                                                                                                                   name="cover"
                                                                                                                   id="cover"
                                                                                                                   class="form-control"
                                                                                                                   accept="image/*">
                            </div>
                        </div>
                        <div class="col d-flex align-items-center"><img src="{{ $microsite->cover }}" alt="logo"
                                                                        height="100" width="100" class="img-thumbnail">
                            <div class="ms-3 g-3"><label for="banner" class="form-label">Banner Image</label> <input
                                    type="file" name="banner" id="banner" class="form-control" accept="image/*"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-8"><label for="socialmedia" class="form-label">Social Media Links</label> <input
                                type="text" name="socialmedia" id="socialmedia_links" class="form-control tags-input"
                                value="{{ collect($microsite->social_links)->implode(',') }}"
                                placeholder="Enter links separated by comma eg https://x.com/test, https://facebook.com/test">
                        </div>
                        <div class="col"><label for="keywords" class="form-label">Keywords</label> <input type="text"
                                                                                                          name="keywords"
                                                                                                          id="keywords"
                                                                                                          class="form-control tags-input"
                                                                                                          value="{{ collect($microsite->keywords)->implode(',') }}"
                                                                                                          placeholder="Enter keywords separated by comma eg tech, programming,">
                        </div>
                    </div>
                    <div class="mb-3"><label class="form-check form-check-inline"> <input class="form-check-input"
                                                                                          type="checkbox" name="status"
                                                                                          @checked($microsite->status) value="1">
                            <span class="form-check-label"> Active </span> </label></div>
                    <div class="mb-3 d-flex justify-content-end">
                        <button class="btn btn-dark-blue" type="submit"> Update Brand</button>
                    </div>
                </form>
            </div>
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
