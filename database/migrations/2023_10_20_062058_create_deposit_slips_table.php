<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_slips', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->decimal('amount',17,2);
            $table->string('deposit_code');
            $table->mediumText('description');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('deposit_slips');
    }
}
