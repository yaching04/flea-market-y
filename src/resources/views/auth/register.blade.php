@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
    <div class="register-container">

        <!-- 1. フォームコンテンツ部分 -->

        <form method="POST" action="{{ route('register') }}" novalidate>
            <div class="auth-content">
                <h1 class="register-title">会員登録</h1>

                @csrf
                <!-- ユーザー名 -->
                <div class="form-group">
                    <label>ユーザー名</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- メールアドレス -->
                <div class="form-group">
                    <label>メールアドレス</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" class="form-input" placeholder="example.@gmail.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- パスワード -->
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

                <!-- 確認用パスワード -->
                <div class="form-group password-field">
                    <label>確認用パスワード</label>
                    <div class="input-wrapper">
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                            <i class="toggle-password fa-regular fa-eye" id="togglePasswordConfirm"></i>
                        </div>
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

                <!-- 登録ボタン -->
                <div class="register-button">
                    <button type="submit" class="register-btn">登録する</button>
                </div>

        </form>
    </div>

        <!-- ログインリンク -->
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
