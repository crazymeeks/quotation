<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuotationController;
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
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'postLogin'])->name('admin.post.login');


Route::group(['middleware' => ['auth.cms', 'action.ability']], function($route){
    $route->group(['prefix' => 'home'], function($route){
        $route->get('/', [HomeController::class, 'getIndex'])->name('home');
    });
    /** Product */
    $route->group(['prefix' => 'products'], function($route){
        $route->get('/', [ProductController::class, 'index'])->name('product.index');
        $route->post('/', [ProductController::class, 'postSave'])->name('product.save');
        $route->get('/add-new', [ProductController::class, 'showAddNewPage'])->name('product.add.new');
        $route->get('/edit/{uuid}', [ProductController::class, 'editProductPage'])->name('product.edit');
        $route->delete('/', [ProductController::class, 'postDelete'])->name('product.delete');

        $route->get('/datatable', [ProductController::class, 'getDatatable'])->name('product.datatable');
    });

    /** Company */
    $route->group(['prefix' => 'companies'], function($route){
        $route->post('/', [CompanyController::class, 'postSave'])->name('admin.company.post.save');
        $route->delete('/', [CompanyController::class, 'deleteCompany'])->name('admin.company.delete');
    });

    /** Unit of measure */
    $route->group(['prefix' => 'unit-of-measure'], function($route){
        $route->post('/', [UnitOfMeasureController::class, 'postSave'])->name('admin.uom.post.save');
        $route->delete('/', [UnitOfMeasureController::class, 'deleteUom'])->name('admin.uom.delete');
    });

    /** Role */
    $route->group(['prefix' => 'roles'], function($route){
        $route->get('/', [RoleController::class, 'getIndex'])->name('admin.role.get.index');
        $route->post('/', [RoleController::class, 'postSave'])->name('admin.role.post.save');
        $route->get('/edit/{uuid}', [RoleController::class, 'editForm'])->name('admin.role.edit');
        $route->get('/new', [RoleController::class, 'displayNewForm'])->name('admin.role.get.new');
        $route->delete('/', [RoleController::class, 'deleteRole'])->name('admin.role.delete');
    });

    /** Quotation */
    $route->group(['prefix' => 'quotation'], function($route){
        $route->post('/', [QuotationController::class, 'postSave'])->name('admin.quotation.post.save');
        $route->get('/new', [QuotationController::class, 'displayQuotationForm'])->name('admin.quotation.get.new');
        
        $route->group(['prefix' => 'product'], function($route){
            $route->post('/', [QuotationController::class, 'postAddProduct'])->name('admin.quotation.product.add.post');
        });

        $route->group(['prefix' => 'compute-discount'], function($route){
            /** Compute discount on keyup in discount input */
            $route->post('/', [QuotationController::class, 'postComputeDiscount'])->name('admin.quotation.compute.discount');
        });
    });

});
/** Customer */
Route::group(['prefix' => 'customers'], function($route){
    $route->get('/typeahead', [CustomerController::class, 'typeAhead'])->name('customer.typeahead.get');
});