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
        Schema::create('User', function (Blueprint $table) {
            $table->id();
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('Email')->unique();
            $table->string('PasswordHash');
            $table->string('Role')->nullable();
            $table->string('Department')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->string('ProfileImageUrl')->nullable();
            $table->dateTime('LastSeenDate')->nullable();
            $table->timestamp('CreatedDate')->nullable();
            $table->timestamp('UpdatedDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('User');
    }
};
