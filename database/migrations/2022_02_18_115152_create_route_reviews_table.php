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
        Schema::create('route_reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('route_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->tinyInteger('mark')->unsigned()->default(0);
            $table->tinyInteger('approved')->unsigned()->default(0);
            $table->string('title')->nullable();
            $table->string('text')->nullable();
            $table->timestamps();
        });

        Schema::table('route_reviews', function($table) {
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_reviews');
    }
};
