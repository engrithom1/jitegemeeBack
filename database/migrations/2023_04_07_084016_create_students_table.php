<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('phone')->default('no phone');
            $table->string('photo')->default('staff.png');
            $table->string('home_address');
            $table->string('email')->default('no email');
            $table->string('accademic_year');
            $table->string('regist_year');
            $table->string('nationality');
            $table->string('birth_date');
            $table->string('behavior')->default('no comment');
            $table->string('transfer_reason')->default('no transfer');
            $table->string('relation_to');
            $table->string('school_from')->default('no transfer');
            $table->string('entry');
            $table->string('admission');
            $table->string('hearth');
            $table->string('index_no');
            $table->string('prem_no');
            $table->foreignId('student_status_id')->constrained();
            $table->foreignId('parent_status_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->foreignId('parent_id')->constrained();
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
        Schema::dropIfExists('students');
    }
}
