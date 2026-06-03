@extends('client.layouts.index')
@section('title', 'Giỏ hàng')

@section('content')

    <div class="mx-auto px-4 py-6">

        <x-breadcrumbs :items="[
            ['title' => 'Trang chủ', 'url' => route('index')],
            ['title' => 'Giỏ hàng'],
        ]" />

        <h2 class="text-xl font-bold text-base-content mt-4 mb-5">Giỏ hàng của bạn</h2>

        @php $cart = session('cart', []); @endphp

        @if (empty($cart))
            <div class="flex flex-col items-center justify-center py-24 gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"
                    stroke-linecap="round" stroke-linejoin="round" class="size-16 text-base-content/20">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                <p class="text-base-content/40 text-sm">Giỏ hàng trống</p>
                <a href="{{ route('products.index') }}" class="btn btn-error btn-sm rounded-xl">
                    Tiếp tục mua sắm
                </a>
            </div>

        @else
            <div class="flex flex-col gap-3">
                @foreach ($cart as $id => $item)
                    <div class="bg-base-100 border border-base-200 rounded-2xl p-4
                                                    flex items-center gap-4">

                        {{-- Tên + giá --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-base-content truncate">
                                {{ $item['name'] }}
                            </p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <span class="text-error font-bold text-sm">
                                    {{ number_format($item['final_price'] ?? $item['price'], 0, ',', '.') }}đ
                                </span>
                                @if (!empty($item['final_price']) && $item['final_price'] < $item['price'])
                                    <span class="text-xs text-base-content/30 line-through">
                                        {{ number_format($item['price'], 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Số lượng --}}
                        <div class="join border border-base-200 rounded-xl overflow-hidden shrink-0">
                            <form method="POST" action="{{ route('cart.decrease', $id) }}">
                                @csrf
                                <button class="join-item btn btn-ghost btn-xs px-3 h-9">−</button>
                            </form>
                            <span class="join-item flex items-center justify-center
                                                             w-9 text-sm font-semibold border-x border-base-200">
                                {{ $item['quantity'] }}
                            </span>
                            <form method="POST" action="{{ route('cart.add', $id) }}">
                                @csrf
                                <button class="join-item btn btn-ghost btn-xs px-3 h-9">+</button>
                            </form>
                        </div>

                        {{-- Thành tiền --}}
                        <div class="text-right shrink-0 w-28 hidden sm:block">
                            <p class="text-[10px] text-base-content/40 mb-0.5">Thành tiền</p>
                            <p class="font-bold text-sm text-base-content">
                                {{ number_format(($item['final_price'] ?? $item['price']) * $item['quantity'], 0, ',', '.') }}đ
                            </p>
                        </div>

                        {{-- Xoá --}}
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            <button class="btn btn-ghost btn-square btn-sm rounded-xl
                                                      hover:bg-error/10 hover:text-error shrink-0" title="Xoá">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path d="M19 6l-1 14H6L5 6" />
                                    <path d="M10 11v6M14 11v6" />
                                    <path d="M9 6V4h6v2" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- Tổng + checkout --}}
            @php
                $total = collect($cart)->sum(fn($i) => ($i['final_price'] ?? $i['price']) * $i['quantity']);
            @endphp

            <div class="mt-6 bg-base-100 border border-base-200 rounded-2xl p-4
                                    flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs text-base-content/40 mb-0.5">Tổng cộng</p>
                    <p class="text-xl font-extrabold text-error">
                        {{ number_format($total, 0, ',', '.') }}đ
                    </p>
                </div>
                <a href="{{ route('cart.checkout') }}" class="btn btn-error rounded-xl font-semibold gap-2">
                    Thanh toán
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif

    </div>

    @if (session('success'))
        <x-toast type="success" :messages="[session('success')]" />
    @endif

@endsection