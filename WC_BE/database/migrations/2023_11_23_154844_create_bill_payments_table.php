<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('bill_payment', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('payment_id');
            $table->decimal('amount');

            $table->foreign('payment_id')
                    ->references('id')
                    ->on('payments')
                    ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bill_payment');
    }
};
