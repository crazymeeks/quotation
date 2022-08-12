<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{


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

        Product::whereId($request->id)->delete();

        return redirect()->back()->with('message', 'Product successfully deleted!');
    }

    /**
     * Show add new page
     *
     * @return \Illuminate\View\View
     */
    public function showAddNewPage()
    {
        return view('admin.pages.products.form');
    }
}
