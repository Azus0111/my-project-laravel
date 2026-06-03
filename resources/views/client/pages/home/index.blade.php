@extends('client.layouts.index')
@section('title', 'Trang chủ')

@section('content')

    <x-breadcrumbs :items="[['title' => 'Home']]" />

    {{-- Hero Banner --}}
    <section class="mt-6 mb-10 rounded-2xl overflow-hidden bg-gradient-to-r from-error to-warning shadow-lg">
        <div class="px-6 md:px-12 py-12 md:py-20 text-white">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Chào mừng bạn!</h1>
            <p class="text-sm md:text-base opacity-90 mb-6 max-w-lg">Khám phá bộ sưu tập sản phẩm mới nhất và ưu đãi đặc biệt dành riêng cho bạn.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm md:btn-md rounded-xl">
                Khám phá ngay ✨
            </a>
        </div>
    </section>

    {{-- Sản phẩm mới --}}
    <section class="mt-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-base-content">🆕 Sản phẩm mới nhất</h2>
                <p class="text-xs md:text-sm text-base-content/60 mt-1">Những sản phẩm vừa được cập nhật</p>
            </div>
            <a href="{{ route('products.index') }}"
               class="btn btn-ghost btn-sm rounded-xl text-error text-xs hover:bg-error/10">
                Xem tất cả →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @forelse ($newProducts as $product)
                <x-client.product-card :product="$product" />
            @empty
                <p class="col-span-full text-center text-base-content/40 py-16 text-sm">
                    Chưa có sản phẩm nào.
                </p>
            @endforelse
        </div>
    </section>

    {{-- Sản phẩm hot --}}
    <section class="mt-12">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-base-content">🔥 Sản phẩm phổ biến</h2>
                <p class="text-xs md:text-sm text-base-content/60 mt-1">Những sản phẩm được yêu thích nhất</p>
            </div>
            <a href="{{ route('products.index', ['sort' => 'views']) }}"
               class="btn btn-ghost btn-sm rounded-xl text-error text-xs hover:bg-error/10">
                Xem tất cả →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @forelse ($hotProducts as $product)
                <x-client.product-card :product="$product" />
            @empty
                <p class="col-span-full text-center text-base-content/40 py-16 text-sm">
                    Chưa có sản phẩm nào.
                </p>
            @endforelse
        </div>
    </section>

    {{-- Sản phẩm khuyến mãi --}}
    <section class="mt-12 mb-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-base-content">🏷️ Khuyến mãi đặc biệt</h2>
                <p class="text-xs md:text-sm text-base-content/60 mt-1">Giảm giá lên đến 70%</p>
            </div>
            <a href="{{ route('products.index', ['sort' => 'discount']) }}"
               class="btn btn-ghost btn-sm rounded-xl text-error text-xs hover:bg-error/10">
                Xem tất cả →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @forelse ($discountProducts as $product)
                <x-client.product-card :product="$product" />
            @empty
                <p class="col-span-full text-center text-base-content/40 py-16 text-sm">
                    Chưa có sản phẩm nào.
                </p>
            @endforelse
        </div>
    </section>

    @if (session('success'))
        <x-toast type="success" :messages="[session('success')]" />
    @endif

@endsection