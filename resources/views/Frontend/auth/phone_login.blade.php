@extends('Frontend.auth.layout')

@section('content')
<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-5 mx-auto">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="p-4">
                            <!-- Success Alert -->
                            @if (session('success'))
                                <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-white"><i class='bx bxs-check-circle'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-white">Success Alert</h6>
                                            <div class="text-white">
                                                {{ session('success') }}
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
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
                                                    <div>
                                                        {{ $error }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="mb-3 text-center">
                                <img src="{{ asset('logo1.png') }}" width="150" alt="" />
                            </div>
                            <div class="form-body">
                                <form class="row g-3" method="post" action="{{ route('phonelogin') }}">
                                    @csrf
                                    <div class="col-12">
                                        <label for="inputPhoneNumber" class="form-label">
                                            {{ __('Please Enter Your Phone Number') }}
                                        </label>
                                        <input type="phone" class="form-control" id="inputPhoneNumber"
                                            placeholder="Enter Phone Number" name="phone" value="{{ $phone??'' }}">
                                    </div>
                                    <div class="col-12 text-center">
                                        <div class="d-grid mb-3">
                                            <button type="submit" class="btn btn-default text-white">Sign in</button>
                                        </div>
                                        <small>
                                            By Clicking Sign In, You Agree with Our  <a href="{{ route('terms') }}"> Terms &amp; Conditions</a>
                                        </small>
                                        <div class="login-separater text-center mb-3"> <span>OR SIGN IN WITH</span>
                                            <hr />
                                        </div>
                                        <div class="d-grid mt-3">
                                            <a href="{{route('phonelogin.form')}}" class="btn btn-dark"> Email and
                                                Password</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<style>
    .btn {
        font-size: 13px !important;
        font-weight: 400 !important;
    }
</style>
@endsection

@section('footer')
@endsection