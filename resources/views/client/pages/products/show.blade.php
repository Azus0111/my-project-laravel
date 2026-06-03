@extends('client.layouts.index')
@section('title', $product->name)

@section('content')

<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- Breadcrumb --}}
    <x-breadcrumbs :items="[
        ['title' => 'Trang chủ', 'url' => route('index')],
        ['title' => 'Sản phẩm',  'url' => route('products.index')],
        ['title' => $product->name],
    ]" />

    {{-- Layout chính --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mt-6 items-start">

        {{-- Ảnh sản phẩm --}}
        <div class="relative rounded-2xl overflow-hidden bg-base-200 aspect-square">
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover"
            />

            @if ($product->discount_percent)
                <span class="absolute top-3.5 left-3.5 badge badge-error font-bold text-xs px-2.5 py-1">
                    −{{ $product->discount_percent }}%
                </span>
            @endif
        </div>

        {{-- Thông tin sản phẩm --}}
        <div class="flex flex-col gap-5">

            {{-- Brand & Category --}}
            <div class="flex items-center gap-2">
                @if ($product->brand)
                    <span class="badge badge-ghost text-xs">{{ $product->brand->name }}</span>
                @endif
                @if ($product->category)
                    <span class="badge badge-ghost text-xs">{{ $product->category->name }}</span>
                @endif
            </div>

            {{-- Tên sản phẩm --}}
            <h1 class="text-2xl font-bold text-base-content leading-snug">
                {{ $product->name }}
            </h1>

            <div class="divider my-0"></div>

            {{-- Mô tả --}}
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-base-content/40 mb-1.5">
                    Mô tả sản phẩm
                </p>
                <p class="text-sm text-base-content/70 leading-relaxed">
                    {!! $product->description ?? 'Chưa có mô tả cho sản phẩm này.' !!}
                </p>
            </div>

            {{-- Giá --}}
            <div class="bg-base-100 border border-base-200 rounded-2xl p-4 flex flex-col gap-2">
                <div class="flex items-baseline gap-2">
                    @if ($product->discount_percent)
                        <span class="text-2xl font-extrabold text-error">
                            {{ number_format($product->final_price, 0, ',', '.') }}đ
                        </span>
                        <span class="text-sm text-base-content/30 line-through">
                            {{ number_format($product->price, 0, ',', '.') }}đ
                        </span>
                    @else
                        <span class="text-2xl font-extrabold text-error">
                            {{ number_format($product->price, 0, ',', '.') }}đ
                        </span>
                    @endif
                </div>

                @if ($product->discount_percent)
                    @php $saved = $product->price - $product->final_price; @endphp
                    <span class="text-xs font-semibold text-success bg-success/10 px-2 py-0.5 rounded-md inline-block w-fit">
                        Tiết kiệm {{ number_format($saved, 0, ',', '.') }}đ
                    </span>
                @endif
            </div>

            {{-- Nút hành động --}}
            <form method="POST" action="{{ route('cart.add', $product->id) }}" class="grid grid-cols-[1fr_auto] gap-3">
                @csrf
                <button type="submit" class="btn btn-error rounded-xl gap-2 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    Thêm vào giỏ hàng
                </button>

                <button class="btn btn-ghost btn-square rounded-xl border border-base-200
                               hover:border-error hover:bg-error/10 hover:text-error tooltip tooltip-left"
                        data-tip="Yêu thích">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06
                                 a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78
                                 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            </form>

            {{-- Cam kết --}}
            <ul class="flex flex-col gap-2 pt-1">
                <li class="flex items-center gap-2 text-xs text-base-content/50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" class="size-4 shrink-0">
                        <rect x="1" y="3" width="15" height="13"/>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/>
                        <circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    Miễn phí vận chuyển đơn từ 500.000đ
                </li>
                <li class="flex items-center gap-2 text-xs text-base-content/50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" class="size-4 shrink-0">
                        <polyline points="23 4 23 10 17 10"/>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                    </svg>
                    Đổi trả miễn phí trong 30 ngày
                </li>
            </ul>

        </div>
    </div>
</div>

{{-- Toast --}}
@if (session('success'))
    <x-toast type="success" :messages="[session('success')]" />
@endif

@endsection