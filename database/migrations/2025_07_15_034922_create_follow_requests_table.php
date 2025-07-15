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
        Schema::create('follow_requests', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger("followedId");
            $table->unsignedBigInteger("userId_request");
            $table->timestamps();

            $table->foreign('followedId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('userId_request')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_requests');
    }
};
