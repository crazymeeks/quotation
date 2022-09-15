<?php

namespace App\Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRepository
{

    public function get()
    {
        return $this->getBaseTable()->get();
    }

    

    /**
     * Get base table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getBaseTable()
    {
        $builder = DB::table('products')
                     ->select(
                        'products.*',
                        'unit_of_measures.title as measure',
                        'companies.name as company_name'
                     )
                     ->leftJoin('unit_of_measures', 'products.unit_of_measure_id', '=', 'unit_of_measures.id')
                     ->leftJoin('companies', 'products.company_id', '=', 'companies.id');

        return $builder;
    }

     /**
     * Data to datatable
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getDatatableData(Request $request)
    {
        $limit = $request->length;
        $offset = $request->start;
        $products = $this->getBaseTable()
                       ->where(function($query) use ($request) {
                            $search = $request->search['value'];
                            if (!empty($search)) {
                                return $query->where('products.name', 'like', '%' . $search . '%')
                                      ->orWhere('products.cost', 'like', '%' . $search . '%')
                                      ->orWhere('products.percent_discount', 'like', '%' . $search . '%')
                                      ->orWhere('products.inventory', 'like', '%' . $search . '%')
                                      ->orWhere('unit_of_measures.title', 'like', '%' . $search . '%')
                                      ->orWhere('companies.name', 'like', '%' . $search . '%');
                            }
                       })
                       ->whereNull('products.deleted_at')
                       ->limit($limit)
                       ->offset($offset)
                       ->get();
        
        
        $logs = $products->toArray();
        
        $totalRecords = count($logs);
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $logs,
        ];

        return $data;
    }
}