<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Models\City;
use \App\Models\Route;
use \App\Models\Sight;
use \App\Models\RouteReview;
use \App\Models\RouteAudio;
use \App\Models\RouteVideo;
use \App\Models\RouteImage;
use \App\Models\SightAudio;
use \App\Models\SightVideo;
use \App\Models\SightImage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(['email' => 'admin@admin.com'],[
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'email_verified_at' => now(),
            'surname' => 'Adminovich',
            'description' => 'Administrator',
            'role' => 2,
            'status' => 2,
            'rating' => rand(3, 4),
            'reviews_count' => rand(3, 30),
            'routes_count' => rand(3, 30),
            'remember_token' => str()->random(10),
            'avatar' => '/images/user-icon.png',
        ]);
        User::firstOrCreate(['email' => 'stas@admin.com'],[
            'name' => 'Admin',
            'email' => 'stas@admin.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'email_verified_at' => now(),
            'surname' => 'Adminovich',
            'description' => 'Administrator',
            'role' => 2,
            'status' => 2,
            'rating' => rand(3, 4),
            'reviews_count' => rand(3, 30),
            'routes_count' => rand(3, 30),
            'remember_token' => str()->random(10),
            'avatar' => '/images/user-icon.png',
        ]);
        User::firstOrCreate(['email' => 'ssaltovsk1@gmail.com'],[
            'name' => 'Сергей',
            'email' => 'ssaltovsk1@gmail.com',
            'password' => '$2y$10$QylzlN1tMXMBG3SkbusNpO0fVQqtq9tHB2GoAyWw6t9KL/t2I.pUK',
            'email_verified_at' => now(),
            'surname' => 'Солтовски',
            'description' => 'Administrator',
            'role' => 2,
            'status' => 2,
            'rating' => rand(3, 4),
            'reviews_count' => rand(3, 30),
            'routes_count' => rand(3, 30),
            'remember_token' => str()->random(10),
            'avatar' => '/images/user-icon.png',
        ]);
        User::factory(10)->create();

        // City::create([
        //     'city' => 'Москва',
        //     'country' => 'Россия',
        //     'latitude' => '55.7522',
        //     'longitude'=> '37.6156',
        // ]);
        // City::create([
        //     'city' => 'Тверь',
        //     'country' => 'Россия',
        //     'latitude' => '56.8584',
        //     'longitude'=> '35.9006',
        // ]);
        // City::create([
        //     'city' => 'Санкт-Петербург',
        //     'country' => 'Россия',
        //     'latitude' => '59.9386',
        //     'longitude'=> '30.3141',
        // ]);
        // City::factory(10)->create();

        Route::factory(30)->create();

        Sight::factory(30)->create();

        $routes = Route::all();

        for ($i = 1; $i < count($routes); $i++) {
            Route::addOrigin($i);
        }

        foreach ($routes as $route) {
            RouteReview::create([
                'route_id' => $route->id,
                'user_id' => 1,
                'mark' => 5,
                'title' => 'Незабываемая поездка',
                'text' => 'Незабываемая Поездка запомнилась на всю жизнь! Ещё не один раз обратимся сюда!'
            ]);
            RouteReview::create([
                'route_id' => $route->id,
                'user_id' => 1,
                'mark' => 5,
                'title' => 'Было супер',
                'text' => 'Всё очень понравилось! И дети и я остались довольны!'
            ]);

            RouteImage::create([
                'route_id' => $route->id,
                'title' => 'Image 1 title',
                'url' => '/images/examples/Altes_Museum_(Berlin-Mitte).09030059.ajb.jpg',
            ]);
            RouteImage::create([
                'route_id' => $route->id,
                'title' => 'Image 2 title',
                'url' => '/images/examples/fe9724d8fb4da3dd4590353bd771a276.jpg',
            ]);

            RouteVideo::create([
                'route_id' => $route->id,
                'title' => 'Video 1 title',
                'url' => 'https://www.youtube.com/watch?v=_0ZGUXUXwnc',
            ]);
            RouteVideo::create([
                'route_id' => $route->id,
                'title' => 'Video 2 title',
                'url' => 'https://www.youtube.com/watch?v=_0ZGUXUXwnc',
            ]);

            RouteAudio::create([
                'route_id' => $route->id,
                'title' => 'Audio 1 title',
                'url' => '/mp3/track1.mp3',
            ]);
            RouteAudio::create([
                'route_id' => $route->id,
                'title' => 'Audio 2 title',
                'url' => '/mp3/track2.mp3',
            ]);
        } // foreach ($routes as $route)

        $sights = Sight::all();

        foreach ($sights as $sight) {
            
            SightImage::create([
                'sight_id' => $sight->id,
                'title' => 'Image 1 title',
                'url' => '/images/examples/Altes_Museum_(Berlin-Mitte).09030059.ajb.jpg',
            ]);
            SightImage::create([
                'sight_id' => $sight->id,
                'title' => 'Image 2 title',
                'url' => '/images/examples/fe9724d8fb4da3dd4590353bd771a276.jpg',
            ]);

            SightVideo::create([
                'sight_id' => $sight->id,
                'title' => 'Video 1 title',
                'url' => 'https://www.youtube.com/watch?v=_0ZGUXUXwnc',
            ]);
            SightVideo::create([
                'sight_id' => $sight->id,
                'title' => 'Video 2 title',
                'url' => 'https://www.youtube.com/watch?v=_0ZGUXUXwnc',
            ]);

            SightAudio::create([
                'sight_id' => $sight->id,
                'title' => 'Audio 1 title',
                'url' => '/mp3/track1.mp3',
            ]);
            SightAudio::create([
                'sight_id' => $sight->id,
                'title' => 'Audio 2 title',
                'url' => '/mp3/track2.mp3',
            ]);
        }
    }
}
