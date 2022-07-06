<?php

namespace Database\Seeders;

use App\Models\Cake;
use App\Models\Client;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (Client::all()->isEmpty()) {
            Client::factory(5000)->create();
        }

        if (Cake::all()->isEmpty()) {
            Cake::factory(10)->create();
        }
    }
}
