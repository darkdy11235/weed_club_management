<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('permission_name');
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
