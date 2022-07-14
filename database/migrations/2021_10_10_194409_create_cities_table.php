<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\City;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city')->nullable(false);
            $table->string('country')->nullable(false);
            $table->point('location')->nullable(true);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('active')->default(0);
        });

        City::create([
            'city' => 'Неизвестно',
            'country' => 'Неизвестно',
            'active' => '1',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
