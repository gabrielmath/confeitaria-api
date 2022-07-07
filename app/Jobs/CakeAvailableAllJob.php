<?php

namespace App\Jobs;

use App\Models\Cake;
use App\Notifications\CakeAvailableNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CakeAvailableAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Cake $cake)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->cake->waitingLists->each(fn($waitingList) => $waitingList->notify(new CakeAvailableNotification()));
//        $this->cake->awaitingLists->each(fn($awaitingList) => CakeAvailableClientJob::dispatch($awaitingList));
    }
}
