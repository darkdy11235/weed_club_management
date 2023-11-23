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
        Schema::create('payment', function (Blueprint $table) {
            $table->id      ("payment_id"); // Auto-incrementing primary key
            $table->string  ('payment_intent_id')->nullable();
            $table->string  ('pay_account')->nullable();
            $table->string  ('pay_method');
            $table->string  ('account_name');
            $table->timestamps  ('create_ad');
            $table->integer ('account_number');
            $table->decimal ('amount_money',10,2);
            $table->string  ('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
};
