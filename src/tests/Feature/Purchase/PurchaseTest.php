<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン状態で商品購入画面にアクセスできる()
    {
        $buyer = User::factory()->create(); // UserFactoryはデフォルトで存在する
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        $item = Item::create([
            'user_id'      => $seller->id,
            'name'         => '購入テスト商品',
            'price'        => 8000,
            'description'  => 'テスト商品説明',
            'condition_id' => $condition->id,
        ]);

        $response = $this->actingAs($buyer)->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('購入確認');
    }

    /** @test */
    public function 未ログインでは購入画面にアクセスできない()
    {
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        $item = Item::create([
            'user_id'      => $seller->id,
            'name'         => '未ログイン商品',
            'price'        => 5000,
            'description'  => 'テスト',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get("/purchase/{$item->id}");

        $response->assertRedirect('/login');
    }
}
