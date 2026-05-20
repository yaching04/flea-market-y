<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストケース1: 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('お名前を入力してください。', session('errors')->first('name'));
    }

    /**
     * テストケース2: メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('メールアドレスを入力してください。', session('errors')->first('email'));
    }

    /**
     * テストケース3: パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertEquals('パスワードを入力してください。', session('errors')->first('password'));
    }

    /**
     * テストケース4: パスワードが7文字以下の場合、バリデーションメッセージが表示される
     */
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertEquals('パスワードは8文字以上で入力してください。', session('errors')->first('password'));
    }

    /**
     * テストケース5: パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
     */
    public function test_password_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertEquals('パスワードと一致しません。', session('errors')->first('password'));
    }

    /**
     * テストケース6: すべての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
     */
    public function test_successful_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // ユーザーが登録されたことを確認
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // プロフィール設定画面にリダイレクトされることを確認
        $response->assertRedirect(route('mypage.profile.edit'));
    }
}
