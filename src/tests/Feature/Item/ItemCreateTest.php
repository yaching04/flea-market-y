<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン状態で商品出品ができる()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);
        $category = Category::create(['name' => 'ファッション']);

        $response = $this->actingAs($user)->post('/sell', [
            'name'          => 'テスト出品商品',
            'price'         => 10000,
            'description'   => 'テストの説明文です。',
            'condition_id'  => $condition->id,
            'categories'    => [$category->id],
            'image'         => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'), // 簡易版
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('items', [
            'name' => 'テスト出品商品',
            'price' => 10000,
        ]);
    }

    /** @test */
    public function 未ログインでは出品画面にアクセスできない()
    {
        $response = $this->get('/sell');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function 商品名が未入力だとエラーになる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'price' => 5000,
        ]);

        $response->assertSessionHasErrors('name');
    }
}
