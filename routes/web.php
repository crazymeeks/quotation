<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;

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

Route::post('/login', [LoginController::class, 'postLogin'])->name('admin.post.login');

Route::group(['prefix' => 'home'], function($route){
    $route->get('/', [HomeController::class, 'getIndex'])->name('home');
});

Route::group(['prefix' => 'product', 'middleware' => 'auth.cms'], function($route){
    $route->post('/', [ProductController::class, 'postSave'])->name('product.save');
    $route->get('/add-new', [ProductController::class, 'showAddNewPage'])->name('product.add.new');
    $route->post('/delete', [ProductController::class, 'postDelete'])->name('product.delete');
});