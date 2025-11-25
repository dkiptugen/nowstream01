@extends('Backend.includes.layout')
@section('content')
    <div class="card card-border-blue" aria-labelledby="add-role" id="edit-role-collapse">
        <div class="card-header">
            <h3 class="card-title my-0 text-blue">Edit Role</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('role.update',$role->id) }}" method="post" class="form form-horizontal create-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="role" class="control-label">Role Name</label>
                    <input type="text" name="role" id="role" class="form-control" value="{{ $role->name }}">
                </div>
                 <div class="form-group">
                    <label for="description" class="control-label">Description</label>
                     <textarea name="description" id="description" class="form-control editor">{{ $role->description }}</textarea>
                </div>
                <div class="form-row form-group">
                    <button type="submit" class="ml-auto mr-2 btn btn-blue">Edit Role</button>
                </div>
            </form>

        </div>
    </div>
@endsection
