<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeController extends Controller
{
    public function scrape(Request $request)
    {
        try {
            $url = 'https://www.masakapahariini.com/';
            $client = new Client();
            $searchQuery = $request->input('q');

            // If a search query is provided, update the URL
            $url .= ($searchQuery) ? "?s=" . urlencode($searchQuery) : '';

            $response = $client->request('GET', $url);
            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);

            // Extract specific information from the website
            $recipes = $crawler->filter('._recipe-card')->each(function (Crawler $node, $i) {
                $title = trim($node->filter('h3.card-title a')->text());
                $thumbnail = $node->filter('.thumbnail img')->attr('data-src'); // masih null
                $key = $node->filter('div[data-id]')->attr('data-id');
                $times = trim($node->filter('a[href*="time="] span')->text());

                // The serving information seems to be within the recipe-features container
                // $servingNode = $node->filter('.recipe-serving span'); // belum dapet
                // $serving = ($servingNode->count() > 0) ? trim($servingNode->text()) : ''; // belum dapet

                $difficulty = trim($node->filter('a[href*="difficulty="]')->text());

                return [
                    'title' => $title,
                    'thumb' => $thumbnail,
                    'key' => $key,
                    'times' => $times,
                    // 'serving' => $serving,
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
