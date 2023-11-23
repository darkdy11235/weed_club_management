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
        Schema::create('bill_payment', function (Blueprint $table) {
            $table->id          ('bill_payment_id'); // Auto-incrementing primary key
            $table->foreignId('payment_id');
            $table->foreign('payment_id')->references('id')->on('payment')->onDelete('cascade');
            $table->decimal     ('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_payment');
    }
};
