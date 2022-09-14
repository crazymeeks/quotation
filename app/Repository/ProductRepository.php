<?php

namespace App\Repository;

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
}