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

        $order = $request->order;
        $columns = $request->columns;

        $column_idx = $order[0]['column'];
        $column = $columns[$column_idx]['data'];

        $column = $this->mapColumn($column);
        
        $orderDirection = $order[0]['dir'];

        $total = $this->getBaseTable()
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
                       ->count();



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
                       ->orderBy($column, $orderDirection)
                       ->get();
        
        
        $logs = $products->toArray();
        
        $totalRecords = $total;
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $logs,
        ];

        return $data;
    }


    protected function mapColumn(string $column)
    {
        $columns = [
            'name' => 'products.name',
            'cost' => 'products.cost',
            'inventory' => 'products.inventory',
            'percent_discount' => 'products.percent_discount',
            'measure' => 'unit_of_measures.title',
            'company_name' => 'companies.name',
            'status' => 'products.status',
        ];

        return $columns[$column];
    }
}