@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
    <div class="register-container">

        <h1 class="register-title">会員登録</h1>

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="form-group">
                <label>ユーザー名</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>メールアドレス</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group password-field">
                <label>パスワード</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-input" required>
                    <i class="toggle-password fa-regular fa-eye" id="togglePassword"></i>
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group password-field">
                <label>確認用パスワード</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input"
                        required>
                    <i class="toggle-password fa-regular fa-eye" id="togglePasswordConfirm"></i>
                </div>
                @if ($errors->has('password_confirmation') || $errors->has('password'))
                    <p class="error-message">
                        {{ $errors->first('password_confirmation') ?: $errors->first('password') }}</p>
                @endif
            </div>

            <button type="submit" class="register-btn">登録する</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>

    </div>

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
            setupToggle('password_confirmation', 'togglePasswordConfirm');
        });
    </script>
@endsection
