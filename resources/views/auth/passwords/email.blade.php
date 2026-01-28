@extends('Frontend.auth.layout')

@section('content')
<div class="wrapper">
    <div class="authentication-forgot d-flex align-items-center justify-content-center">
        <div class="card forgot-box">

            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="card-body">
                <div class="p-3">
                    <div class="text-center">
                        <img src="{{asset('/logo1.png')}}" width="200" alt="">
                    </div>
                    <h4 class="mt-5 font-weight-bold">{{ __('Forgot Password?') }}</h4>
                    <p class="text-muted">Enter your registered email ID to reset the password</p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="my-4">
                            <label class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Password Reset Link') }}</button>
                            <a href="{{route('user.login')}}" class="btn btn-light"><i class="bx bx-arrow-back me-1"></i>Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
