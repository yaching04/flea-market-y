<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCategoryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_category_pivot', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // 同じ商品に同じカテゴリを重複登録できないように
            $table->unique(['item_id', 'category_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_category_pivot');
    }
}
