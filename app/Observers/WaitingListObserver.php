<?php

namespace App\Observers;

use App\Jobs\CakeAvailableAllJob;
use App\Models\WaitingList;

class WaitingListObserver
{
    /**
     * Handle the AwaitingList "created" event.
     *
     * @param WaitingList $awaitingList
     * @return void
     */
    public function created(WaitingList $awaitingList)
    {
        if ($awaitingList->cake->available_quantity > 0) {
            CakeAvailableAllJob::dispatch($awaitingList->cake);
        }
    }
}
