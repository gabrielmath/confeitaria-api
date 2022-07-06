<?php

namespace App\Observers;

use App\Jobs\CakeAvailableAllJob;
use App\Models\Cake;
use App\Models\Client;

class CakeObserver
{
    /**
     * Handle the Cake "updated" event.
     *
     * @param \App\Models\Cake $cake
     * @return void
     */
    public function updated(Cake $cake)
    {
        if ($cake->awaitingLists()->count() == 0) {
            $clients = Client::all();

            foreach ($clients as $client) {
                $cake->awaitingLists()->create(['client_id' => $client->id]);
            }
        }

        CakeAvailableAllJob::dispatch($cake);
    }
}
