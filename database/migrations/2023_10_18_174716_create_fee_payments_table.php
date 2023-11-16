<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('valid_to');
            $table->decimal('amount',17,2);
            $table->decimal('paid_amount',17,2);
            $table->foreignId('student_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('fee_id')->constrained();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_payments');
    }
}
