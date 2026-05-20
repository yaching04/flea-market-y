<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テストユーザーを作成（出品者として使用）
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テストユーザー',
                'password' => bcrypt('password'),
            ]
        );

        $items = [
            [
                'user_id' => $user->id,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'images/Armani+Mens+Clock.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'images/HDD+Hard+Disk.jpg',
                'condition_id' => 2,
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'images/iLoveIMG+d.jpg',
                'condition_id' => 3,
            ],
            [
                'user_id' => $user->id,
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'images/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4,
            ],
            [
                'user_id' => $user->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'image_path' => 'images/Living+Room+Laptop.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => $user->id,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'images/Music+Mic+4632231.jpg',
                'condition_id' => 2,
            ],
            [
                'user_id' => $user->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'images/Purse+fashion+pocket.jpg',
                'condition_id' => 3,
            ],
            [
                'user_id' => $user->id,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_path' => 'images/Tumbler+souvenir.jpg',
                'condition_id' => 4,
            ],
            [
                'user_id' => $user->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_path' => 'images/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => $user->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'image_path' => 'images/make-set.jpg',
                'condition_id' => 2,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
