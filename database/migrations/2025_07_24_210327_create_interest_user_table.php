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
        Schema::create('interest_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      // FK
            $table->unsignedBigInteger('interest_id');  // FK
            $table->timestamps(); // created_at も含まれる
    
            // 外部キー制約（オプションだけど推奨）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('interest_id')->references('id')->on('interests')->onDelete('cascade');
            
            // 同じ組み合わせを重複登録させないようにする
            $table->unique(['user_id', 'interest_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_user');
    }
};