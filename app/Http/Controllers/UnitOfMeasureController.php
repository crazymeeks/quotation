<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\UnitOfMeasure;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UnitOfMeasureController extends Controller
{
    

    /**
     * Display index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.pages.unit-of-measures.index');
    }

    /**
     * Get uom data for datatable
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

        $total = UnitOfMeasure::where(function($query) use ($request) {
            $search = $request->search['value'];
            if (!empty($search)) {
                return $query->where('title', 'like', '%' . $search . '%');
            }
        })
        ->count();

        $companies = UnitOfMeasure::where(function($query) use ($request) {
            $search = $request->search['value'];
            if (!empty($search)) {
                return $query->where('title', 'like', '%' . $search . '%');
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
        $uom = new UnitOfMeasure();
        return view('admin.pages.unit-of-measures.form', ['uom' => $uom]);
    }


    /**
     * Edit unit of measure
     *
     * @param string $uuid
     * 
     * @return \Illuminate\View\View
     */
    public function editCompanyPage(string $uuid)
    {
        $uom = UnitOfMeasure::whereUuid($uuid)->first();
        if ($uom !== null) {
            return view('admin.pages.unit-of-measures.form', ['uom' => $uom]);
        }
        abort(404);
    }

    /**
     * Save unit of measure
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:unit_of_measures,title,' . $request->id
        ]);

        try {
            DB::transaction(function() use ($request) {
                $data = $request->except(['id']);
                if (!$request->has('uuid')) {
                    $data['uuid'] = generateUuid();
                }
                UnitOfMeasure::updateOrCreate(['uuid' => $request->uuid], $data);
            });
            return response()->json(['message' => 'Unit of measure successfully saved.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Oops! Something went wrong.', 'error' => $e->getMessage()], 500);

        }

    }

    /**
     * Delete unit of measure
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUom(Request $request)
    {
        $request->validate([
            'uuid' => 'required'
        ]);

        $uom = UnitOfMeasure::where('uuid', $request->uuid)->first();
        $uom->deleted_at = now()->__toString();
        $uom->title = sprintf("%s.%s", $uom->title, uniqid());
        $uom->save();

        return response()->json(['message' => 'Unit of measure successfully deleted.']);
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
                'title' => 'required|unique:unit_of_measures,title,' . $request->id,
            ]);

            return response()->json(['message' => 'Unit of measure is valid.']);

        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
