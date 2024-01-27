<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;

class ScraperService
{
    public function scrapeCategories($categoriesUrl)
    {
        $client = new Client();
        $response = $client->request('GET', $categoriesUrl);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        // Extract specific information from the website
        $categories = $crawler->filter('._category-select a.dropdown-item')->each(function (Crawler $node, $i) {
            $title = trim($node->text());
            $resUrl = $node->attr('href');
            $title = str_replace('Menu', '', $title);
            $key = Str::slug($title);

            return [
                'title' => $title,
                'resUrl' => $resUrl,
                'key' => $key,
            ];
        });

        return $categories;
    }

    public function scrapeRecipes($baseUrl, $searchQuery = null)
    {
        // Insert input into query url
        $url = $baseUrl . '/?s=' . urlencode($searchQuery);
        $client = new Client();
        $response = $client->request('GET', $url);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);
            
        // Extract specific information from the website
        $recipes = $crawler->filter('._recipe-card')->each(function (Crawler $node, $i) {
            $title = trim($node->filter('h3.card-title a')->text());
            $thumbnail = $node->filter('img[data-src]')->attr('data-src');
            $key = $node->filter('div[data-id]')->attr('data-id');
            $cookTime = trim($node->filter('a[href*="time="] span')->text());
            $difficulty = trim($node->filter('a[href*="difficulty="]')->text());
            $resUrl = $node->filter('h3.card-title a')->attr('href');

            return [
                'title' => $title,
                'thumb' => $thumbnail,
                'key' => $key,
                'cookTime' => $cookTime,
                'difficulty' => $difficulty,
                'resUrl' => $resUrl,
            ];
        });

        return $recipes;
    }

    public function scrapeCategoriesAndRecipes($categoriesUrl, $recipesUrl, $searchQuery = null)
    {
        $categories = $this->scrapeCategories($categoriesUrl);
        $recipes = $this->scrapeRecipes($recipesUrl, $searchQuery);

        return [
            'categories' => $categories,
            'recipes' => $recipes,
        ];
    }
}
