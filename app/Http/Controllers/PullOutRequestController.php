<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\PullOutRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PullOutRequestNumbers;
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

        return view('admin.pages.pull-outs.form', [
            'pullOut' => $pullOut,
            'types' => $this->getPullOutTypes(),
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
            'items' => 'required',
        ]);

        $num = PullOutRequestNumbers::first();
        if (!$num) {
            $num = PullOutRequestNumbers::create([
                'num' => 1,
            ]);
        }
        $invoice = $num->num;
        try {
            DB::transaction(function() use ($request, $invoice) {
                $pullOut = PullOutRequest::updateOrCreate(['id' => $request->id], [
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
                ]);

                $items = [];
                foreach($request->items as $item){
                    $product = Product::find($item['product_id']);

                    $items[] = [
                        'pull_out_request_id' => $pullOut->id,
                        'quantity' => $item['quantity'],
                        'unit' => $product->unit_of_measure->title,
                        'product_uuid' => $product->uuid,
                        'product_name' => $product->name,
                        'code' => $product->code,
                        'purchase_description' => $product->purchase_description,
                        'size' => $product->size,
                        'color' => $product->color,
                        'remarks' => $item['remarks'],
                        'created_at' => now()->__toString(),
                        'updated_at' => now()->__toString(),
                    ];
                }

                if (count($items) > 0) {
                    PullOutRequestProduct::insert($items);
                }

            });
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
}
