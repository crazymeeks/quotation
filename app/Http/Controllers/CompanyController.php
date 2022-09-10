<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    
    /**
     * Save company
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies,name,' . $request->id,
        ]);

        try {
            DB::transaction(function() use ($request) {
                $data = $request->toArray();

                if (!$request->has('id')) {
                    $data['uuid'] = generateUuid();
                }

                Company::updateOrCreate(['id' => $request->id], $data);
            });
            return response()->json(['message' => 'Company successfully saved!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Oops! Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a company
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCompany(Request $request)
    {
        $request->validate([
            'uuid' => 'required'
        ]);
        
        $company = Company::where('uuid', $request->uuid)->first();
        $company->name = sprintf("%s.%s", $company->name, uniqid());
        $company->deleted_at = now()->__toString();
        $company->save();

        return response()->json([
            'message' => 'Company has been deleted'
        ]);
    }
}
