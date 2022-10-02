<?php

namespace App\Http\Controllers;

use stdClass;
use Exception;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\QuoteCode;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\QuoteProduct;
use App\Models\OrderProduct;
use App\Models\QuotationHistory;
use App\Models\QuotationProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\QuotationHistoryProduct;
use App\Repository\QuotationRepository;
use App\Http\Requests\QuotationRequest;

class QuotationController extends Controller
{

    /** @var array */
    protected $items = [];
    
    public function index()
    {
        return view('admin.pages.quotation.index');
    }

    /**
     * Get data and display in datatable
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Repository\QuotationRepository $quotationRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataTable(Request $request, QuotationRepository $quotationRepository)
    {
        $data = $quotationRepository->getDatatableData($request);
        
        return $data;
    }

    /**
     * Display quotation form
     *
     * @param \App\Models\Product $product
     * @param \App\Models\QuoteCode $quoteCode
     * 
     * @return \Illuminate\View\View
     */
    public function displayQuotationForm(Product $product, QuoteCode $quoteCode)
    {
        $products = $product->active()->get();
        $quoteCode = $quoteCode->first();
        $quotation = new Quotation();
        
        if ($quoteCode === null || !session()->has('quote_code')) {
            $quoteCode = QuoteCode::updateOrCreate([
                'id' => 1
            ], ['code' => generate_string(15, true)]);
        }

        session()->put('quote_code', $quoteCode->code);

        $quoteProducts = $this->getQuoteProducts();
        
        return view('admin.pages.quotation.form', [
            'code' => $quoteCode->code,
            'products' => $products,
            'quoteProducts' => $quoteProducts,
            'quotation' => $quotation
        ]);
    }

    /**
     * Display edit form
     *
     * @param string $uuid
     * 
     * @return \Illuminate\View\View
     */
    public function editQuotation(string $uuid)
    {
        $quotation = Quotation::with(['customer'])->whereUuid($uuid)->first();
        $products = Product::active()->get();
        $productQuotations = $this->getRestructuredProductQuotation($quotation);
        
        return view('admin.pages.quotation.form', [
            'code' => $quotation->code,
            'products' => $products,
            'quoteProducts' => json_decode(json_encode($productQuotations)),
            'quotation' => $quotation,
            'discount' => $quotation->percent_discount,
        ]);
    }

    /**
     * Delete quotation
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $quotation = Quotation::whereCode($request->code)
                             ->whereStatus(Quotation::PENDING)
                             ->first();
        
        if ($quotation) {
            QuotationProduct::where('quotation_id', $quotation->id)->delete();
    
            $quotationHistory = QuotationHistory::whereCode($request->code)->first();
            if ($quotationHistory) {
                QuotationHistoryProduct::where('quotation_history_id', $quotationHistory->id)->delete();
                $quotationHistory->delete();
            }
            
            $quotation->delete();
    
            return response()->json([
                'message' => 'Quotation has been deleted.'
            ]);
        }

        return response()->json([
            'message' => 'Quotation not found.'
        ], 404);
    }

    /**
     * Get product quotations
     *
     * @param \App\Models\Quotation $quotation
     * 
     * @return array
     */
    protected function getRestructuredProductQuotation(Quotation $quotation)
    {
        $quoteProducts = QuotationProduct::where('quotation_id', $quotation->id)->get();

        $data = [];
        foreach($quoteProducts as $quoteProduct){
            $data[] = $this->reStructureQuoteProduct($quoteProduct);
        }

        return $data;
    }

    /**
     * Structure quotation product
     *
     * @param \App\Models\QuotationProduct $quoteProduct
     * 
     * @return array
     */
    protected function reStructureQuoteProduct(QuotationProduct $quoteProduct)
    {
        return [
            'name' => $quoteProduct->product_name,
            'cost' => $quoteProduct->price,
            'quote_product_quantity' => $quoteProduct->quantity,
            'quote_product_id' => $quoteProduct->uuid,
        ];
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
        
        if (!$request->has('id')) {
            $quoteProduct = QuoteProduct::count();
            if ($quoteProduct <= 0) {
                return response()->json(['message' => 'Please add product!'], 403);
            }
        }
        
        try {
            $this->saveQuotation($request);
            $request->session()->forget('quote_code');
            return response()->json(['message' => 'Quotation successfully saved.']);
        } catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(['message' => 'Oops! Something went wrong.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Save quotation
     *
     * @param \App\Http\Requests\QuotationRequest|\Illuminate\Http\Request $request
     * 
     * @return \App\Models\Quotation
     */
    protected function saveQuotation($request)
    {
        $quoteCode = $this->getQuoteCode($request);

        $customer = $this->getCustomer($request);
        $quotation = DB::transaction(function() use ($request, $customer, $quoteCode) {
            if ($request->has('id')) {
                $quote = Quotation::find($request->id);
                $quote->customer_id = $customer->id;
                $quote->user_id = $request->user->id;
                $quote->percent_discount = $request->discount;
                $quote->save();
            } else {
                $quote = Quotation::create([
                    'customer_id' => $customer->id,
                    'user_id' => $request->user->id,
                    'uuid' => generateUuid(),
                    'code' => $quoteCode->code,
                    'percent_discount' => $request->discount,
                    'status' => $request->has('status') ? $request->status : Quotation::PENDING,
                ]);
    
                $this->createQuotation($quote, $request);
            }
            return $quote;
        });
        
        QuoteProduct::truncate();

        return $quotation;
    }

    /**
     * Get quotation code
     *
     * @param \App\Http\Requests\QuotationRequest|\Illuminate\Http\Request $request
     * 
     * @return mixed
     */
    protected function getQuoteCode($request)
    {
        if ($request->has('id')) {
            $quotation = Quotation::find($request->id);
        } else {
            $quotation = QuoteCode::first();
        }
        return $quotation;
    }

    /**
     * Create actual quotation
     *
     * @param \App\Models\Quotation $quote
     * 
     * @return void
     */
    private function createQuotation(Quotation $quote)
    {
        
        $data = [];
        $items = QuoteProduct::get();
        
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
                'created_at' => now()->__toString(),
                'updated_at' => now()->__toString(),
            ];
        }
        $this->items = $data;
        if (count($data) > 0) {
            QuotationProduct::insert($data);

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
     * @param \App\Models\QuoteProduct $item
     *
     * @todo move this to repository
     * 
     * @return \stdClass
     */
    protected function getProduct(QuoteProduct $item)
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
     * @param \App\Http\Requests\QuotationRequest|\Illuminate\Http\Request $request
     * 
     * @return \App\Models\Customer
     */
    protected function getCustomer($request)
    {
        $customer = null;
        if ($request->has('customer_id')) {
            $customer = Customer::find($request->customer_id);
        } elseif ($request->has('id')) {
            // editing existing customer quote
            $customer = Customer::where('customer_name', $request->customer)->first();
        }
        
        if ($customer === null) {
            $customer = Customer::create([
                'uuid' => generateUuid(),
                'customer_name' => strtoupper($request->customer),
                'address' => strtoupper($request->address),
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
            $quotation = new stdClass();
            $quotation->status = null;
            $html = $this->getQuoteHtml($request, $quotation);

            return response()->json(['html' => $html]);
        }

        return response()->json(['message' => 'Product could not be found.'], 400);
    }

    /**
     * Get products of a quotation
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getQuoteProducts()
    {
        $quoteProducts = DB::table('products')
                               ->select(
                                'products.*',
                                'quote_products.id as quote_product_id',
                                'quote_products.quantity as quote_product_quantity'
                               )
                               ->join('quote_products', 'products.id', '=', 'quote_products.product_id')
                               ->get();

        return $quoteProducts;
    }

    /**
     * Get quote html tempalte
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Quotation|\stdClass $quotation
     * 
     * @return string
     */
    protected function getQuoteHtml(Request $request, $quotation = null)
    {
        $quoteProducts = $this->getQuoteProducts();
        if ($quotation instanceof Quotation) {
            $quoteProducts = json_decode(json_encode($this->getRestructuredProductQuotation($quotation)));
            
        }

        $html = view('admin.pages.quotation.quote-details-products', [
            'quoteProducts' => $quoteProducts,
            'discount' => $request->has('discount') ? $request->discount : 0,
            'quotation' => $quotation,
        ])->render();
        
        return $html;
    }

    /**
     * When user input discount quotation discount
     * send ajax request to backend to update
     * quotation being displayed
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function postComputeDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required'
        ]);

        $html = $this->getQuoteHtml($request);

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Show edit item modal
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postShowEditItemModal(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $quoteProduct = QuoteProduct::find($request->id);

        if ($quoteProduct === null) {
            // we are on edit existing quotation, therefore
            // find quotation products in quotation_products table
            $quoteProduct = QuotationProduct::whereUuid($request->id)->first();
        }

        $html = view('admin.pages.quotation.modal.edit-quote-item-body', ['quoteProduct' => $quoteProduct])->render();

        return response()->json([
            'html' => $html
        ]);
        
    }

    /**
     * Update item quantity
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required'
        ]);

        $quotation = new stdClass();
        $quotation->status = null;
        
        $quoteProduct = QuoteProduct::whereId($request->id)->first();
        
        if ($quoteProduct === null) {
            // we are on edit existing quotation, therefore
            // find quotation products in quotation_products table
            $quoteProduct = QuotationProduct::whereUuid($request->id)->first();
            $quoteProduct->quantity = $request->quantity;
            $quoteProduct->updated_at = now()->__toString();
            $quoteProduct->save();

            /** @todo too many database fetching here... refactor */
            $quotation = Quotation::whereId($quoteProduct->quotation_id)->first();
            
            // update quotation history
            $quotationHistory = QuotationHistory::whereCode($quotation->code)->first();
            // get latest quotation history product
            $quotationHistoryProduct = QuotationHistoryProduct::where('quotation_history_id', $quotationHistory->id)
                                                              ->orderBy('id', 'desc')
                                                              ->limit(1)
                                                              ->first();
            $item = collect($quotationHistoryProduct)->toArray();
            $item['version'] = $item['version'] + 1;
            $item['quantity'] = $request->quantity;
            $item['created_at'] = now()->__toString();
            $item['updated_at'] = now()->__toString();
            /** @todo what to do if the cost of the product changes? */
            QuotationHistoryProduct::create($item);
            
        } else {
            $quoteProduct->quantity = $request->quantity;
            $quoteProduct->updated_at = now()->__toString();
            $quoteProduct->save();
        }

        $html = $this->getQuoteHtml($request, $quotation);

        return response()->json([
            'html' => $html
        ]);

    }

    /**
     * Delete quote item
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItem(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        QuoteProduct::find($request->id)->delete();

        $html = $this->getQuoteHtml($request);

        return response()->json([
            'html' => $html
        ]);

    }

    /**
     * Convert quote to order
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postConvertToOrder(Request $request)
    {
        try {
            
            if ($request->has('id')) {
                DB::transaction(function() use ($request) {
                    $quotation = Quotation::find($request->id);
                    $quotation->status = Quotation::CONVERTED;
                    $quotation->save();
                    $quotationProducts = QuotationProduct::where('quotation_id', $quotation->id)->get();
                    $items = collect($quotationProducts)->toArray();
                    $this->createOrder($quotation, $items);
                });
            } else {
                
                $request->merge([
                    'status' => Quotation::CONVERTED
                ]);
                DB::transaction(function() use ($request) {
                    $quotation = $this->saveQuotation($request);
                    
                    $this->createOrder($quotation, $this->items);
                });
                
            }
            return response()->json(['message' => 'Order has been created!']);        
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Oops! Something went wrong!', 'error' => $e->getMessage()], 500);        
        }
    }

    /**
     * Create order from quotation
     *
     * @param \App\Models\Quotation $quotation
     * @param array $items
     * 
     * @return void
     */
    protected function createOrder(Quotation $quotation, array $items)
    {

        $total = 0;
        $items = array_map(function($item) use (&$total) {
            unset($item['quotation_id'], $item['uuid']);
            $item['final_price'] = $item['price'] * $item['quantity'];
            $total += $item['final_price'];
            return $item;
        }, $items);
        
        $order = Order::create([
            'customer_id' => $quotation->customer_id,
            'user_id' => $quotation->user_id,
            'uuid' => generateUuid(),
            'reference_no' => $quotation->code,
            'grand_total' => $total,
            'percent_discount' => $quotation->percent_discount,
            'status' => Order::PENDING,
        ]);

        $items = array_map(function($item) use ($order) {
            $item['order_id'] = $order->id;
            $item['uuid'] = generateUuid();
            $item['created_at'] = now()->__toString();
            $item['updated_at'] = now()->__toString();
            return $item;
        }, $items);

        OrderProduct::insert($items);
    }
}
