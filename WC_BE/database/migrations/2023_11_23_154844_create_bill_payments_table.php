<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('bill_payments', function (Blueprint $table) {
            // $table->id(); // Auto-incrementing primary key
            $table->foreignId('bill_id');
            $table->foreignId('payment_id');
            $table->decimal('amount');
            $table->timestamps();

            $table->unique(['bill_id', 'payment_id']);

            $table->foreign('payment_id')
                    ->references('id')
                    ->on('payments')
                    ->onDelete('cascade');
            $table->foreign('bill_id')
                    ->references('id')
                    ->on('bills')
                    ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bill_payments');
    }
};
