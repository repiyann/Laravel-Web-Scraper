<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;

class ScraperService
{
    public function scrapeCategories($categoriesUrl)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', $categoriesUrl);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            // Extract specific information from the website
            $categories = $crawler->filter('._category-select a.dropdown-item')->each(function (Crawler $node) {
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
        } catch (\Exception $error) {
            // Render error view with the error message
            return view('error', ['error' => $error->getMessage()]);
        }
    }

    public function scrapeRecipes($baseUrl, $searchQuery = null)
    {
        try {
            // Insert input into query url
            $url = $baseUrl . '/?s=' . urlencode($searchQuery);
            $client = new Client();
            $response = $client->request('GET', $url);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            // Extract specific information from the website
            $recipes = $crawler->filter('._recipe-card')->each(function (Crawler $node) {
                $title = trim($node->filter('h3.card-title a')->text());
                $thumbnail = $node->filter('img[data-src]')->attr('data-src');
                $key = $node->filter('div[data-id]')->attr('data-id');
                $cookTime = trim($node->filter('a[href*="time="] span')->text());
                $difficulty = trim($node->filter('a[href*="difficulty="]')->text());
                $resUrl = $node->filter('h3.card-title a')->attr('href');
                $title = str_replace('Menu', '', $title);
                $keyword = Str::slug($title);

                return [
                    'title' => $title,
                    'thumb' => $thumbnail,
                    'key' => $key,
                    'keyword' => $keyword,
                    'cookTime' => $cookTime,
                    'difficulty' => $difficulty,
                    'resUrl' => $resUrl,
                ];
            });

            return $recipes;
        } catch (\Exception $error) {
            // Render error view with the error message
            return view('error', ['error' => $error->getMessage()]);
        }
    }

    public function scrapeArticle($articleUrl)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', $articleUrl);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            // Extract specific information from the website
            $articles = $crawler->filter('._category-select a.dropdown-item')->each(function (Crawler $node) {
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

            return $articles;
        } catch (\Exception $error) {
            // Render error view with the error message
            return view('error', ['error' => $error->getMessage()]);
        }
    }

    public function scrapeCategoriesAndRecipes($categoriesUrl, $recipesUrl, $searchQuery = null)
    {
        try {
            $categories = $this->scrapeCategories($categoriesUrl);
            $recipes = $this->scrapeRecipes($recipesUrl, $searchQuery);

            return [
                'categories' => $categories,
                'recipes' => $recipes,
            ];
        } catch (\Exception $error) {
            // Render error view with the error message
            return view('error', ['error' => $error->getMessage()]);
        }
    }

    public function recipeDetail($baseUrl, $recipeKey)
    {
        try {
            $recipeUrl = $baseUrl . '/resep/' . $recipeKey;
            $client = new Client();
            $response = $client->request('GET', $recipeUrl);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            // Extract specific information from the website
            $title = trim($crawler->filter('header._section-title h1')->text());
            $thumbnail = $crawler->filter('img[data-src]')->attr('data-src');
            $cookTime = trim($crawler->filter('a[href*="time="] span')->text());
            $difficulty = trim($crawler->filter('a[href*="difficulty="]')->text());
            $writer = trim($crawler->filter('.author')->text());
            $description = trim($crawler->filter('._rich-content p')->text());
            $quantity = $crawler->filter('#portions-value')->text();

            // Extract specific information from the website
            $ingredients = $crawler->filter('._recipe-ingredients .d-flex')->each(function (Crawler $node) {
                $quantityNode = $node->filter('.part');
                $unitNode = $node->filter('.item');

                if ($quantityNode->count() > 0 && $unitNode->count() > 0) {
                    $quantity = trim($quantityNode->text());
                    $unit = trim($unitNode->text());

                    return $quantity . ' ' . $unit;
                }

                return null;
            });

            $steps = $crawler->filter('._recipe-steps .step')->each(function (Crawler $node) {
                $stepsNumberNode = $node->filter('.number-step');
                $stepsDetailNode = $node->filter('.content p');

                if ($stepsNumberNode->count() > 0 && $stepsDetailNode->count() > 0) {
                    $stepsNumber = trim($stepsNumberNode->text());
                    $stepsDetail = trim($stepsDetailNode->text());

                    return $stepsNumber . ' ' . $stepsDetail;
                }

                return null;
            });

            // Remove null value from ingredients because the filter cant find the part and item class
            $ingredients = array_values(array_filter($ingredients));
            
            $recipeData = [
                'title' => $title,
                'thumbnail' => $thumbnail,
                'cookTime' => $cookTime,
                'difficulty' => $difficulty,
                'writer' => $writer,
                'description' => $description,
                'portions' => $quantity,
                'ingredients' => $ingredients,
                'steps' => $steps
            ];

            return $recipeData;
        } catch (\Exception $error) {
            // Render error view with the error message
            return view('error', ['error' => $error->getMessage()]);
        }
    }
}
