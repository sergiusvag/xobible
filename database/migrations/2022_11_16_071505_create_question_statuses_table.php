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
        Schema::create('question_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('room_number');
            $table->bigInteger('round_number');
            $table->bigInteger('0_field_question_id');
            $table->enum('0_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('1_field_question_id');
            $table->enum('1_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('2_field_question_id');
            $table->enum('2_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('3_field_question_id');
            $table->enum('3_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('4_field_question_id');
            $table->enum('4_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('5_field_question_id');
            $table->enum('5_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('6_field_question_id');
            $table->enum('6_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('7_field_question_id');
            $table->enum('7_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->bigInteger('8_field_question_id');
            $table->enum('8_field_question_status', ['none', 'host_answered', 'join_answered'])->default('none');
            $table->enum('selected_field', [
                    'none', '0_field', '1_field', '2_field', '3_field', '4_field',
                    '5_field', '6_field', '7_field', '8_field', '9_field'
                ])->default('none');
            $table->bigInteger('game_status_id');
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
        Schema::dropIfExists('question_statuses');
    }
};
