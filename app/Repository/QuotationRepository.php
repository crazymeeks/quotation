<?php

namespace App\Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationRepository
{


    /**
     * Get base table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getBaseTable()
    {
        $builder = DB::table('quotations')
                     ->select(
                        'quotations.*',
                        'customers.customer_name as customer'
                     )
                     ->leftJoin('quotation_products', 'quotations.id', '=', 'quotation_products.quotation_id')
                     ->leftJoin('customers', 'quotations.customer_id', '=', 'customers.id');
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

        $quotations = $this->getBaseTable()
                           ->where(function($query) use ($request) {
                                $search = $request->search['value'];
                                if (!empty($search)) {
                                    return $query->where('quotations.code', 'like', '%' . $search . '%')
                                                 ->orWhere('customers.customer_name', 'like', '%' . $search . '%')
                                                 ->orWhere('quotations.status', 'like', '%' . $search . '%');
                                }
                           })
                           ->limit($limit)
                           ->offset($offset)
                           ->orderBy($column, $orderDirection)
                           ->get();

        $quotations = $quotations->toArray();

        $totalRecords = count($quotations);
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $quotations,
        ];

        return $data;

    }

    protected function mapColumn(string $column)
    {
        $columns = [
            'customer' => 'customers.customer_name',
            'status' => 'quotations.status',
            'code' => 'quotations.code',
        ];

        return $columns[$column];
    }
}