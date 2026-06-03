@extends('admin.layouts.index')
@section('title', 'Dashboard')

@section('content')

@php
    $statusMap = [
        'pending'    => ['badge-warning', 'Chờ xử lý'],
        'processing' => ['badge-info',    'Đang xử lý'],
        'shipped'    => ['badge-primary', 'Đang giao'],
        'delivered'  => ['badge-success', 'Đã giao'],
    ];
    $paymentMap = [
        'unpaid'   => ['badge-error',   'Chưa TT'],
        'paid'     => ['badge-success', 'Đã TT'],
        'refunded' => ['badge-warning', 'Hoàn tiền'],
    ];
@endphp

<div class="mb-4">
    <x-breadcrumbs :items="[['title' => 'Home']]" />
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    @foreach ([
        ['label' => 'Sản phẩm',    'value' => $totalProducts,  'color' => 'text-primary',
         'icon'  => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>'],
        ['label' => 'Đơn hàng',    'value' => $totalOrders,    'color' => 'text-warning',
         'icon'  => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>'],
        ['label' => 'Khách hàng',  'value' => $totalCustomers, 'color' => 'text-success',
         'icon'  => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
        ['label' => 'Doanh thu',   'value' => number_format($totalRevenue, 0, ',', '.') . 'đ', 'color' => 'text-error',
         'icon'  => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>'],
    ] as $stat)
        <div class="bg-base-100 border border-base-200 rounded-2xl p-4 flex items-center gap-4">
            <div class="p-3 rounded-xl bg-base-200 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round"
                     class="size-5 {{ $stat['color'] }}">
                    {!! $stat['icon'] !!}
                </svg>
            </div>
            <div>
                <p class="text-xs text-base-content/40 mb-0.5">{{ $stat['label'] }}</p>
                <p class="text-xl font-extrabold text-base-content">{{ $stat['value'] }}</p>
            </div>
        </div>
    @endforeach

</div>

{{-- ===== 2 BẢNG ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Sản phẩm mới nhất --}}
    <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <p class="font-bold text-sm">Sản phẩm mới nhất</p>
            <a href="{{ route('admin.products.index') }}"
               class="text-xs text-error hover:underline">Xem tất cả →</a>
        </div>

        <div class="flex flex-col gap-3">
            @forelse ($newProducts as $product)
                <div class="flex items-center gap-3">
                    <div class="avatar shrink-0">
                        <div class="mask mask-squircle h-10 w-10">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}" />
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $product->name }}</p>
                        <p class="text-xs text-base-content/40">
                            {{ $product->category->name ?? '—' }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold text-error">
                            {{ number_format($product->final_price ?? $product->price, 0, ',', '.') }}đ
                        </p>
                        @if ($product->discount_percent)
                            <span class="text-[10px] text-success font-semibold">
                                −{{ $product->discount_percent }}%
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-base-content/40 text-center py-6">Chưa có sản phẩm.</p>
            @endforelse
        </div>
    </div>

    {{-- Đơn hàng mới nhất --}}
    <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <p class="font-bold text-sm">Đơn hàng mới nhất</p>
            <a href="{{ route('admin.orders.index') }}"
               class="text-xs text-error hover:underline">Xem tất cả →</a>
        </div>

        <div class="flex flex-col gap-3">
            @forelse ($newOrders as $order)
                @php
                    [$sCls, $sLabel] = $statusMap[$order->status]         ?? ['badge-ghost', $order->status];
                    [$pCls, $pLabel] = $paymentMap[$order->payment_status] ?? ['badge-ghost', $order->payment_status];
                @endphp
                <div class="flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-mono font-bold">#{{ $order->order_code }}</p>
                            <span class="badge {{ $sCls }} badge-xs">{{ $sLabel }}</span>
                        </div>
                        <p class="text-xs text-base-content/40 truncate mt-0.5">
                            {{ $order->customer->fullname ?? '—' }}
                            · {{ $order->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold">
                            {{ number_format($order->total_amount, 0, ',', '.') }}đ
                        </p>
                        <span class="badge {{ $pCls }} badge-xs">{{ $pLabel }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-base-content/40 text-center py-6">Chưa có đơn hàng.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection