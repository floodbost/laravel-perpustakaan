<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->integer('book_id');
            $table->integer('user_id');

            $table->enum('status', ['booked', 'release'])
                ->default('booked')
                ->nullable();

            $table->dateTime('book_at')
                ->default(null)
                ->nullable();

            $table->dateTime('release_at')
                ->default(null)
                ->nullable();

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
        Schema::dropIfExists('bookings');
    }
}
