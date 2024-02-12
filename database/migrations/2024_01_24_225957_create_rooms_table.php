<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('HotelID');
            $table->unsignedInteger('room_number');
            $table->boolean('status')->default(0);
            $table->unsignedInteger('capacity');
            $table->timestamps();

        });
        Schema::table('bookings', function (Blueprint $table) {
         $table->foreign('RoomID')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
