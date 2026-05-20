<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => '',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
        //$response->assertSee('お名前を入力してください。');
    }

    /** @test */
    public function メールアドレスが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => '',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSee('メールアドレスを入力してください。');
    }

    /** @test */
    public function パスワードが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSee('パスワードを入力してください。');
    }

    /** @test */
    public function パスワードが7文字以下の場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSee('パスワードは8文字以上で入力してください。');
    }

    /** @test */
    public function パスワードが確認用パスワードと一致しない場合バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSee('パスワードと一致しません。');
    }

    /** @test */
    public function すべての項目が入力されている場合会員登録されプロフィール設定画面に遷移される()
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/mypage/profile');   // 設計書指定の遷移先
        $this->assertDatabaseHas('users', [
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
    }
}
