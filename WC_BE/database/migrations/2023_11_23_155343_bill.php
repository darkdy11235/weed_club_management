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
        Schema::create('bill', function (Blueprint $table) {
            $table->id('bill_id');
            $table->string('fee_type');
            $table->string('payer');
            $table->decimal('fee', 10, 2);
            $table->timestamps();
            $table->string('created_by');
            $table->date('bill_at');
            $table->string('month');
            $table->string('year');
            $table->text('description')->nullable();
            $table->string('column9')->nullable();
            // Thêm các cột khác tùy thuộc vào yêu cầu cụ thể của bạn
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
};
