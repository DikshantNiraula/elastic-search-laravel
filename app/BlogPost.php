<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Elasticsearch\ClientBuilder;

class BlogPost extends Model
{
    protected $fillable = ['title','content'];
    public static function createIndex()
    {
        $client = ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();
        $params = [
            'index' => 'blogs',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'title' => ['type' => 'completion'],
                        'content' => ['type' => 'text'],
                        'published_at' => ['type' => 'date'],
                    ],
                ]
            ],
        ];

        try {
            $client->indices()->create($params);
        } catch (\Exception $e) {
            dd($e);
            // Handle the exception (e.g., log the error or display a user-friendly message)
        }
    }

    public static function indexToElasticsearch($post)
    {
        $client = ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();
        $params = [
            'index' => 'blogs',
            'id' => $post->id,
            'body' => [
                'title' => $post->title,
                'content' => $post->content,
                'published_at' => $post->published_at,
            ],
        ];

        try {
            $res = $client->index($params);
        } catch (\Exception $e) {
            dd($e);
            // Handle the exception
        }
    }

    


}
