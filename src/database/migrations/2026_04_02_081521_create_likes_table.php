<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'item_id']); // 1ユーザー1商品1いいね
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
