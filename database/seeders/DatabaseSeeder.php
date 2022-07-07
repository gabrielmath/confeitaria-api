<?php

namespace Database\Seeders;

use App\Jobs\CakeAvailableAllJob;
use App\Models\Cake;
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
        \Artisan::call('cache:clear');
        \Artisan::call('queue:clear redis');
        \Artisan::output();

        if (Cake::all()->isEmpty()) {
            Cake::factory(20)->create();
        }

        $cake = Cake::first();
        for ($i = 0; $i < 2000; $i++) {
            $cake->waitingLists()->create([
                'name'  => "Usuário {$i}",
                'email' => "user{$i}@email.test"
            ]);
        }

        CakeAvailableAllJob::dispatch($cake);
    }
}
