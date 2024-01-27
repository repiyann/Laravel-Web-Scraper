<?php

namespace App\Http\Controllers;

use App\Services\ScraperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ScrapeController extends Controller
{
    private $scraperService;
    private $baseUrl;

    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
        $this->baseUrl = App::make('base_url');
    }

    public function index()
    {
        $categoriesUrl = $this->baseUrl . 'resep/';
        $recipesUrl = $this->baseUrl . '/?s=';
        $data = $this->scraperService->scrapeCategoriesAndRecipes($categoriesUrl, $recipesUrl);

        return view('scraped-both', $data);
    }

    public function searchRecipe(Request $request)
    {
        $searchQuery = $request->input('q');
        $recipes = $this->scraperService->scrapeRecipes($this->baseUrl, $searchQuery);

        return view('scraped-data', ['recipes' => $recipes, 'searchQuery' => $searchQuery]);
    }

    public function searchCategory()
    {
        $categoriesUrl = $this->baseUrl . 'resep/';
        $categories = $this->scraperService->scrapeCategories($categoriesUrl);

        return view('scraped-category', ['categories' => $categories]);
    }

    public function searchArticle()
    {
        $articleUrl = $this->baseUrl . 'artikel/';
        $articles = $this->scraperService->scrapeCategories($articleUrl);

        return view('scraped-article', ['articles' => $articles]);
    }

    public function recipeDetail($recipeKey)
    {
        $recipeData = $this->scraperService->recipeDetail($this->baseUrl, $recipeKey);

        return view('recipe-detail', ['recipeData' => $recipeData]);
    }
}
