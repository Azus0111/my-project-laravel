<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $with = ['category', 'brand'];

        $newProducts = Product::with($with)
            ->where('status', 1)
            ->latest()
            ->limit(8)
            ->get();

        $hotProducts = Product::with($with)
            ->where('status', 1)
            ->orderByDesc('views')
            ->limit(8)
            ->get();

        $discountProducts = Product::with($with)
            ->where('status', 1)
            ->where('discount_percent', '>', 0)
            ->orderByDesc('discount_percent')
            ->limit(8)
            ->get();

        return view('client.pages.home.index', compact(
            'newProducts',
            'hotProducts',
            'discountProducts'
        ));
    }
}
