<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use App\BlogPost;

class SearchController extends Controller
{

    public function searchPage()
    {
        return view('search_results');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $client = ClientBuilder::create()->setHosts(config('database.connections.elasticsearch.hosts'))->build();

        if(!$client->indices()->exists(['index' => 'blogs'])){
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
            $response = $client->search($params);
        } catch (\Exception $e) {
            // Handle the exception
            return response()->view('error_page', ['message' => 'An error occurred while searching.']);
        }
        return $response;
    }

}
