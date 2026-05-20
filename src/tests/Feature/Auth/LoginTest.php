<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 正常にログインができる()
    {
        // 事前にユーザーを作成
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');  // トップページにリダイレクト
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function メールアドレスが未入力だとエラーになる()
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function パスワードが未入力だとエラーになる()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function 誤った情報でログインするとエラーになる()
    {
        $response = $this->post('/login', [
            'email'    => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
    }
}
