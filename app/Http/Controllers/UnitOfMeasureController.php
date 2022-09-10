<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\UnitOfMeasure;
use Illuminate\Support\Facades\DB;

class UnitOfMeasureController extends Controller
{
    

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
}
