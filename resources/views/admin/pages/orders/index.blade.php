@extends('admin.layouts.index')
@section('title', "Orders")


@section('content')
    <div class="flex justify-between mb-3">
        <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('admin.dashboard')],
            ['title' => 'Orders']
        ]" />

    </div>

    <x-filters :fields="[
            ['type' => 'search', 'name' => 'search', 'placeholder' => 'Customer Phone, Email, Full Name or Order Code'],
            [
                'type' => 'select',
                'name' => 'status',
                'options' => [
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'processing', 'label' => 'Processing'],
                    ['value' => 'shipped', 'label' => 'Shipped'],
                    ['value' => 'delivered', 'label' => 'Delivered']
                ],
                'label' => 'Status'
            ],
            [
                'type' => 'select',
                'name' => 'payment_status',
                'options' => [
                    ['value' => 'unpaid', 'label' => 'Unpaid'],
                    ['value' => 'paid', 'label' => 'Paid']
                ],
                'label' => 'Payment Status'
            ]
        ]" />

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <!-- head -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fullname & Phone</th>
                    <th>Order Code</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($orders->isEmpty())
                    <tr>
                        <td class="text-center font-bold text-2xl" colspan="6">Danh sách trống</td>
                    </tr>
                @else
                    @foreach ($orders as $order)
                        <tr onclick="window.location.href='{{ route('admin.orders.show', $order->id) }}'" class="cursor-pointer">
                            <th>{{ $order->id }}</th>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-bold">{{ $order->customer->fullname }}</div>
                                        <div class="text-sm opacity-50">{{ $order->customer->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $order->order_code }}</td>
                            <td>
                                <x-status-badge :statusOrder="$order->status" />
                            </td>
                            <td>
                                <x-status-badge :payment_status="$order->payment_status" />
                            </td>
                            <td>
                                <form action="{{ route("admin.orders.destroy", $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-soft btn-error tooltip tooltip-top" data-tip="Delete"
                                        onclick="return confirm('Are you sure you want to delete this order?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @if(session('success'))
        <x-toast :messages="[session('success')]" />
    @endif
    @if(session('error'))
        <x-toast type="error" :messages="[session('error')]" />
    @endif
@endsection