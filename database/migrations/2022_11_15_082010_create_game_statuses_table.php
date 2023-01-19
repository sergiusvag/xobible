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
        Schema::create('game_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('host_id');
            $table->string('host_name');
            $table->string('host_color');
            $table->bigInteger('host_current_score')->default(0);
            $table->bigInteger('host_current_wrong_score')->default(0);
            $table->bigInteger('host_current_bonus_score')->default(0);
            $table->bigInteger('host_current_total_score')->default(0);
            $table->bigInteger('host_score')->default(0);
            $table->bigInteger('host_wrong_score')->default(0);
            $table->bigInteger('host_bonus_score')->default(0);
            $table->bigInteger('host_total_score')->default(0);
            $table->bigInteger('join_id');
            $table->string('join_name');
            $table->string('join_color');
            $table->bigInteger('join_current_score')->default(0);
            $table->bigInteger('join_current_wrong_score')->default(0);
            $table->bigInteger('join_current_bonus_score')->default(0);
            $table->bigInteger('join_current_total_score')->default(0);
            $table->bigInteger('join_score')->default(0);
            $table->bigInteger('join_wrong_score')->default(0);
            $table->bigInteger('join_bonus_score')->default(0);
            $table->bigInteger('join_total_score')->default(0);
            $table->bigInteger('room_number');
            $table->string('question_category_id');
            $table->bigInteger('current_round')->default(1);
            $table->bigInteger('selected_option')->default(-1);
            $table->enum('current_player', ['host', 'join'])->default('host');
            $table->enum('status', ['in_round', 'in_board', 'in_question', 'in_result', 'in_over'])->default('in_question');
            $table->enum('result', ['none', 'is_correct', 'is_wrong'])->default('none');
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
        Schema::dropIfExists('game_statuses');
    }
};
