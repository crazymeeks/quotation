<?php

namespace App\Http\Controllers;

use stdClass;
use Exception;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\QuoteProduct;
use App\Models\QuotationHistory;
use App\Models\QuotationProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\QuotationHistoryProduct;
use App\Http\Requests\QuotationRequest;

class QuotationController extends Controller
{
    
    /**
     * Display quotation form
     *
     * @param \App\Models\Product $product
     * 
     * @return \Illuminate\View\View
     */
    public function displayQuotationForm(Product $product)
    {
        $products = $product->active()->get();
        $quoteProducts = $this->getQuoteProducts();
        return view('admin.pages.quotation.form', [
            'products' => $products,
            'quoteProducts' => $quoteProducts,
            'discount' => 0,
        ]);
    }


    /**
     * Save quotation
     *
     * @param \App\Http\Requests\QuotationRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(QuotationRequest $request)
    {
        try {
            $customer = $this->getCustomer($request);
            DB::transaction(function() use ($request, $customer) {
                $quote = Quotation::create([
                    'customer_id' => $customer->id,
                    'user_id' => $request->user->id,
                    'uuid' => generateUuid(),
                    'code' => $request->code,
                    'percent_discount' => $request->discount,
                ]);

                $this->createQuotation($quote, $request);
            });
            return response()->json(['message' => 'Quotation successfully saved.']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Oops! Something went wrong.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create actual quotation
     *
     * @param \App\Models\Quotation $quote
     * @param \App\Http\Requests\QuotationRequest $request
     * 
     * @return void
     */
    private function createQuotation(Quotation $quote, QuotationRequest $request)
    {
        $items = json_decode(json_encode($request->items));
        $data = [];
        foreach($items as $item){
            $product = $this->getProduct($item);
            
            $data[] = [
                'uuid' => generateUuid(),
                'quotation_id' => $quote->id,
                'product_uuid' => $product->uuid,
                'unit_of_measure' => $product->uom,
                'company' => $product->company_name,
                'product_name' => $product->name,
                'manufacturer_part_number' => $product->manufacturer_part_number,
                'purchase_description' => $product->purchase_description,
                'sales_description' => $product->sales_description,
                'price' => $product->cost,
                'quantity' => $item->quantity,
            ];
        }
        if (count($data) > 0) {
            $quoteProduct = QuotationProduct::insert($data);

            // create quotation history
            $quoteHistory = QuotationHistory::create([
                'code' => $quote->code
            ]);

            $quoteHistoryProduct = QuotationHistoryProduct::where('quotation_history_id', $quoteHistory->id)
                                                          ->orderBy('id', 'desc')
                                                          ->first();
            $version = 1;
            
            if ($quoteHistoryProduct) {
                $version = $quoteHistoryProduct->version + 1;
            }

            foreach($data as &$d){
                $d['quotation_history_id'] = $quoteHistory->id;
                $d['version'] = $version;
                $d['uuid'] = generateUuid();
                unset($d['quotation_id']);
                unset($d);
            }

            QuotationHistoryProduct::insert($data);
            
        }
    }

    /**
     * Get product
     * 
     * @param \stdClass $item
     *
     * @todo move this to repository
     * 
     * @return \stdClass
     */
    protected function getProduct(stdClass $item)
    {
        $product = DB::table('products')
                     ->select(
                        'products.*',
                        'unit_of_measures.title as uom',
                        'companies.name as company_name',
                        'companies.address as company_address'
                     )
                     ->leftJoin('unit_of_measures', 'products.unit_of_measure_id', '=', 'unit_of_measures.id')
                     ->leftJoin('companies', 'products.company_id', '=', 'companies.id')
                     ->where('products.id', $item->product_id)
                     ->first();
        return $product;
    }

    /**
     * Create or get existing customer
     *
     * @param \App\Http\Requests\QuotationRequest $request
     * 
     * @return \App\Models\Customer
     */
    protected function getCustomer(QuotationRequest $request)
    {
        if ($request->has('customer_id')) {
            $customer = Customer::find($request->customer_id);
        } else {
            $customer = Customer::create([
                'uuid' => generateUuid(),
                'customer_name' => $request->customer,
                'address' => $request->address,
                'contact_no' => $request->contact_no,
            ]);
        }

        return $customer;
    }


    /**
     * Add product to a quotation upon clicking "Add to Quote" from modal
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddProduct(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'quantity' => 'required|numeric',
        ]);


        $product = Product::whereId($request->product)->first();

        if ($product) {
            QuoteProduct::updateOrCreate(['product_id' => $product->id], [
                'quantity' => $request->quantity
            ]);

            $quoteProducts = $this->getQuoteProducts();

            $html = view('admin.pages.quotation.quote-details-products', [
                'quoteProducts' => $quoteProducts,
                'discount' => $request->has('discount') ? $request->discount : 0,
            ])->render();

            return response()->json(['html' => $html]);
        }

        return response()->json(['message' => 'Product could not be found.'], 400);
    }

    protected function getQuoteProducts()
    {
        $quoteProducts = DB::table('products')
                               ->select(
                                'products.*',
                                'quote_products.id as quote_product_id',
                                'quote_products.quantity as quote_product_quantity'
                               )
                               ->leftJoin('quote_products', 'products.id', '=', 'quote_products.product_id')
                               ->get();

        return $quoteProducts;
    }
}
