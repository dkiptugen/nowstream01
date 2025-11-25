@extends('Backend.includes.layout')
@section('content')
    <div class="card card-border-dark" aria-labelledby="add-role"  id="add-role">
        <div class="card-header bg-light">
            <h3 class="text-dark h6 my-0 card-title">
                Add Role
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('role.store') }}" method="post" class="form form-horizontal create-form" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">
                    <label for="role" class="control-label">Role Name</label>
                    <input type="text" name="role" id="role" class="form-control font-control-sm" value="{{ old('name') }}">
                </div>
                 <div class="form-group mt-2">
                    <label for="description" class="control-label">Description</label>
                     <textarea name="description" id="description" class="form-control editor">{{ old('description') }}</textarea>
                </div>
                <div class="d-flex form-group justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">Save Role</button>
                </div>
            </form>

        </div>
    </div>
@endsection
