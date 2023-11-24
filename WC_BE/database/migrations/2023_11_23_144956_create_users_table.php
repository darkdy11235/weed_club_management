<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name');
            $table->smallInteger('age');
            $table->string('gender');
            $table->string('phone');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('salt');
            $table->string('avatar');
            $table->rememberToken();
            $table->string('password_reset_token');
            $table->timestamps(); // Created at and Updated at timestamps
            $table->string('created_by');
            $table->string('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}