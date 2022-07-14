<?php

namespace Database\Seeders;

use App\Models\Sight;
use Illuminate\Database\Seeder;

class SightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 30; $i++) {
            Sight::factory(1)->create();
        }
    }
}
