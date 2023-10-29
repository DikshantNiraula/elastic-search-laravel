<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use App\BlogPost;

class SearchController extends Controller
{
    protected $client;

    public function __construct()
    {
       $this->client =  ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build(); 
    }

    public function searchPage()
    {
        return view('search_results');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        if(!$this->client->indices()->exists(['index' => 'blogs'])){
            BlogPost::createIndex();
            dd("created");
        }
        $params = [
            'index' => 'blogs',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title', 'content'],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->client->search($params);
        } catch (\Exception $e) {
            // Handle the exception
            return response()->view('error_page', ['message' => 'An error occurred while searching.']);
        }
        return $response;
    }

    public function autoComplete(Request $request)
    {
        $query = $request->input('query');

        $params = [
            'index' => 'blogs',
            'body' => [
                'suggest' => [
                    'suggestions' => [
                        'prefix' => $query,
                        'completion' => [
                            'field' => 'title',
                            'size' => 5
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->client->search($params);
        
        // Extract and format autocomplete suggestions from the Elasticsearch response
        $suggestions = $response['suggest']['suggestions'][0]['options'];
        $suggestions = array_column($suggestions, 'text');
        
        return $suggestions;
        
    }

}
