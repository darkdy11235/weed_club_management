<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id'); // Auto-incrementing primary key
            $table->foreignId('permission_id');
            $table->timestamps(); // Created at and Updated at timestamps

            // Foreign key relationship with the permissions table
            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permissions')
                  ->onDelete('cascade'); // Delete related permissions if a role is deleted
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
