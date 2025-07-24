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
        Schema::create('interest_advertise', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertise_id');   // 広告ID（FK）
            $table->unsignedBigInteger('interest_id');    // 興味ID（FK）
            $table->timestamps();                         // created_at, updated_at

            // 外部キー制約
            $table->foreign('advertise_id')->references('id')->on('advertises')->onDelete('cascade');
            $table->foreign('interest_id')->references('id')->on('interests')->onDelete('cascade');

            // 同じ組み合わせの重複を防ぐ
            $table->unique(['advertise_id', 'interest_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_advertise');
    }
};