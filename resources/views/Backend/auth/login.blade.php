@extends('Backend.auth.layout')

@section('content')
    <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
        <div class="d-table-cell align-middle">



            <div class="card">
                <div class="card-body">
                    <div class="m-sm-4">
                        <div class="text-center">
                            <img src="{{ $logo }}" width="154" alt="">
                        </div>
                        <form  method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{ __('Email | Username') }}</label>
                                <input type="text" placeholder="Enter your email or username" class=" form-control-lg form-control @error('email') is-invalid @enderror @error('username') is-invalid @enderror" name="email" value="{{ old('email')??old('username') }}" required autocomplete="email" autofocus />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" type="password" placeholder="Enter your password" />
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="w-100 d-flex">
                                    <a href="{{ url('reset') }}">Forgot password?</a>
                                </small>
                            </div>
                            <div>
                                <div class="form-check align-items-center">
                                    <input type="checkbox" class="form-check-input" value="remember-me" name="remember-me" checked>
                                    <label class="form-check-label text-small">Remember me next time</label>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-lg btn-primary">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if($message = Session::get('error'))
                <div class="alert alert-danger ">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
        </div>
    </div>




@endsection
