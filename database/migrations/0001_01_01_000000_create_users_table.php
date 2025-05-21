<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('nickname', 100)->nullable();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->text('biographie')->nullable();
            $table->text('img')->nullable();
            $table->string('google_scholar')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
