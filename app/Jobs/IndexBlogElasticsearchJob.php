<?php

namespace App\Jobs;

use App\BlogPost;
use Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Elasticsearch\ClientBuilder;

class IndexBlogElasticsearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $blog;

    public function __construct($blog)
    {
        $this->blog = $blog;
    }

    /**
     * Execute the job.
     * @param Client $client
     */
    public function handle()
    {
        $client = ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();

        $params = [
            'index' => 'blogs',
            'id' => $this->blog->id,
            'body' => [
                'title' => $this->blog->title,
                'content' => $this->blog->content,
                'published_at' => $this->blog->published_at
            ]
        ];

        $client->index($params);
    }
}
