<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\OrderRepository;

class OrderController extends Controller
{

    /** @var \App\Repository\OrderRepository */
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    

    public function index()
    {
        return view('admin.pages.orders.index');
    }

    /**
     * Get data and display in datatable
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataTable(Request $request)
    {
        $data = $this->orderRepository->getDatatableData($request);
        
        return $data;
    }

    /**
     * View order
     *
     * @param string $uuid
     * 
     * @return \Illuminate\View\View
     */
    public function viewOrder(string $uuid)
    {
        $order = $this->orderRepository->getOrderProducts($uuid);

        if (is_testing()) {
            return response()->json(['order' => $order]);
        }
        return view('admin.pages.orders.order-details', ['order' => $order]);
    }
}
