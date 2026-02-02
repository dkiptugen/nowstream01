@extends('Frontend.includes.layout')
@section('content')
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="font-35 text-white"><i class='bx bxs-check-circle'></i></div>
                <div class="ms-3">
                    <h6 class="mb-0 text-white">Success Alert</h6>
                    <div class="text-white">{{ session('success') }}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i></div>
                <div class="ms-3">
                    <h6 class="mb-0 text-white">Error Alert</h6>
                    <div class="text-white">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

 
<div class="container">
    <div class="main-body">
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{ $user->image ?? asset('avatar.png')}}" alt="Profile Image" class="rounded-circle p-1 bg-primary" width="110">
                            <div class="mt-3">
                                <h4>{{ $user->name }}</h4>
                                <p class="text-secondary mb-1">{{ $user->phone ?? 'Phone number' }}</p>
                                <!-- <button class="btn btn-outline-primary">Create Channel</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Full Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Profile Image</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                </div>
                            </div>
                        </form>

    <hr>
            <h5 class="mb-3">Change Password</h5>

<!-- Password Update Form -->
<form method="POST" action="{{ route('profile.password.update') }}">
    @csrf
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-0">Current Password</h6>
        </div>
        <div class="col-sm-9 text-secondary">
            <input type="password" class="form-control" name="current_password" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-0">New Password</h6>
        </div>
        <div class="col-sm-9 text-secondary">
            <input type="password" class="form-control" name="password" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-0">Confirm Password</h6>
        </div>
        <div class="col-sm-9 text-secondary">
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9 text-secondary">
            <button type="submit" class="btn btn-primary px-4">Update Password</button>
        </div>
    </div>
</form>
                    </div>
                </div>
            </div>
        </div>
      
        

    </div>
</div>

@endsection
@section('header')
@endsection
@section('footer')
@endsection