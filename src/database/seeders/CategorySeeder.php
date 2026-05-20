<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'ファッション'],
            ['name' => 'アクセサリー'],
            ['name' => '家電'],
            ['name' => '家具'],
            ['name' => '食品'],
            ['name' => '本・漫画'],
            ['name' => 'スポーツ'],
            ['name' => '美容・コスメ'],
            ['name' => 'アウトドア'],
            ['name' => 'その他'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
