<?php

namespace App\Http\Controllers;

use App\Services\ScraperService;
use Illuminate\Http\Request;

class ScrapeController extends Controller
{
    private $scraperService;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function index()
    {
        $baseUrl = 'https://www.masakapahariini.com/';
        $categoriesUrl = $baseUrl . 'resep/';
        $recipesUrl = $baseUrl . '/?s=';
        
        $data = $this->scraperService->scrapeCategoriesAndRecipes($categoriesUrl, $recipesUrl);

        return view('scraped-both', $data);
    }

    public function searchRecipe(Request $request)
    {
        $baseUrl = 'https://www.masakapahariini.com/';
        $searchQuery = $request->input('q');

        $recipes = $this->scraperService->scrapeRecipes($baseUrl, $searchQuery);

        return view('scraped-data', ['recipes' => $recipes, 'searchQuery' => $searchQuery]);
    }

    public function searchCategory(Request $request)
    {
        $baseUrl = 'https://www.masakapahariini.com/';
        $categoriesUrl = $baseUrl . 'resep/';

        $categories = $this->scraperService->scrapeCategories($categoriesUrl);

        return view('scraped-category', ['categories' => $categories]);
    }
}
