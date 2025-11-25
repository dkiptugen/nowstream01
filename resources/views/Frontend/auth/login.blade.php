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
                            <div class="text-center mb-4">
                                <p class="mb-0">
                                    {{ __('Please log in to your account') }}
                                </p>
                            </div>
                            <div class="form-body">
                                	@if($country == 'KE') 
                                        <div class="d-grid mt-3">
                                            <a href="{{route('login')}}" class="btn btn-dark"> One-Tap Phone
                                                Number Login</a>
                                        </div>
                            <div class="login-separater text-center mb-3"> <span>OR SIGN IN WITH</span>
                                <hr />
                            </div> 
											@endif
                                <form class="row g-3" method="post" action="{{ route('new.login') }}">
                                    @csrf
                                    <div class="col-12">
                                        <label for="inputEmailAddress" class="form-label">
                                            {{ __('Email') }}
                                        </label>
                                        <input type="email" class="form-control" id="inputEmailAddress"
                                            placeholder="Email address" name="email">
                                    </div>
                                    <div class="col-12">
                                        <label for="inputChoosePassword" class="form-label">Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" class="form-control border-end-0"
                                                id="inputChoosePassword" placeholder="Enter Password" name="password">
                                            <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                    class='bx bx-hide'></i></a>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                name="remember-me" value="1">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Remember
                                                Me</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end"> <a href="{{ route('password.request') }}">Forgot
                                            Password ?</a>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-default text-white">Sign in</button>
                                        </div>
                                    </div>

                                </form>
                                <div class="col-12">
                                    <div class="text-center ">
                                        <p class="my-3">Don't have an account? <a
                                                href="{{ route('user.register') }}">Sign up here</a></p>
                                    <small>
                                            By Clicking Sign In, You Agree with Our  <a href="{{ route('terms') }}"> Terms &amp; Conditions</a>
                                        </small>
                                    </div>

                                </div>
                            </div>
                            <!-- <div class="login-separater text-center mb-5"> <span>OR SIGN IN WITH</span>
                                <hr />
                            </div> -->
                            <!-- <div class="list-inline contacts-social text-center">
                                <a href="{{ route('auth.social', 'facebook') }}"
                                    class="list-inline-item bg-facebook text-white border-0 rounded-3"><i
                                        class="bx bxl-facebook"></i></a>
                                <a href="{{ route('auth.social', 'twitter') }}"
                                    class="list-inline-item bg-twitter text-white border-0 rounded-3"><i
                                        class="bx bxl-twitter"></i></a>
                                <a href="{{ route('auth.social', 'google') }}"
                                    class="list-inline-item bg-google text-white border-0 rounded-3"><i
                                        class="bx bxl-google"></i></a>
                                <a href="{{ route('auth.social', 'linkedin') }}"
                                    class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i
                                        class="bx bxl-linkedin"></i></a>
                            </div> -->

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
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text")
                {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				}
                else if ($('#show_hide_password input').attr("type") == "password")
                {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
@endsection