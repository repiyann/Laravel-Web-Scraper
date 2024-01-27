<?php

use App\Http\Controllers\ScrapeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search', [ScrapeController::class, 'searchRecipe'])->name('searchRecipe');
Route::get('/recipe/category', [ScrapeController::class, 'searchCategory'])->name('searchCategory');
Route::get('/article/category', [ScrapeController::class, 'searchArticle'])->name('searchArticle');
Route::get('/recipe-detail/{recipeKey}', [ScrapeController::class, 'recipeDetail'])->name('recipeDetail');
