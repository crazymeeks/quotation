<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\PullOutItem;
use Illuminate\Http\Request;
use App\Models\PullOutRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PullOutRequestNumber;
use App\Models\PullOutRequestProduct;

class PullOutRequestController extends Controller
{
    

    /**
     * Display index page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.pages.pull-outs.index');
    }

    /**
     * Get data and display in datatable
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataTable(Request $request)
    {
        $limit = $request->length;
        $offset = $request->start;
        
        $order = $request->order;
        $columns = $request->columns;

        $column_idx = $order[0]['column'];
        $column = $columns[$column_idx]['data'];

        // $column = $this->mapColumn($column);
        
        $orderDirection = $order[0]['dir'];

        $total = PullOutRequest::where(function($query) use ($request) {
                                    $search = $request->search['value'];
                                    if (!empty($search)) {
                                        return $query->where('por_no', 'like', '%' . $search . '%')
                                                     ->orWhere('type', 'like', '%' . $search . '%')
                                                     ->orWhere('business_name', 'like', '%' . $search . '%')
                                                     ->orWhere('contact_person', 'like', '%' . $search . '%')
                                                     ->orWhere('requested_by', 'like', '%' . $search . '%')
                                                     ->orWhere('approved_by', 'like', '%' . $search . '%')
                                                     ->orWhere('returned_by', 'like', '%' . $search . '%')
                                                     ->orWhere('counter_checked_by', 'like', '%' . $search . '%');
                                    }
                                })
                               ->count();


        $pullOuts = PullOutRequest::where(function($query) use ($request) {
                                    $search = $request->search['value'];
                                    if (!empty($search)) {
                                        return $query->where('por_no', 'like', '%' . $search . '%')
                                                        ->orWhere('type', 'like', '%' . $search . '%')
                                                        ->orWhere('business_name', 'like', '%' . $search . '%')
                                                        ->orWhere('contact_person', 'like', '%' . $search . '%')
                                                        ->orWhere('requested_by', 'like', '%' . $search . '%')
                                                        ->orWhere('approved_by', 'like', '%' . $search . '%')
                                                        ->orWhere('returned_by', 'like', '%' . $search . '%')
                                                        ->orWhere('counter_checked_by', 'like', '%' . $search . '%');
                                    }
                                })
                                ->limit($limit)
                                ->offset($offset)
                                ->orderBy($column, $orderDirection)
                                ->get();
        
        $pullOuts = $pullOuts->toArray();

        $totalRecords = $total;
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $pullOuts,
        ];

        return $data;
    }

    /**
     * Display form
     *
     * @return \Illuminate\View\View
     */
    public function displayAddNewForm()
    {
        $pullOut = new PullOutRequest();

        $products = Product::active()->get();
        $items = $this->getItemToPullOut();
        
        $por = PullOutRequestNumber::first();

        if (!$por) {
            $por = PullOutRequestNumber::create([
                'num' => 1,
            ]);
        }

        return view('admin.pages.pull-outs.form', [
            'pullOut' => $pullOut,
            'products' => $products,
            'items' => $items,
            'types' => $this->getPullOutTypes(),
            'por_code' => invoice_num($por->num)
        ]);
    }

    public function view(string $uuid)
    {
        $pullOut = PullOutRequest::with(['pull_out_request_products'])
                               ->whereUuid($uuid)
                               ->first();
        
        return view('admin.pages.pull-outs.view', [
            'pullOut' => $pullOut,
        ]);
    }

    /**
     * Get pull out types
     *
     * @return array
     */
    protected function getPullOutTypes()
    {
        return [
            PullOutRequest::DEMO_ITEMS,
            PullOutRequest::REPLACED_UNITS,
            PullOutRequest::BACKUP_UNITS,
            PullOutRequest::OTHERS,
        ];
    }

    /**
     * Add product/item to be pulled out
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric',
        ]);

        PullOutItem::updateOrCreate(['product_id' => $request->product_id], [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        $items = $this->getItemToPullOut();
        
        $html = view('admin.pages.pull-outs.pull-out-items', [
            'items' => $items
        ])->render();

        return response()->json([
            'html' => $html
        ]);

    }

    /**
     * Get items to pull out
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getItemToPullOut()
    {
        $items = DB::table('products')
                  ->select(
                    'products.*',
                    'pull_out_items.quantity',
                    'pull_out_items.id as item_id'
                  )
                  ->join('pull_out_items', 'products.id', '=', 'pull_out_items.product_id')
                  ->get();
        return $items;
    }


    /**
     * Pull out an item
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postPullOut(Request $request)
    {
        
        $request->validate([
            'type' => 'required',
        ]);

        $pullOuts = PullOutItem::get();
        if (count($pullOuts) <= 0) {
            return response()->json([
                'error' => "Pull out items are required."
            ], 422);
        }

        $num = PullOutRequestNumber::first();
        if (!$num) {
            $num = PullOutRequestNumber::create([
                'num' => 1,
            ]);
        }
        $invoice = $num->num;
        try {
            DB::transaction(function() use ($request, $invoice, $pullOuts, $num) {

                $data = [
                    'uuid' => generateUuid(),
                    'type' => $request->type,
                    'por_no' => invoice_num($invoice),
                    'business_name' => $request->business_name,
                    'address' => $request->address,
                    'contact_person' => $request->contact_person,
                    'phone' => $request->phone,
                    'salesman' => $request->salesman,
                    'requested_by' => $request->requested_by,
                    'approved_by' => $request->approved_by,
                    'returned_by' => $request->returned_by,
                    'counter_checked_by' => $request->counter_checked_by,
                ];

                if ($request->has('id')) {
                    unset($data['uuid']);
                }

                $pullOut = PullOutRequest::updateOrCreate(['id' => $request->id], $data);

                $items = [];
                
                foreach($pullOuts as $item){
                    $product = Product::find($item->product_id);

                    $items[] = [
                        'pull_out_request_id' => $pullOut->id,
                        'quantity' => $item->quantity,
                        'unit' => $product->unit_of_measure->title,
                        'product_uuid' => $product->uuid,
                        'product_name' => $product->name,
                        'code' => $product->code,
                        'purchase_description' => $product->purchase_description,
                        'size' => $product->size,
                        'color' => $product->color,
                        'remarks' => NULL,
                        'created_at' => now()->__toString(),
                        'updated_at' => now()->__toString(),
                    ];
                }

                if (count($items) > 0) {
                    PullOutRequestProduct::insert($items);
                    $num->num = $num->num + 1;
                    $num->save();
                }

            });
            PullOutItem::truncate();
            return response()->json([
                'message' => 'Product pull out request successfully saved.'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete pull out item
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        PullOutItem::whereId($request->id)->delete();
        
        $items = $this->getItemToPullOut();
        
        $html = view('admin.pages.pull-outs.pull-out-items', [
            'items' => $items
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }
}
