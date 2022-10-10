<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{

    /**
     * Display index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.pages.companies.index');
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

        $total = Company::where(function($query) use ($request) {
            $search = $request->search['value'];
            if (!empty($search)) {
                return $query->where('name', 'like', '%' . $search . '%');
            }
        })
        ->count();

        $companies = Company::where(function($query) use ($request) {
            $search = $request->search['value'];
            if (!empty($search)) {
                return $query->where('name', 'like', '%' . $search . '%');
            }
        })
        ->limit($limit)
        ->offset($offset)
        ->orderBy($column, $orderDirection)
        ->get();
        
        $totalRecords = $total;
        $companies = collect($companies)->toArray();
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $companies,
        ];

        return $data;
    }


    /**
     * Show add new form page 
     *
     * @return \Illuminate\View\View
     */
    public function showAddNewPage()
    {
        $company = new Company();
        return view('admin.pages.companies.form', ['company' => $company]);
    }

    /**
     * Edit company
     *
     * @param string $uuid
     * 
     * @return \Illuminate\View\View
     */
    public function editCompanyPage(string $uuid)
    {
        $company = Company::whereUuid($uuid)->first();
        if ($company !== null) {
            return view('admin.pages.companies.form', ['company' => $company]);
        }
        abort(404);
    }
    
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

                $data['name'] = strtoupper($data['name']);
                if (isset($data['address']) && $data['address'] !== null) {
                    $data['address'] = strtoupper($data['address']);
                }

                Company::updateOrCreate(['id' => $request->id], $data);
            });
            return response()->json(['message' => 'Company successfully saved!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Oops! Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate companies
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postValidate(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:companies,name,' . $request->id,
            ]);

            return response()->json(['message' => 'Company name is valid.']);

        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
