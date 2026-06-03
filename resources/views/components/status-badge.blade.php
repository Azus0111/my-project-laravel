@props(['status', 'role', 'statusOrder', 'payment_status'])

@php
    $badgeMap = [
        'status' => [
            1 => ['text' => 'Active', 'color' => 'success'],
            0 => ['text' => 'Inactive', 'color' => 'warning'],
        ],
        'role' => [
            1 => ['text' => 'Admin', 'color' => 'primary'],
            0 => ['text' => 'User', 'color' => 'secondary'],
        ],
        'statusOrder' => [
            'pending' => ['text' => 'Pending', 'color' => 'warning'],
            'processing' => ['text' => 'Processing', 'color' => 'primary'],
            'shipped' => ['text' => 'Shipped', 'color' => 'info'],
            'delivered' => ['text' => 'Delivered', 'color' => 'success'],
        ],
        'payment_status' => [
            'unpaid' => ['text' => 'Unpaid', 'color' => 'error'],
            'paid' => ['text' => 'Paid', 'color' => 'success'],
        ],
    ];

    $defaultBadge = ['text' => 'Unknown', 'color' => 'neutral'];
    $statusData = isset($status) ? ($badgeMap['status'][$status] ?? $defaultBadge) : null;
    $roleData = isset($role) ? ($badgeMap['role'][$role] ?? $defaultBadge) : null;
    $statusOrderData = isset($statusOrder) ? ($badgeMap['statusOrder'][$statusOrder] ?? $defaultBadge) : null;
    $paymentStatusData = isset($payment_status) ? ($badgeMap['payment_status'][$payment_status] ?? $defaultBadge) : null;

@endphp

<div>
    @if ($statusData)
        <div class="badge badge-soft badge-{{ $statusData['color'] }}">
            {{ $statusData['text'] }}
        </div>
    @endif

    @if ($roleData)
        <div class="badge badge-soft badge-{{ $roleData['color'] }}">
            {{ $roleData['text'] }}
        </div>
    @endif

    @if ($statusOrderData)
        <div class="badge badge-soft badge-{{ $statusOrderData['color'] }}">
            {{ $statusOrderData['text'] }}
        </div>
    @endif

    @if ($paymentStatusData)
        <div class="badge badge-soft badge-{{ $paymentStatusData['color'] }}">
            {{ $paymentStatusData['text'] }}
        </div>
    @endif
</div>