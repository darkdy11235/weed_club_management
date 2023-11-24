<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('fee_type');
            $table->string('payer');
            $table->decimal('fee', 10, 2);
            $table->timestamps();
            $table->string('created_by');
            $table->date('bill_at');
            $table->string('month');
            $table->string('year');
            $table->text('description')->nullable();

           
        });
    }

    public function down()
    {
        Schema::dropIfExists('bills');
    }
};
