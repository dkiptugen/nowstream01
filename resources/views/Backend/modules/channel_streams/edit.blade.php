@extends('Backend.includes.layout')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Channels</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Channels </li>
                </ol>
            </nav>
        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title m-0 h5">Edit Channel</h3>
                    <hr>
                    <form action="" class="form form-horizontal">
                        <div class="form-group">
                            <label for="channel_name" class="control-label"> Channel Name</label>
                            <input type="text" name="channel_name" id="channel_name" class="form-control form-control-sm">
                        </div>
                        <div class="form-group mt-2">
                            <label for="channel_description" class="control-label">Description</label>
                            <textarea name="channel_description" id="channel_description" class="form-control" rows="10"></textarea>
                        </div>
                        <div class="form-group mt-2 row">
                            <div class="col">
                                <label for="thumbnail" class="control-label">Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail" class="form-control form-control-sm">
                                <small class="text-muted">Should be 150x150PX</small>
                            </div>
                            <div class="col">
                                <label for="cover_image" class="control-label">Cover Image</label>
                                <input type="file" name="cover_image" id="cover_image" class="form-control form-control-sm">
                                <small class="text-muted">Should be 1024x300PX</small>
                            </div>

                        </div>
                        <div class="form-group d-flex justify-content-end mt-2">
                            <button type="submit" name="" id="" class="btn btn-sm btn-primary">Add Channel</button>
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
@endsection
