<?php

namespace App\Console\Commands;

use App\Jobs\DefaultCrawling;
use App\Services\CrawlingService;
use Illuminate\Console\Command;

class ProcessCrawling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawling:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get crawling requests and register to pool';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(CrawlingService::class);
        $service->getDefaultCrawlingRequests()->each(function ($request) {
            $request->update(['state_code' => 'processing',]);
            DefaultCrawling::dispatch($request)->onQueue($request->service);
        });
    }
}
