<?php

namespace App\Jobs;

use GuzzleHttp\Client;

class InvalidateCache extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
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
        try {
            $client=new Client();
            $client->delete('192.168.1.19:8000/cache/invalidate/'.$id);
        } catch (ConnectException $e) {
            error_log(GuzzleHttp\Psr7\Message::toString($e->getRequest()));
        }
    }
}
