<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $longitude = $this->faker->longitude(37.5, 37.8);
        $latitude =  $this->faker->latitude(55.6, 55.8);
        return [
            'city' => strtolower($this->faker->city()),
            'country' => strtolower($this->faker->country()),
            'location' => \DB::raw("ST_GeomFromText('POINT($longitude $latitude)')"),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
}
