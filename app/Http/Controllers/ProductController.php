<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\UnitOfMeasure;
use Illuminate\Support\Facades\DB;
use App\Repository\ProductRepository;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{


    public function index(ProductRepository $productRepository)
    {
        return view('admin.pages.products.index');
    }

    /**
     * Get product data for datatable
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatable(Request $request, ProductRepository $productRepository)
    {
        $data = $productRepository->getDatatableData($request);

        return $data;
    }


    /**
     * Create/update product
     *
     * @param \App\Http\Requests\ProductRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(ProductRequest $request)
    {
        try {
            $message = 'Product successfully created!';

            DB::transaction(function() use($request, &$message){

                $values = array_merge(createUUIDAttribute(), $request->except(['_token']));
                
                if ($request->has('id') && !is_null($request->id)) {
                    unset($values['uuid']);
                    $message = 'Product successfully updated!';
                }

                Product::updateOrCreate(
                    ['id' => $request->id],
                    $values
                );
            });

            return response()->json([
                'message' => $message
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => 'Error creating new product!',
                'error' => $e->getMessage()
            ], 400);
        }
        
    }

    /**
     * Delete product
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(Request $request)
    {
        
        $request->validate([
            'id' => 'required|numeric'
        ]);

        $product = Product::find($request->id);
        $product->deleted_at = now()->__toString();
        $product->name = sprintf("%s.%s", $product->name, uniqid());
        $product->save();
        return response()->json(['message' => 'Product successfully deleted!']);
    }

    /**
     * Show add new page
     *
     * @return \Illuminate\View\View
     */
    public function showAddNewPage()
    {
        $units = UnitOfMeasure::all();
        $companies = Company::all();
        $product = new Product();
        return view('admin.pages.products.form', [
            'units' => $units,
            'companies' => $companies,
            'product' => $product,
        ]);
    }

    /**
     * Display edit product page
     *
     * @param string $uuid
     * 
     * @return \Illuminate\View\View
     */
    public function editProductPage(string $uuid)
    {

        $units = UnitOfMeasure::all();
        $companies = Company::all();
        $product = Product::whereUuid($uuid)->first();
        return view('admin.pages.products.form', [
            'units' => $units,
            'companies' => $companies,
            'product' => $product,
        ]);
    }
}
