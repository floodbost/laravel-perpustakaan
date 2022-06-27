<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('isbn', 16)
                ->default(null)
                ->nullable()
                ->unique('isbn');

            $table->string('title')
                ->default(null)
                ->nullable();

            $table->string('cover_path')
                ->default(null)
                ->nullable();

            $table->string('author')
                ->default(null)
                ->nullable();

            $table->string('publisher')
                ->default(null)
                ->nullable();

            $table->string('publish_date')
                ->default(null)
                ->nullable();

            $table->string('description')
                ->default(null)
                ->nullable();

            $table->boolean('is_booked')
                ->default(0);

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
        Schema::dropIfExists('books');
    }
}
