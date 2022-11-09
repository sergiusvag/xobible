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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('host_id');
            $table->string('host_name');
            $table->bigInteger('join_id')->nullable();
            $table->string('join_name')->nullable();
            $table->bigInteger('room_number');
            $table->bigInteger('room_key')->nullable();
            $table->enum('status', ['in_room', 'in_game'])->default('in_room');
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
        Schema::dropIfExists('rooms');
    }
};
