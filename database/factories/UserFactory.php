<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $role = $this->faker->randomElement([0, 1]);
        $status = 2;
        if ($role == 0) {
            $status = $this->faker->randomElement([1, 3]);
        }

        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
            'email_verified_at' => now(),
            'description' => $this->faker->text(255),
            'role' => $role,
            'status' => $status,
            'rating' => rand(3, 4),
            'reviews_count' => rand(3, 30),
            'routes_count' => rand(3, 30),
            'remember_token' => str()->random(10),
            'avatar' => '/images/user-icon.png',
        ];
    }

}
