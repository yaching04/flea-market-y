<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Condition;
use App\Models\User;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未ログインでも商品一覧画面が表示される()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('おすすめ');
    }

    /** @test */
    public function 商品が正しく一覧で表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品1',
            'price' => 5000,
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テスト商品1');
        $response->assertSee('5,000');
    }

    /** @test */
    public function 自分が出品した商品は一覧に表示されない()
    {
        $loginUser = User::factory()->create();
        $otherUser = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        // 自分が出品した商品
        Item::create([
            'user_id' => $loginUser->id,
            'name' => '自分の商品',
            'price' => 3000,
            'description' => '自分の出品',
            'condition_id' => $condition->id,
        ]);

        // 他人が出品した商品
        $otherItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'price' => 4000,
            'description' => '他人の出品',
            'condition_id' => $condition->id,
        ]);

        $response = $this->actingAs($loginUser)->get('/');

        $response->assertDontSee('自分の商品');     // 重要！
        $response->assertSee('他人の商品');
    }

    /** @test */
    public function 購入済み商品にはSOLD_OUTが表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Item::create([
            'user_id' => $user->id,
            'name' => '売却済み商品',
            'price' => 1000,
            'brand' => '',
            'description' => 'テスト',
            'condition_id' => $condition->id,
            'sold_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertSee('SOLD OUT');
    }

    /** @test */
    public function キーワード検索ができる()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Item::create([
            'user_id' => $user->id,
            'name' => '検索テスト商品',
            'price' => 3000,
            'brand' => '',
            'description' => 'テスト説明',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get('/?keyword=検索テスト');

        $response->assertSee('検索テスト商品');
    }
}
