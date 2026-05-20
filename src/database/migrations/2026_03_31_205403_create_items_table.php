<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 出品者
            $table->string('name');
            $table->string('brand')->nullable();
            $table->text('description');
            $table->integer('price');
            $table->foreignId('condition_id')->constrained()->cascadeOnDelete();
            $table->string('image_path')->nullable();
            $table->timestamp('sold_at')->nullable();   // 売却済み時刻（フラグ代わり）
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
        Schema::dropIfExists('items');
    }
}
