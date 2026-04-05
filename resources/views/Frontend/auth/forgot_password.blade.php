@extends('Frontend.auth.layout')
@section('content')
    <div class="authentication-forgot d-flex align-items-center justify-content-center">
        <div class="card forgot-box">
            <div class="card-body">
                <div class="p-3">
                    <div class="text-center">
                                <img src="{{ asset('assets/img/logo/logo2.png') }}" width="100" alt="" />
                    </div>
                    <h4 class="mt-5 fw-bold">Forgot Password?</h4>
                    <p class="text-muted">Enter your registered email ID to reset the password</p>
                    <div class="my-4">
                        <label class="form-label">Email id</label>
                        <input type="text" class="form-control" placeholder="example@user.com" />
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger">Send</button>
                        <a href="authentication-signin.html" class="btn btn-light"><i class='bx bx-arrow-back me-1'></i>Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
