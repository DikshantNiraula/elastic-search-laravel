<?php

use Illuminate\Support\Facades\Route;
use App\BlogPost;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $words = ['twittwe', 'instagram', 'holland', 'germany', 'paris', 'Php', 'Laravel', 'Elastic Search', 'Taylor', 'React', 'Novice', 'B1', 'Deutse', 'Bread', 'Sausages', 'ich bin', 'Berlin', 'Danke', 'haute'];
        $numPosts = 100;

        for ($i = 1; $i <= $numPosts; $i++) {
            $title = $words[array_rand($words)] . ' ' . $words[array_rand($words)];
            $content = $words[array_rand($words)] . ' ' . $words[array_rand($words)] . ' ' . $words[array_rand($words)];
            
            BlogPost::create([
                'title' => $title,
                'content' => $content,
            ]);
        }

        return response()->json(['message' => 'Posts populated successfully']);
    // return view('welcome');
});

Route::get('/search-page', 'SearchController@searchPage')->name('searchPage');
Route::get('/search', 'SearchController@search')->name('search');
