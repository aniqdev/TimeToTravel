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
        Schema::create('route_user_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('route_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->tinyInteger('is_favorite')->unsigned()->default(0);
            $table->tinyInteger('is_viewed')->unsigned()->default(0);
            $table->tinyInteger('is_downloaded')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::table('route_user_info', function($table) {
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });

        Schema::table('route_user_info', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_user_info');
    }
};
