@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
    <div class="login-container">

        <h1 class="login-title">
            <div class="input-wrapper">
                @error('login')
                    <div class="login-error">{{ $message }}</div>
                @enderror
                ログイン
            </div>
        </h1>



        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="form-group">
                <label>メールアドレス</label>
                <div class="input-wrapper">
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group password-field">
                <label>パスワード</label>
                <div class="input-wrapper">
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-input" required>
                        <i class="toggle-password fa-regular fa-eye" id="togglePassword"></i>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="login-btn">ログインする</button>
        </form>

        <div class="register-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>

    </div>

    <!-- スクリプトはそのまま -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupToggle(inputId, toggleId) {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);
                if (!input || !toggle) return;

                toggle.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        toggle.classList.remove('fa-eye');
                        toggle.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        toggle.classList.remove('fa-eye-slash');
                        toggle.classList.add('fa-eye');
                    }
                });
            }
            setupToggle('password', 'togglePassword');
        });
    </script>
@endsection
