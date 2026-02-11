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
                        <form action="{{ route('backend.store_channel') }}" class="form form-horizontal create-form"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="channel_name" class="control-label"> Channel Name</label>
                                <input type="text" name="channel_name" id="channel_name"
                                       class="form-control form-control-sm">
                            </div>
                            <div class="form-group ">
                                <label for="channel_description" class="control-label">Description</label>
                                <textarea name="channel_description" id="channel_description" class="form-control"
                                          rows="6"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="thumbnail" class="control-label">Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail_image" class="form-control-file">
                                <small class="text-muted">Should be 150x150PX</small>
                            </div>
                            <div class="form-group">
                                <label for="cover_image" class="control-label">Cover Image</label>
                                <input type="file" name="cover_image" id="cover_image" class="form-control-file">
                                <small class="text-muted">Should be 1024x300PX</small>
                            </div>
                            <div class="form-group d-flex justify-content-end ">
                                <button type="submit" name="" id="" class="btn btn-sm btn-primary">Add Channel</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @if($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-d.ismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
        </div>
    </div>

@endsection
