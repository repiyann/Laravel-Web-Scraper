<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeController extends Controller
{
    public function searchRecipe(Request $request)
    {
        try {
            $url = 'https://www.masakapahariini.com/';
            $client = new Client();

            // Insert input into query url
            $searchQuery = $request->input('q');
            $url .= ($searchQuery) ? "?s=" . urlencode($searchQuery) : '';
            $response = $client->request('GET', $url);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            // Extract specific information from the website
            $recipes = $crawler->filter('._recipe-card')->each(function (Crawler $node, $i) {
                $title = trim($node->filter('h3.card-title a')->text());
                $thumbnail = $node->filter('img[data-src]')->attr('data-src');
                $key = $node->filter('div[data-id]')->attr('data-id'); // possibly in use
                $cookTime = trim($node->filter('a[href*="time="] span')->text());
                $difficulty = trim($node->filter('a[href*="difficulty="]')->text());

                return [
                    'title' => $title,
                    'thumb' => $thumbnail,
                    'key' => $key,
                    'cookTime' => $cookTime,
                    'difficulty' => $difficulty,
                ];
            });

            return view('scraped-data', ['recipes' => $recipes, 'searchQuery' => $searchQuery]);
        } catch (\Exception $e) {
            // Log or display the error
            dd($e->getMessage());
        }
    }

    public function searchByCategory(Request $request)
    {
        try {
            $url = 'https://www.masakapahariini.com/';
            $client = new Client();

            // Insert input into query url
            $searchQuery = $request->input('q');
            $url .= ($searchQuery) ? "resep/" . urlencode($searchQuery) : '';
            $response = $client->request('GET', $url);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            // Extract specific information from the website
            $recipes = $crawler->filter('._recipe-card')->each(function (Crawler $node, $i) {
                $title = trim($node->filter('h3.card-title a')->text());
                $thumbnail = $node->filter('.thumbnail img')->attr('data-src'); // still null not receiving any data
                $key = $node->filter('div[data-id]')->attr('data-id'); // possibly in use
                $cookTime = trim($node->filter('a[href*="time="] span')->text());
                $difficulty = trim($node->filter('a[href*="difficulty="]')->text());

                return [
                    'title' => $title,
                    'thumb' => $thumbnail,
                    'key' => $key,
                    'cookTime' => $cookTime,
                    'difficulty' => $difficulty,
                ];
            });

            return view('scraped-data', ['recipes' => $recipes, 'searchQuery' => $searchQuery]);
        } catch (\Exception $e) {
            // Log or display the error
            dd($e->getMessage());
        }
    }
}
