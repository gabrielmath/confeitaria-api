<?php

namespace App\Observers;

use App\Jobs\CakeAvailableAllJob;
use App\Models\Cake;

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
        if ($cake->available_quantity > 0 && $cake->waitingLists()->exists()) {
            CakeAvailableAllJob::dispatch($cake);
        }
    }
}
