<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {
        $query = Order::query()->with('customer');

        if ($request->has('search') && $request->search != '') {
            $customer = Customer::where('phone', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('fullname', 'like', '%' . $request->search . '%')
                ->first();
            $query->where('order_code', 'like', '%' . $request->search . '%')
                ->orWhere('customer_id', $customer ? $customer->id : null);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('id', 'desc')->paginate(5);

        $orders->appends($request->all());

        return view('admin.pages.orders.index', compact('orders'));
    }

    public function show($id) {
        $order = Order::with('customer', 'orderItems')->findOrFail($id);
        return view('admin.pages.orders.show', compact('order'));
    }

    public function updatePaymentStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('admin.orders.index', $id)->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }

    public function updateStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.index', $id)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
