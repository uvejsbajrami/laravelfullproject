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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('HotelID');
            $table->unsignedBigInteger('RoomID');
            $table->date('CheckInDate');
            $table->date('CheckOutDate');
            $table->unsignedInteger('days');

            $table->boolean('status')->default(0);
            $table->timestamps();

            // Define foreign key constraints after the columns
            $table->foreign('UserID',"bookings_user_id_foreign")->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
