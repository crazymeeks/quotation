<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    

    /**
     * Display index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.pages.customers.index');
    }

    /**
     * Get product data for datatable
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatable(Request $request)
    {

        $limit = $request->length;
        $offset = $request->start;

        $order = $request->order;
        $columns = $request->columns;

        $column_idx = $order[0]['column'];
        $column = $columns[$column_idx]['data'];

        $orderDirection = $order[0]['dir'];

        $total = Customer::where(function($query) use ($request) {
            $search = $request->search['value'];
            if (!empty($search)) {
                return $query->where('customer_name', 'like', '%' . $search . '%')
                             ->orWhere('contact_no', 'like', '%' . $search . '%')
                             ->orWhere('address', 'like', '%' . $search . '%');
            }
        })
        ->count();

        $customers = Customer::where(function($query) use ($request) {
            $search = $request->search['value'];
            if (!empty($search)) {
                return $query->where('customer_name', 'like', '%' . $search . '%')
                             ->orWhere('contact_no', 'like', '%' . $search . '%')
                             ->orWhere('address', 'like', '%' . $search . '%');
            }
        })
        ->limit($limit)
        ->offset($offset)
        ->orderBy($column, $orderDirection)
        ->get();
        
        $totalRecords = $total;
        $customers = collect($customers)->toArray();
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $customers,
        ];

        return $data;
    }


    /**
     * Get customer list using typeahead jquery library
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function typeAhead(Request $request)
    {
        $customers = Customer::where('customer_name', 'like', '%' . $request->q . '%')->get();
        $customers = collect($customers)->toArray();
        return response()->json(['results' => $customers]);
    }
}
