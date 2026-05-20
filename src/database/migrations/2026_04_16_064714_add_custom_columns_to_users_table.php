<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('postal_code', 8)->nullable()->after('password');
            $table->string('address')->nullable()->after('postal_code');
            $table->string('building')->nullable()->after('address');
            $table->string('profile_image')->nullable()->after('building');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'address', 'building', 'profile_image']);
        });
    }
};
