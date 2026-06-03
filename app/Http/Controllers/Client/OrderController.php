<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function lookup(Request $request)
    {
        $order    = null;
        $orders   = null;
        $customer = null;

        if ($request->filled('order_code')) {
            $order = Order::with(['customer', 'orderItems'])
                ->where('order_code', $request->order_code)
                ->first();
        }

        if ($request->filled('email') && $request->filled('phone')) {
            $customer = Customer::where('email', $request->email)
                ->where('phone', $request->phone)
                ->first();

            if ($customer) {
                $orders = Order::with(['orderItems'])
                    ->where('customer_id', $customer->id)
                    ->latest()
                    ->get();
            }
        }

        return view('client.pages.orders.lookup', compact('order', 'orders', 'customer'));
    }
}
