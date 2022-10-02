<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\OrderRepository;

class OrderController extends Controller
{
    

    public function index()
    {
        return view('admin.pages.orders.index');
    }

    /**
     * Get data and display in datatable
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Repository\OrderRepository $orderRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataTable(Request $request, OrderRepository $orderRepository)
    {
        $data = $orderRepository->getDatatableData($request);
        
        return $data;
    }
}
