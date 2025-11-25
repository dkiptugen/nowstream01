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
                    <h4 class="card-header text-center">{{ __('Reset Password') }}</h4> 
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <div class="my-4">
                        <input type="hidden" name="token" value="{{ $token }}">

                            <label class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="row mx-0 mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label> 
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                        </div>

                        <div class="row mx-0 mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
 
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password"> 
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset Password') }}</button>
                            <a href="{{route('login')}}" class="btn btn-light"><i class="bx bx-arrow-back me-1"></i>Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
