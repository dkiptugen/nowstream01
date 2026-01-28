@extends('Backend.includes.layout')
@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h1 class="my-0 h6">Add User</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('backend.user.store') }}" method="post" class="form form-horizontal create-form">
                @csrf
                <div class="form-group row">
                    <div class="col col-md-8">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="col col-md-4">
                        <label for="role" class="control-label">Role</label>
                        <select name="role" id="role" class="form-control select2">
                            @foreach($role as $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-group mt-2">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>

                <div class="form-group row mt-2">
                    <div class="col col-md-6">
                        <label for="password" class="control-label">Password</label>
                        <input type="text" name="password" id="password" class="form-control">
                    </div>
                    <div class="col col-md-6">
                        <label for="con_password" class="control-label">Confirm Password</label>
                        <input type="text" name="con_password" id="con_password" class="form-control">
                    </div>

                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="1" name="status" id="active">
                    <label class="form-check-label" for="active">
                        Active
                    </label>
                </div>
                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="btn  btn-primary btn-sm">Add User</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
