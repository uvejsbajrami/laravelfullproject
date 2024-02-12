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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->float('price');
            $table->char('contact',20);
            $table->timestamps();
        });
        Schema::table('rooms', function (Blueprint $table) {
         $table->foreign('HotelID')->references('id')->on('hotels')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('HotelID')->references('id')->on('hotels')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
