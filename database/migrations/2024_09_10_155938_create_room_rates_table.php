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
        Schema::create('room_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('meal_plan_id');
            $table->boolean('is_cancellable');
            $table->decimal('price', 10, 2);
            $table->string('currency'); // Cambiamos decimal a string para el campo currency
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->timestamps();

            // Agregar índice único para evitar duplicados
            $table->unique(['hotel_id', 'room_id', 'meal_plan_id']);

            // Agregar índice para optimizar consultas por hotel_id y room_id
            $table->index(['hotel_id', 'room_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_rates');
    }
};