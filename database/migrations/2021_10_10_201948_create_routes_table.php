<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('preview_url')->nullable(true);
            $table->string('name')->nullable();
            $table->text('description')->nullable(true);
            $table->double('price', 8, 2)->default(0);
            $table->double('old_price', 8, 2)->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->float('length')->nullable(false)->default(0);
            $table->integer('duration')->nullable(false)->default(0);
            $table->string('transport')->default('');
            $table->string('language')->default('ru');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->string('status')->default('draft');
            $table->point('origin')->nullable();
            $table->text('line_points')->default('[]');
            $table->timestamps();
        });

        Schema::table('routes', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
