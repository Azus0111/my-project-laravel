<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        return view('client.pages.cart.index');
    }

    public function checkout()
    {
        return view('client.pages.cart.checkout');
    }

    public function addToCart(Request $req, $id)
    {
        $product = Product::findOrFail($id);
        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            $cart[$id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->final_price ?? $product->price,
            ];
        }
        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function decreaseQuantity(Request $req, $id)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] -= 1;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
            Session::put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Quantity decreased successfully!');
    }

    public function removeCart($id)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {

            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }

    public function save(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank_transfer',
            'note' => 'nullable|string|max:1000',
        ]);

        $cart = Session::get('cart');
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        DB::transaction(function () use ($request, $cart, &$order) {

            $customer = Customer::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'fullname' => $request->fullname,
                    'email' => $request->email,
                    'address' => $request->address,
                ]
            );

            $total = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['product_id']);

                $price = $product->final_price ?? $product->price;

                $total += $price * $item['quantity'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'order_code' => 'ORD-' . now()->timestamp . '-' . rand(1000, 9999),
                'total_amount' => $total,
                'note' => $request->note ?? NULL,
                'payment_method' => $request->payment_method,
            ]);

            $order->orderItems()->createMany($orderItems);
        });


        Session::forget('cart');

        return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
    }
}
