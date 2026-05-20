<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                  ->cascadeOnDelete();   // 購入者

            $table->foreignId('item_id')
                ->constrained()
                  ->cascadeOnDelete();   // 購入された商品

            $table->enum('payment_method', ['convenience', 'card']); // 支払い方法

            $table->string('postal_code', 8);   // 郵便番号
            $table->string('address');          // 住所
            $table->string('building')->nullable(); // 建物名

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
        Schema::dropIfExists('purchases');
    }
}
