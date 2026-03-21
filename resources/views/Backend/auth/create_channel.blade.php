@extends('Backend.auth.layout')

@section('content')
    <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
        <div class="d-table-cell align-middle">


            <div class="card">
                <div class="card-body">
                    <div class="m-sm-4">
                        <div class="text-center">
                            <img src="{{ $logo }}" width="154" alt="">
                        </div>
                        <form action="{{ route('backend.store_brand') }}" class="form form-horizontal create-form"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            @csrf
                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea rows="4" name="description" id="description" class="form-control "></textarea>
                            </div>
                            <div class="form-group form-group-file">
                                <label for="logo" class="control-label-file">Logo</label>
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                <small class="text text-muted">Image should be 512x512 pixels</small>
                            </div>
                            <div class="form-group form-group-file">
                                <label for="cover" class="control-label-file">Cover Image</label>
                                <input type="file" name="cover" id="cover" class="form-control" accept="image/*">
                                <small class="text text-muted">Image should be 1024x672 pixels</small>
                            </div>
                            <div class="form-group form-group-file">
                                <label for="banner" class="control-label-file">Banner Image</label>
                                <input type="file" name="banner" id="banner" class="form-control" accept="image/*">
                                <small class="text text-muted">Image should be 728x90 pixels</small>
                            </div>

                            <div class="form-group ">
                                <label for="keywords" class="control-label">Keywords</label>
                                <input type="text" name="keywords" id="keywords" class="form-control tags-input"
                                       placeholder="Enter keywords separated by comma eg tech, programming,">
                            </div>
                            <div class="form-group d-flex justify-content-end ">
                                <button type="submit" name="" id="" class="btn btn-sm btn-primary">Add Brand</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @if($message = Session::get('error'))
                <div class="alert alert-danger ">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
        </div>
    </div>

@endsection
