<?php

namespace App\Jobs;

use App\Models\Request;
use App\Services\Client\Factory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DefaultCrawling implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Request $request)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = app(Factory::class)->make();

        try {
            $response = $client->request(
                'GET',
                $this->request->url,
                $this->request->requestOptions
            );

            $this->request->update([
                'response' => $response->getBody()->getContents(),
                'state_code' => 'request-completed',
                'responded_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            $this->request->update([
                'state_code' => 'request-failed',
                'responded_at' => Carbon::now()
            ]);
        }
    }
}
