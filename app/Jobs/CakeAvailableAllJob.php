<?php

namespace App\Jobs;

use App\Models\Cake;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CakeAvailableAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Cake $cake;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cake $cake)
    {
        $this->cake = $cake;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->cake->awaitingLists->each(
            fn($awaitingList) => CakeAvailableClientJob::dispatch($awaitingList)
        );
    }
}
