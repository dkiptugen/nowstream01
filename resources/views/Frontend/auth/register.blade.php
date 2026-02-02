@extends('Frontend.auth.layout')

@section('content')
<div class="d-flex align-items-center justify-content-center my-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <div class="card mb-0">
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
                                <div class="mt-3 text-center">
                                    <img src="{{ asset('logo1.png') }}" width="120" alt="" />
                                </div> 

                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h5 class="">Register</h5>
                            <p class="mb-0">Please fill the below details to create your account</p>
                        </div>
                        <div class="form-body">
                            <form class="row g-3" method="POST" action="{{ route('user.register') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label for="inputUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="inputUsername" name="name" placeholder="John" value="{{ old('name') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="inputEmailAddress" name="email" placeholder="example@user.com" value="{{ old('email') }}">
                                </div>
                                <!-- Add Phone Number Field -->
                                <div class="col-12 col-md-6">
                                    <label for="inputPhoneNumber" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="inputPhoneNumber" name="phone" placeholder="Enter Phone Number" value="{{ old('phone') }}">
                                </div>
                                <!-- Add Image Upload Field -->
                                <div class="col-12">
                                    <label for="inputUserImage" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control" id="inputUserImage" name="image">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="inputChoosePassword" class="form-label">Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control border-end-0" id="inputConfirmPassword" name="password_confirmation" placeholder="Confirm Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">I read and agree to <a href="{{ route('terms') }}"> Terms &amp; Conditions</a>
                                           </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-default">Sign up</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0">Already have an account? <a href="{{ route('user.login') }}">Sign in here</a></p>
                                    </div>
                               
                                    </div>
                            </form>
                        </div>
                        <!-- <div class="login-separater text-center mb-5"> <span>OR SIGN UP WITH EMAIL</span>
                            <hr>
                        </div>
                        <div class="list-inline contacts-social text-center">
                            <a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
                            <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
                            <a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
                            <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
