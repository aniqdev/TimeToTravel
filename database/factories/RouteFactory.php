<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\User;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Route::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = $this->faker->randomElement(User::pluck('id')->toArray());
        $city_id = $this->faker->randomElement(City::pluck('id')->toArray());
        return [
            'preview_url' => asset('/images/examples/fe9724d8fb4da3dd4590353bd771a276.jpg'),
            'name' => $this->faker->word(),
            'description' => $this->faker->text(255),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'old_price' => $this->faker->randomFloat(2, 5000, 10000),
            'latitude' => $this->faker->latitude(55.6, 55.8),
            'longitude' => $this->faker->longitude(37.5, 37.8),
            'length' => $this->faker->randomFloat(2, 1, 20),
            'transport' => ['undefined', 'walk', 'car', 'public_transport'][rand(0, 3)],
            'language' => $this->faker->numberBetween(0, 1),
            'user_id' => $user_id,
            'city_id' => $city_id,
            'status' => ['undefined', 'pending', 'accepted', 'declined'][rand(0, 3)],
        ];
    }
}
