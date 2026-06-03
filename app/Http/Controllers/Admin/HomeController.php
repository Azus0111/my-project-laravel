<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.pages.dashboard', [
            'totalProducts'  => Product::count(),
            'totalOrders'    => Order::count(),
            'totalCustomers' => Customer::count(),
            'totalRevenue'   => Order::where('payment_status', 'paid')->sum('total_amount'),

            'newProducts' => Product::with('category')
                ->latest()->limit(5)->get(),

            'newOrders'   => Order::with('customer')
                ->latest()->limit(5)->get(),
        ]);
    }
}
