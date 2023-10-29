<?php

namespace App\Jobs;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveBlogElasticsearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $blogId;

    public function __construct($blogId)
    {
        $this->blogId = $blogId;
    }

    public function handle(Client $client)
    {
        $params = [
            'index' => 'blogs-v1',
            'id' => $this->blogId,
        ];

        try {
            $client->delete($params);
        } catch (Missing404Exception $exception) {
            // Already deleted..
        }
    }
}
