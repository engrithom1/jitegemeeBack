<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('initial');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('phone');
            $table->string('about_me');
            $table->string('photo')->default('staff.png');
            $table->string('home_address');
            $table->string('email');
            $table->string('index_no');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('role_id')->constrained();
            $table->foreignId('department_id')->constrained();
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
        Schema::dropIfExists('staff');
    }
}
