@extends('client.layouts.index')
@section('title', 'Tra cứu đơn hàng')

@section('content')

@php
    $statusMap = [
        'pending'    => ['badge-warning', 'Chờ xử lý'],
        'processing' => ['badge-info',    'Đang xử lý'],
        'shipped'    => ['badge-primary', 'Đang giao'],
        'delivered'  => ['badge-success', 'Đã giao'],
    ];
    $paymentMap = [
        'unpaid'   => ['badge-error',   'Chưa thanh toán'],
        'paid'     => ['badge-success', 'Đã thanh toán'],
        'refunded' => ['badge-warning', 'Đã hoàn tiền'],
    ];
    $activeTab = request('tab', 'order');
@endphp

<div class="mx-auto px-4 py-10">

    <x-breadcrumbs :items="[
        ['title' => 'Trang chủ', 'url' => route('index')],
        ['title' => 'Tra cứu đơn hàng'],
    ]" />

    <div class="tabs tabs-box mt-6 mb-5">
        <a href="?tab=order"
           class="tab {{ $activeTab === 'order' ? 'tab-active' : '' }} font-medium text-sm">
            🔍 Tra cứu mã đơn
        </a>
        <a href="?tab=history"
           class="tab {{ $activeTab === 'history' ? 'tab-active' : '' }} font-medium text-sm">
            📦 Lịch sử mua hàng
        </a>
    </div>

    @if ($activeTab === 'order')

        <div class="bg-base-100 border border-base-200 rounded-2xl p-6">
            <h2 class="text-base font-bold text-base-content mb-1">Tra cứu đơn hàng</h2>
            <p class="text-sm text-base-content/40 mb-5">Nhập mã đơn hàng để kiểm tra trạng thái.</p>

            <form method="GET" action="{{ route('orders.lookup') }}">
                <input type="hidden" name="tab" value="order" />
                <div class="join w-full">
                    <input type="text" name="order_code"
                           value="{{ request('order_code') }}"
                           placeholder="VD: ORD-20240001"
                           class="input input-bordered join-item flex-1 rounded-l-xl focus:outline-none focus:border-error" />
                    <button class="btn btn-error join-item rounded-r-xl font-semibold">
                        Tra cứu
                    </button>
                </div>
            </form>
        </div>

        @isset($order)
            @php
                $order_statuses = array_keys($statusMap);
                $currentIdx = array_search($order->status, $order_statuses);
                [$sCls, $sLabel] = $statusMap[$order->status]          ?? ['badge-ghost', $order->status];
                [$pCls, $pLabel] = $paymentMap[$order->payment_status] ?? ['badge-ghost', $order->payment_status];
            @endphp

            <div class="mt-5 bg-base-100 border border-base-200 rounded-2xl p-6">
                <p class="font-bold text-sm mb-5">Trạng thái đơn hàng</p>
                <ul class="steps steps-horizontal w-full text-xs">
                    @foreach ($statusMap as $key => [$cls, $label])
                        @php $thisIdx = array_search($key, $order_statuses); @endphp
                        <li class="step {{ $thisIdx <= $currentIdx ? 'step-error' : '' }}">
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-5 bg-base-100 border border-base-200 rounded-2xl p-6">
                <p class="font-bold text-sm mb-4">Thông tin đơn hàng</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-base-content/40 mb-0.5">Mã đơn</p>
                        <p class="font-mono font-bold">#{{ $order->order_code }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/40 mb-0.5">Ngày đặt</p>
                        <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/40 mb-0.5">Trạng thái</p>
                        <span class="badge {{ $sCls }} badge-sm font-semibold">{{ $sLabel }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/40 mb-0.5">Thanh toán</p>
                        <span class="badge {{ $pCls }} badge-sm font-semibold">{{ $pLabel }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/40 mb-0.5">Phương thức</p>
                        <p class="font-medium">{{ $order->payment_method }}</p>
                    </div>
                    @if ($order->note)
                        <div>
                            <p class="text-xs text-base-content/40 mb-0.5">Ghi chú</p>
                            <p class="font-medium">{{ $order->note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Thông tin khách --}}
            <div class="mt-5 bg-base-100 border border-base-200 rounded-2xl p-6">
                <p class="font-bold text-sm mb-4">Người nhận</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-base-content/40 mb-0.5">Họ và tên</p>
                        <p class="font-medium">{{ $order->customer->fullname }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-base-content/40 mb-0.5">Số điện thoại</p>
                        <p class="font-medium">{{ $order->customer->phone }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-base-content/40 mb-0.5">Địa chỉ</p>
                        <p class="font-medium">{{ $order->customer->address ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Sản phẩm --}}
            <div class="mt-5 bg-base-100 border border-base-200 rounded-2xl p-6">
                <p class="font-bold text-sm mb-4">Sản phẩm</p>
                <div class="flex flex-col gap-3">
                    @foreach ($order->orderItems as $item)
                        <div class="flex items-center gap-3">
                            @if ($item->product?->image)
                                <div class="avatar shrink-0">
                                    <div class="mask mask-squircle h-11 w-11">
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}" />
                                    </div>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">
                                    {{ $item->product->name ?? 'Sản phẩm đã xoá' }}
                                </p>
                                <p class="text-xs text-base-content/40">
                                    {{ number_format($item->price, 0, ',', '.') }}đ × {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="text-sm font-bold shrink-0">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="divider my-3"></div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold">Tổng cộng</span>
                    <span class="text-lg font-extrabold text-error">
                        {{ number_format($order->total_amount, 0, ',', '.') }}đ
                    </span>
                </div>
            </div>

        @endisset

        @if (request('order_code') && !isset($order))
            <div class="mt-5 bg-base-100 border border-base-200 rounded-2xl p-10
                        flex flex-col items-center gap-3 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.2"
                     stroke-linecap="round" stroke-linejoin="round"
                     class="size-14 text-base-content/20">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <p class="font-semibold text-base-content/60">Không tìm thấy đơn hàng</p>
                <p class="text-sm text-base-content/40">
                    Mã <span class="font-mono font-bold">{{ request('order_code') }}</span> không tồn tại.
                </p>
            </div>
        @endif

    @endif

    {{-- ===== TAB 2: LỊCH SỬ MUA HÀNG ===== --}}
    @if ($activeTab === 'history')

        <div class="bg-base-100 border border-base-200 rounded-2xl p-6">
            <h2 class="text-base font-bold text-base-content mb-1">Lịch sử mua hàng</h2>
            <p class="text-sm text-base-content/40 mb-5">Nhập email và số điện thoại để xem lịch sử.</p>

            <form method="GET" action="{{ route('orders.lookup') }}">
                <input type="hidden" name="tab" value="history" />
                <div class="flex flex-col gap-3">
                    <input type="email" name="email"
                           value="{{ request('email') }}"
                           placeholder="Email đã đặt hàng"
                           class="input input-bordered w-full rounded-xl focus:outline-none focus:border-error" />
                    <div class="join w-full">
                        <input type="text" name="phone"
                               value="{{ request('phone') }}"
                               placeholder="Số điện thoại"
                               class="input input-bordered join-item flex-1 rounded-l-xl focus:outline-none focus:border-error" />
                        <button class="btn btn-error join-item rounded-r-xl font-semibold">
                            Xem lịch sử
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Kết quả lịch sử --}}
        @if (request('email') && request('phone'))
            @if ($customer && $orders?->isNotEmpty())

                <div class="mt-5 bg-base-100 border border-base-200 rounded-2xl p-5">
                    <p class="text-sm font-bold mb-1">{{ $customer->fullname }}</p>
                    <p class="text-xs text-base-content/40">
                        {{ $customer->email }} · {{ $customer->phone }}
                    </p>
                </div>

                <div class="mt-3 flex flex-col gap-3">
                    @foreach ($orders as $order)
                        @php
                            [$sCls, $sLabel] = $statusMap[$order->status]          ?? ['badge-ghost', $order->status];
                            [$pCls, $pLabel] = $paymentMap[$order->payment_status] ?? ['badge-ghost', $order->payment_status];
                        @endphp

                        <div class="bg-base-100 border border-base-200 rounded-2xl p-4">
                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <p class="font-mono font-bold text-sm">#{{ $order->order_code }}</p>
                                    <p class="text-xs text-base-content/40 mt-0.5">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="badge {{ $sCls }} badge-sm font-semibold">{{ $sLabel }}</span>
                                    <span class="badge {{ $pCls }} badge-sm font-semibold">{{ $pLabel }}</span>
                                </div>
                            </div>

                            {{-- Sản phẩm rút gọn --}}
                            <div class="flex flex-col gap-2">
                                @foreach ($order->orderItems->take(2) as $item)
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="flex-1 text-base-content/70 truncate">
                                            {{ $item->product->name ?? 'Sản phẩm đã xoá' }}
                                        </span>
                                        <span class="text-xs text-base-content/40 shrink-0">
                                            ×{{ $item->quantity }}
                                        </span>
                                    </div>
                                @endforeach
                                @if ($order->orderItems->count() > 2)
                                    <p class="text-xs text-base-content/30">
                                        +{{ $order->orderItems->count() - 2 }} sản phẩm khác
                                    </p>
                                @endif
                            </div>

                            <div class="divider my-2"></div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-extrabold text-error">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                </span>
                                <a href="{{ route('orders.lookup') }}?tab=order&order_code={{ $order->order_code }}"
                                   class="btn btn-ghost btn-xs rounded-lg text-xs">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="mt-5 bg-base-100 border border-base-200 rounded-2xl p-10
                            flex flex-col items-center gap-3 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.2"
                         stroke-linecap="round" stroke-linejoin="round"
                         class="size-14 text-base-content/20">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <p class="font-semibold text-base-content/60">Không tìm thấy khách hàng</p>
                    <p class="text-sm text-base-content/40">Kiểm tra lại email và số điện thoại.</p>
                </div>
            @endif
        @endif

    @endif

</div>

@endsection