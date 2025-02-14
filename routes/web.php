<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('login');
});

// Route for getting data of product detail //
Route::get('/dashboard', [DashboardController::class, 'product_d'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {

    // Route for adding new product in product table  //

    Route::post('/product_data',[ProductController::class,'product_data']);

    // Route for edit product detail //

    Route::post('/edit_product_data',[ProductController::class,'edit_product_data'])->name('edit_product_d');

    // Route for deleteting particular product //

    Route::delete('/delete_products/{id}', [ProductController::class, 'delete_product']);

 // Route for search in stock movement page
    Route::get('/search_pro',[ProductController::class,'search_pro']);

    // Route for stock movement data of every movement //


    Route::get('/stock_movement', [ProductController::class, 'stock_movement'])->name('stock_movement');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
