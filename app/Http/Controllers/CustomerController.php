<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    

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
