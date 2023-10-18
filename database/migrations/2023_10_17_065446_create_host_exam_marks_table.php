<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostExamMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_exam_marks', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->integer('subjects');
            $table->integer('points');
            $table->integer('total_marks');
            $table->mediumText('details');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->foreignId('exam_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('host_exam_marks');
    }
}
