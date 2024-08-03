<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('type');
            $table->string('status');
            $table->string('email')->unique();
            $table->string('google_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
