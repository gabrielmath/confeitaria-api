<?php

namespace Database\Seeders;

use App\Models\Cake;
use App\Models\CakeAwaitingList;
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
            Client::factory(2000)->create();
        }

        if (Cake::all()->isEmpty()) {
            Cake::factory(10)->create();
        }

        if (CakeAwaitingList::all()->isEmpty()) {
            $cake = Cake::find(1);
            $clients = Client::all();

            foreach ($clients as $client) {
                $cake->awaitingLists()->create(['client_id' => $client->id]);
            }
        }
    }
}
