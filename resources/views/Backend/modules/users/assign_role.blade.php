@extends('Backend.includes.layout')
@section('content')
<div class="col-12">
    <div class="card shadow-lg m-0">
        <div class="card-header d-flex justify-content-between">
            <h5 class="modal-title">Assign Roles</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="card-body">
            <form action="{{ route('users.roles.assign',$userid) }}" class="form form-horizontal create-form" method="post">
                @csrf

                <div class="form-group">
                    <label for="add-role" class="control-label">Role</label>
                    <select name="role" id="add-role" class="form-select">
                        @foreach(\App\Models\Role::get() as $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group row">
                    <div class="ms-auto text-end">
                        <button type="submit" class="btn btn-primary">Save</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
