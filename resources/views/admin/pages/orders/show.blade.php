@extends('admin.layouts.index')
@section('title', 'Chi tiết đơn hàng #' . $order->order_code)

@section('content')

<div class="flex justify-between mb-3">
    <x-breadcrumbs :items="[
        ['title' => 'Home',       'url' => route('admin.dashboard')],
        ['title' => 'Orders',     'url' => route('admin.orders.index')],
        ['title' => '#' . $order->order_code],
    ]" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-5 items-start">

    {{-- ===== CỘT TRÁI ===== --}}
    <div class="flex flex-col gap-5">

        {{-- Danh sách sản phẩm --}}
        <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
            <p class="font-bold text-sm mb-4">Sản phẩm trong đơn</p>

            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr class="text-xs text-base-content/40 border-base-200">
                            <th>Sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr class="border-base-200">
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if ($item->product?->image)
                                            <div class="avatar shrink-0">
                                                <div class="mask mask-squircle h-10 w-10">
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                         alt="{{ $item->product->name }}" />
                                                </div>
                                            </div>
                                        @endif
                                        <span class="text-sm font-medium line-clamp-2 max-w-xs">
                                            {{ $item->product->name ?? 'Sản phẩm đã xoá' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center text-sm text-base-content/60">
                                    {{ $item->quantity }}
                                </td>
                                <td class="text-right text-sm text-base-content/60 whitespace-nowrap">
                                    {{ number_format($item->price, 0, ',', '.') }}đ
                                </td>
                                <td class="text-right text-sm font-semibold whitespace-nowrap">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-base-200">
                            <td colspan="3"
                                class="text-right text-sm font-bold text-base-content pt-3">
                                Tổng cộng
                            </td>
                            <td class="text-right text-base font-extrabold text-error whitespace-nowrap pt-3">
                                {{ number_format($order->total_amount, 0, ',', '.') }}đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Thông tin khách hàng --}}
        <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
            <p class="font-bold text-sm mb-4">Thông tin khách hàng</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-xs text-base-content/40 mb-0.5">Họ và tên</p>
                    <p class="font-medium">{{ $order->customer->fullname }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/40 mb-0.5">Email</p>
                    <p class="font-medium">{{ $order->customer->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/40 mb-0.5">Số điện thoại</p>
                    <p class="font-medium">{{ $order->customer->phone }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/40 mb-0.5">Địa chỉ</p>
                    <p class="font-medium">{{ $order->customer->address ?? '—' }}</p>
                </div>
                @if ($order->note)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-base-content/40 mb-0.5">Ghi chú</p>
                        <p class="font-medium">{{ $order->note }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ===== CỘT PHẢI ===== --}}
    <div class="flex flex-col gap-5">

        {{-- Tóm tắt đơn --}}
        <div class="bg-base-100 border border-base-200 rounded-2xl p-5 flex flex-col gap-3">
            <p class="font-bold text-sm">Thông tin đơn hàng</p>

            <div class="flex flex-col gap-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-base-content/40">Mã đơn</span>
                    <span class="font-mono font-bold">#{{ $order->order_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/40">Thanh toán</span>
                    <span class="font-medium">{{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng' : 'Thanh toán online' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-base-content/40">Trạng thái đơn</span>
                    <x-status-badge :statusOrder="$order->status" />
                </div>
            </div>
        </div>

        {{-- Cập nhật trạng thái thanh toán --}}
        <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
            <p class="font-bold text-sm mb-4">Trạng thái thanh toán</p>

            <form method="POST"
                  action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}">
                @csrf
                @method('PATCH')

                <div class="flex flex-col gap-3">
                    <select name="payment_status"
                            class="select select-bordered w-full rounded-xl text-sm">
                        @foreach ([
                            'unpaid'   => 'Chưa thanh toán',
                            'paid'     => 'Đã thanh toán',
                        ] as $value => $label)
                            <option value="{{ $value }}"
                                    @selected($order->payment_status === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="btn btn-primary rounded-xl btn-sm w-full font-semibold">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>

        {{-- Cập nhật trạng thái đơn hàng --}}
        <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
            <p class="font-bold text-sm mb-4">Trạng thái đơn hàng</p>

            <form method="POST"
                  action="{{ route('admin.orders.updateStatus', $order->id) }}">
                @csrf
                @method('PATCH')

                <div class="flex flex-col gap-3">
                    <select name="status"
                            class="select select-bordered w-full rounded-xl text-sm">
                        @foreach ([
                            'pending'    => 'Chờ xử lý',
                            'processing' => 'Đang xử lý',
                            'shipped'    => 'Đang giao',
                            'delivered'  => 'Đã giao',
                        ] as $value => $label)
                            <option value="{{ $value }}"
                                    @selected($order->status === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="btn btn-primary rounded-xl btn-sm w-full font-semibold">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@if (session('success'))
    <x-toast :messages="[session('success')]" />
@endif
@if (session('error'))
    <x-toast type="error" :messages="[session('error')]" />
@endif

@endsection