<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
    
    /**
     * Display index page
     *
     * @return \Illuminate\View\View
     */
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
     * Set order to paid
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function putPaidOrder(Request $request)
    {
        $request->validate([
            'uuid' => 'required'
        ]);


        $order = Order::whereUuid($request->uuid)
                      ->whereStatus(Order::PENDING)
                      ->first();

        if ($order !== null) {
            $order->status = Order::PAID;
            $order->updated_at = now()->__toString();
            $order->save();
            return response()->json([
                'message' => "Order successfully set to paid."
            ]);
        }

        return response()->json([
            'message' => 'Order could not be found.'
        ], 400);

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
