<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('pay_account')->nullable();
            $table->string('pay_method');
            $table->string('account_name');
            $table->integer('account_number');
            $table->decimal('amount_money',10,2);
            $table->string('description');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade'); // Delete related permissions if a role is deleted
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
