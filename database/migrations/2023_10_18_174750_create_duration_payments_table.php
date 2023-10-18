<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDurationPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duration_payments', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->decimal('amount');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('fee_id')->constrained();
            $table->foreignId('fee_payment_id')->constrained();
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
        Schema::dropIfExists('duration_payments');
    }
}
