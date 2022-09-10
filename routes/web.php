<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UnitOfMeasureController;

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

Route::group(['middleware' => ['auth.cms', 'action.ability']], function($route){

    /** Product */
    $route->group(['prefix' => 'product'], function($route){
        $route->post('/', [ProductController::class, 'postSave'])->name('product.save');
        $route->get('/add-new', [ProductController::class, 'showAddNewPage'])->name('product.add.new');
        $route->delete('/delete', [ProductController::class, 'postDelete'])->name('product.delete');
    });

    /** Company */
    $route->group(['prefix' => 'company'], function($route){
        $route->post('/', [CompanyController::class, 'postSave'])->name('admin.company.post.save');
        $route->delete('/', [CompanyController::class, 'deleteCompany'])->name('admin.company.delete');
    });

    /** Unit of measure */
    $route->group(['prefix' => 'unit-of-measure'], function($route){
        $route->post('/', [UnitOfMeasureController::class, 'postSave'])->name('admin.uom.post.save');
        $route->delete('/', [UnitOfMeasureController::class, 'deleteUom'])->name('admin.uom.delete');
    });
});