<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionAtendancesTable extends Migration
{
    /**
     * Run the migrations.
     * 'subject_id'
     * @return void
     */
    public function up()
    {
        Schema::create('session_atendances', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('date_att');
            $table->integer('date_no');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('attend')->default(0);
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
        Schema::dropIfExists('session_atendances');
    }
}
