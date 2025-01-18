<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CallUrlCollect implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = route('collect.main');
        $response = Http::get($url);

        if ($response->failed()) {
            Log::error('Failed to call URL: ' . $url);
        } else {
            Log::info('URL called successfully: ' . $url);
        }
    }
}
