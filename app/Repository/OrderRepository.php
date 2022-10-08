<?php

namespace App\Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderRepository
{


    /**
     * Get base table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getBaseTable()
    {
        $builder = DB::table('orders')
                     ->select(
                        'orders.*',
                        'customers.customer_name as customer',
                        'customers.contact_no as customer_contact',
                        'customers.address as customer_address',
                        'order_products.product_uuid',
                        'order_products.unit_of_measure',
                        'order_products.company',
                        'order_products.product_name',
                        'order_products.manufacturer_part_number',
                        'order_products.purchase_description',
                        'order_products.sales_description',
                        'order_products.price',
                        'order_products.quantity',
                        'order_products.final_price'
                     )
                     ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
                     ->leftJoin('order_products', 'orders.id', '=', 'order_products.order_id');
        return $builder;
    }

    /**
     * Get order products by uuid
     *
     * @param string $uuid
     * 
     * @return array
     */
    public function getOrderProducts(string $uuid)
    {
        $order = $this->getBaseTable()
                      ->where('orders.uuid', $uuid)
                      ->get();
        
        $data = [
            'customer' => null,
            'order' => null,
            'items' => null
        ];

        foreach($order as $o){
            if ($data['customer'] === null) {
                $data['customer'] = [
                    'name' => $o->customer,
                    'contact' => $o->customer_contact,
                    'address' => $o->customer_address,
                ];
            }
            if ($data['order'] === null) {
                $data['order'] = [
                    'reference_no' => $o->reference_no,
                    'status' => $o->status,
                    'discount' => $o->percent_discount,
                    'grand_total' => $o->grand_total,
                ];
            }

            $data['items'][] = [
                'name' => $o->product_name,
                'price' => $o->price,
                'final_price' => $o->final_price,
                'quantity' => $o->quantity,
                'unit_of_measure' => $o->unit_of_measure,
            ];
        }

        return json_decode(json_encode($data));
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
                                    return $query->where('orders.reference_no', 'like', '%' . $search . '%')
                                                 ->orWhere('customers.customer_name', 'like', '%' . $search . '%')
                                                 ->orWhere('orders.status', 'like', '%' . $search . '%');
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
            'status' => 'orders.status',
            'reference_no' => 'orders.reference_no',
            'grand_total' => 'orders.grand_total',
            'percent_discount' => 'orders.percent_discount',
        ];

        return $columns[$column];
    }
}