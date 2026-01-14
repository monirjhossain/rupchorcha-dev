@extends('layouts.auth')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e2eafc 100%);">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-white text-center border-0 pt-4 pb-0">
                <img src="https://startbootstrap.com/assets/img/sb-admin-2.svg" alt="Logo" style="height: 60px;">
                <h2 class="h4 text-dark mt-3 mb-2 font-weight-bold">Sign in to Rupchorcha</h2>
                <p class="text-muted mb-0">Enter your credentials to access your account</p>
            </div>
            <div class="card-body px-5 py-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div class="text-center mb-4">
                    <button type="button" class="btn btn-outline-primary mr-2" id="showEmailLogin">Login with Email</button>
                    <button type="button" class="btn btn-outline-success" id="showOtpLogin">Login with OTP</button>
                </div>
                <form method="POST" action="{{ url('/login') }}" class="user" id="emailLoginForm">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email" class="font-weight-bold">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" required autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="font-weight-bold">Password</label>
                        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">Login</button>
                </form>
                <form method="POST" action="{{ url('/api/verify-otp') }}" class="user" id="otpLoginForm" style="display:none;">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="otp_email" class="font-weight-bold">Email Address</label>
                        <input type="email" id="otp_email" name="email" class="form-control form-control-lg" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group mb-3">
                        <button type="button" class="btn btn-info btn-block" id="sendOtpBtn">Send OTP</button>
                    </div>
                    <div class="form-group mb-3" id="otpField" style="display:none;">
                        <label for="otp" class="font-weight-bold">Enter OTP</label>
                        <input type="text" id="otp" name="otp" class="form-control form-control-lg" placeholder="Enter OTP" maxlength="6">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block mt-3" id="verifyOtpBtn" style="display:none;">Login with OTP</button>
                </form>
                <script>
                document.getElementById('showEmailLogin').onclick = function() {
                    document.getElementById('emailLoginForm').style.display = 'block';
                    document.getElementById('otpLoginForm').style.display = 'none';
                };
                document.getElementById('showOtpLogin').onclick = function() {
                    document.getElementById('emailLoginForm').style.display = 'none';
                    document.getElementById('otpLoginForm').style.display = 'block';
                };
                document.getElementById('sendOtpBtn').onclick = function(e) {
                    e.preventDefault();
                    var email = document.getElementById('otp_email').value;
                    fetch('/api/send-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            document.getElementById('otpField').style.display = 'block';
                            document.getElementById('verifyOtpBtn').style.display = 'block';
                            alert('OTP sent to your email.');
                        } else {
                            alert(data.message);
                        }
                    });
                };
                </script>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                    <a class="small" href="{{ route('register') }}">Create an Account</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
