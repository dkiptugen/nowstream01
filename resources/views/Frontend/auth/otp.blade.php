@extends('Frontend.auth.layout')

@section('content')
<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
    <div class="container">
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col mx-auto">
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
                                <img src="{{ asset('logo1.png') }}" width="90" alt="" />
                            </div>
                            <div class="form-body">
                                <form class="row g-3" method="post" action="{{ route('otp_verification') }}"
                                    id="otp-form">
                                    @csrf
                                    <div class="col-12 text-center">
                                        <h4>Verify Your Account</h4>
                                        <p>We are sending an OTP to validate your mobile number, Hang on!</p>
                                        <div class="otp-inputs d-flex justify-content-center mt-2">
                                            <input type="text" class="otp-input" maxlength="1" required>
                                            <input type="text" class="otp-input" maxlength="1" required>
                                            <input type="text" class="otp-input" maxlength="1" required>
                                            <input type="text" class="otp-input" maxlength="1" required>
                                            <input type="text" class="otp-input" maxlength="1" required>
                                            <input type="text" class="otp-input" maxlength="1" required>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="OtpCode" placeholder="Enter Otp Code"
                                        name="otp">
                                    <input type="hidden" name="user_id" value="{{ $id }}">
                                    <div class="col-12 text-center">
                                        <div class="d-grid mb-3">
                                            <button type="submit" class="btn btn-default text-white">Submit</button>
                                        </div>
                                        <small class="text text-danger">A SMS has been sent to
                                            {{ $phone }}
                                        </small> <br>
                                        <div class="mt-3">
                                            Didn't Receive SMS? 
                                            <!-- <button type="button" class="btn btn-link p-0"
                                                onClick="window.location.href=window.location.href">Resend OTP</button> -->
                                                <a href="{{ route('phoneresend') }}" class="btn btn-link p-0" >Resend OTP</a>
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
    .otp-inputs input {
        width: 40px;
        height: 40px;
        font-size: 24px;
        text-align: center;
        margin: 0 5px;
        border: 1px solid #898989;
        border-radius: 5px;
    }
</style>

<script>
    // $(document).on('click', '#page_reload', function (e) {
    //     e.preventDefault();
    //     window.location.href = window.location.href;
    // });

    document.addEventListener('DOMContentLoaded', (event) => {
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHiddenInput = document.getElementById('OtpCode');
        const otpForm = document.getElementById('otp-form');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                if (value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                updateOtpHiddenInput();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                const pasteData = e.clipboardData.getData('text');
                if (pasteData.length === otpInputs.length) {
                    otpInputs.forEach((input, idx) => {
                        input.value = pasteData[idx];
                    });
                    e.preventDefault();
                    updateOtpHiddenInput();
                }
            });
        });

        otpForm.addEventListener('submit', (e) => {
            updateOtpHiddenInput();
        });

        function updateOtpHiddenInput() {
            let otpValue = '';
            otpInputs.forEach(input => {
                otpValue += input.value;
            });
            otpHiddenInput.value = otpValue;
        }
    });
</script>

@endsection

@section('footer')
@endsection