<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // ログイン後は必ずトップページに飛ぶ
            return redirect()->route('items.index')
                            ->with('success', 'ログインしました！');
        }

        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません。',
        ])->onlyInput('email');
    }
}
