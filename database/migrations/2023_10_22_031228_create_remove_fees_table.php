<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoveFeesTable extends Migration
{
    /**
     * Run the migrations.
     * ['','','','','','','','','','',''];
     * @return void
     */
    public function up()
    {
        Schema::create('remove_fees', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('fee_name');
            $table->mediumText('reason');
            $table->string('action')->default('not yet');
            $table->integer('actionable_id')->default(0);
            $table->decimal('amount');
            $table->decimal('paid_amount');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('fee_id')->constrained();
            $table->foreignId('fee_payment_id');
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
        Schema::dropIfExists('remove_fees');
    }
}
