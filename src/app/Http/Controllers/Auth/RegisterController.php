<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 登録後すぐにログイン
        auth()->login($user);

        // 設計書通り：初回登録後はプロフィール編集画面へ
        return redirect()->route('mypage.profile.edit')->with('success', '会員登録が完了しました。プロフィールを設定してください。');
    }
}
