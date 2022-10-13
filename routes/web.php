<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\UnitOfMeasureController;
use App\Http\Controllers\QuotationHistoryController;

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
Route::get('/q', function(){
    return view('templates.quote-pdf');
});

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'postLogin'])->name('admin.post.login');
Route::post('/logout', [LoginController::class, 'postLogout'])->name('admin.post.logout');


Route::group(['middleware' => ['auth.cms', 'action.ability']], function($route){
    $route->group(['prefix' => 'home'], function($route){
        $route->get('/', [HomeController::class, 'getIndex'])->name('home');
    });
    /** Product */
    $route->group(['prefix' => 'products', 'middleware' => ['can.manage.products']], function($route){
        $route->get('/', [ProductController::class, 'index'])->name('product.index');
        $route->post('/', [ProductController::class, 'postSave'])->name('product.save');
        $route->get('/add-new', [ProductController::class, 'showAddNewPage'])->name('product.add.new');
        $route->get('/edit/{uuid}', [ProductController::class, 'editProductPage'])->name('product.edit');
        $route->delete('/', [ProductController::class, 'postDelete'])->name('product.delete');

        $route->get('/datatable', [ProductController::class, 'getDatatable'])->name('product.datatable');
    });

    /** Company */
    $route->group(['prefix' => 'companies'], function($route){
        $route->get('/', [CompanyController::class, 'index'])->name('admin.company.index');
        $route->post('/', [CompanyController::class, 'postSave'])->name('admin.company.post.save');
        $route->delete('/', [CompanyController::class, 'deleteCompany'])->name('admin.company.delete');
        $route->get('/add-new', [CompanyController::class, 'showAddNewPage'])->name('admin.company.add.new');
        $route->get('/edit/{uuid}', [CompanyController::class, 'editCompanyPage'])->name('admin.company.edit');

        $route->post('/validate', [CompanyController::class, 'postValidate'])->name('admin.company.post.validate');

        $route->get('/datatable', [CompanyController::class, 'getDatatable'])->name('admin.company.datatable');
    });

    /** Customer */
    $route->group(['prefix' => 'customers'], function($route){
        $route->get('/', [CustomerController::class, 'index'])->name('admin.customer.index');
        
        $route->get('/datatable', [CustomerController::class, 'getDatatable'])->name('admin.customer.datatable');
    });

    /** Unit of measure */
    $route->group(['prefix' => 'unit-of-measures'], function($route){
        $route->get('/', [UnitOfMeasureController::class, 'index'])->name('admin.uom.index');
        $route->post('/', [UnitOfMeasureController::class, 'postSave'])->name('admin.uom.post.save');
        $route->delete('/', [UnitOfMeasureController::class, 'deleteUom'])->name('admin.uom.delete');
        $route->get('/add-new', [UnitOfMeasureController::class, 'showAddNewPage'])->name('admin.uom.add.new');
        $route->get('/edit/{uuid}', [UnitOfMeasureController::class, 'editCompanyPage'])->name('admin.uom.edit');

        $route->post('/validate', [UnitOfMeasureController::class, 'postValidate'])->name('admin.uom.post.validate');
        $route->get('/datatable', [UnitOfMeasureController::class, 'getDatatable'])->name('admin.uom.datatable');
    });

    /** Role */
    $route->group(['prefix' => 'roles', 'middleware' => ['can.manage.users']], function($route){
        $route->get('/', [RoleController::class, 'getIndex'])->name('admin.role.get.index');
        $route->post('/', [RoleController::class, 'postSave'])->name('admin.role.post.save');
        $route->get('/edit/{uuid}', [RoleController::class, 'editForm'])->name('admin.role.edit');
        $route->get('/new', [RoleController::class, 'displayNewForm'])->name('admin.role.get.new');
        $route->delete('/', [RoleController::class, 'deleteRole'])->name('admin.role.delete');
    });

    /** Orders */
    $route->group(['prefix' => 'orders'], function($route){
        $route->get('/', [OrderController::class, 'index'])->name('admin.orders.get.index');
        $route->get('/datatable', [OrderController::class, 'getDataTable'])->name('admin.orders.get.datatable');
        $route->get('/{uuid}/view', [OrderController::class, 'viewOrder'])->name('admin.orders.get.view');
        $route->put('/', [OrderController::class, 'putPaidOrder'])->name('admin.orders.post.paid');
    });
    /** Quotations */
    $route->group(['prefix' => 'quotations'], function($route){
        $route->get('/', [QuotationController::class, 'index'])->name('admin.quotation.index');
        $route->post('/', [QuotationController::class, 'postSave'])->name('admin.quotation.post.save');
        $route->get('/new', [QuotationController::class, 'displayQuotationForm'])->name('admin.quotation.get.new');
        $route->get('/{uuid}/edit', [QuotationController::class, 'editQuotation'])->name('admin.quotation.get.edit');
        $route->get('/datatable', [QuotationController::class, 'getDataTable'])->name('admin.quotation.get.datatable');
        $route->delete('/', [QuotationController::class, 'delete'])->name('admin.quotation.delete');

        /** Quotation history */
        $route->group(['prefix' => 'histories'], function($route){
            $route->post('/versions', [QuotationHistoryController::class, 'postShowVersions'])->name('admin.quotation.histories.post.show.versions');
        });

        $route->group(['prefix' => 'products'], function($route){
            $route->post('/', [QuotationController::class, 'postAddProduct'])->name('admin.quotation.product.add.post');
            $route->put('/', [QuotationController::class, 'updateQuantity'])->name('admin.quotation.product.update.quantity');
            $route->delete('/', [QuotationController::class, 'deleteItem'])->name('admin.quotation.product.delete');
            /** Edit quote item quantity */
            $route->post('/edit-item-modal', [QuotationController::class, 'postShowEditItemModal'])->name('admin.quotation.product.post.edit.modal');

            /** Convert quote to order */
            $route->post('/convert-to-order', [QuotationController::class, 'postConvertToOrder'])->name('admin.quotation.post.convert.to.order');
        });

        $route->group(['prefix' => 'compute-discount'], function($route){
            /** Compute discount on keyup in discount input */
            $route->post('/', [QuotationController::class, 'postComputeDiscount'])->name('admin.quotation.compute.discount');
        });
        
    });

    $route->group(['prefix' => 'users', 'middleware' => ['can.manage.users']], function($route){
        $route->get('/', [UserController::class, 'index'])->name('admin.users.index');
        $route->post('/', [UserController::class, 'postSave'])->name('admin.users.post.save');
        $route->get('/add-new', [UserController::class, 'displayAddNewForm'])->name('admin.users.get.add.new');
        $route->get('/{uuid}/edit', [UserController::class, 'displayEditForm'])->name('admin.users.get.edit');
        $route->get('/datatable', [UserController::class, 'getDataTable'])->name('admin.users.get.datatable');
        $route->delete('/', [UserController::class, 'delete'])->name('admin.users.delete');
        
    });

});
/** Customer */
Route::group(['prefix' => 'customers'], function($route){
    $route->get('/typeahead', [CustomerController::class, 'typeAhead'])->name('customer.typeahead.get');
});