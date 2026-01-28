@extends('Backend.includes.layout')
@section('content')
    <div class="card card-border-blue" aria-labelledby="add-role"  id="add-role">
        <div class="card-header">
            <h3 class="my-0 text-blue card-title">
               {{ __('Assign Permissions to '. $role->name) }}
            </h3>
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('backend.role.assign',$role->id) }}" class="form create-form" enctype="multipart/form-data" method="post">
             @csrf

                <div class="card-columns">
             @foreach($permission as $key => $value)
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title my-0">{{ ucfirst(strtolower($key)) }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($value as $perm)
                            <div class="form-check">
                                <input type="checkbox" name="perm[]" id="perm-{{ $perm['id'] }}" class="form-check-input" @if($role->hasPermissionTo( $perm['name'])) checked @endif value="{{ $perm['name'] }}">
                                <label for="perm-{{ $perm['id'] }}" class="form-check-label">{{ $perm['display_name'] }}</label>
                            </div>
                        @endforeach

                    </div>
             	</div>
             @endforeach
         </div>
                <div class="d-flex">
                    <a href="{{ route('role.index') }}" class="btn btn-dark  ml-auto">Close</a>
                    <button type="submit" class="btn btn-blue ml-3">Assign Permissions</button>
                </div>
                </form>

        </div>
    </div>
@endsection
