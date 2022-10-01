<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationHistoryController extends Controller
{
    

    /**
     * Show versions in modal
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postShowVersions(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $histories = DB::table('quotation_histories')
                       ->select(
                            'quotation_histories.code',
                            'quotation_history_products.*'
                       )
                       ->leftJoin('quotation_history_products', 'quotation_histories.id', '=', 'quotation_history_products.quotation_history_id')
                       ->where('quotation_histories.code', $request->code)
                       ->get();
        
        $html = view('admin.pages.quotation.modal.quotation-histories-content', ['histories' => $histories])->render();
        
        return response()->json([
            'html' => $html
        ]);
    }
}
