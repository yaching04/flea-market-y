<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // バリデーション
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'メールアドレスを入力してください。',
            'email.email'       => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        // 認証試行
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('items.index')
                            ->with('success', 'ログインしました！');
        }

        // 認証失敗（メールまたはパスワードが間違っている場合）
        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません。',
        ])->onlyInput('email');
    }
}
