@extends('client.layouts.index')
@section('title', "Sản phẩm")

@section('content')

    <x-breadcrumbs :items="[
            ['title' => 'Home', 'url' => route('index')],
            ['title' => 'Sản phẩm']
        ]" />

    <div class="mt-8">
        <div class="mb-8">
            <h1 class="text-center font-bold text-3xl md:text-4xl text-base-content">📦 Danh sách sản phẩm</h1>
            <p class="text-center text-base-content/60 mt-2 text-sm md:text-base">Khám phá hàng trăm sản phẩm chất lượng cao</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Filter Sidebar --}}
            <aside class="lg:col-span-1">
                <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                    {{-- Search --}}
                    <div>
                        <label class="block text-sm font-semibold text-base-content mb-2">🔍 Tìm kiếm</label>
                        <input type="text" name="search" placeholder="Tên sản phẩm..." 
                            value="{{ request('search') }}"
                            class="input input-bordered input-sm w-full rounded-lg" />
                    </div>

                    {{-- Price Range --}}
                    <div class="divider my-4"></div>
                    <div>
                        <label class="block text-sm font-semibold text-base-content mb-3">💰 Khoảng giá</label>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-base-content/60">Giá từ</label>
                                <input type="number" name="price_min" placeholder="0" 
                                    value="{{ request('price_min') }}"
                                    min="0" step="1000"
                                    class="input input-bordered input-sm w-full rounded-lg" />
                            </div>
                            <div>
                                <label class="text-xs text-base-content/60">Giá đến</label>
                                <input type="number" name="price_max" placeholder="999999999" 
                                    value="{{ request('price_max') }}"
                                    min="0" step="1000"
                                    class="input input-bordered input-sm w-full rounded-lg" />
                            </div>
                        </div>
                    </div>

                    {{-- Quick Price Filters --}}
                    <div class="space-y-2">
                        <a href="{{ route('products.index', ['price_min' => 0, 'price_max' => 100000]) }}"
                            class="btn btn-ghost btn-xs w-full justify-start text-left">
                            🏷️ Dưới 100K
                        </a>
                        <a href="{{ route('products.index', ['price_min' => 100000, 'price_max' => 500000]) }}"
                            class="btn btn-ghost btn-xs w-full justify-start text-left">
                            🏷️ 100K - 500K
                        </a>
                        <a href="{{ route('products.index', ['price_min' => 500000, 'price_max' => 1000000]) }}"
                            class="btn btn-ghost btn-xs w-full justify-start text-left">
                            🏷️ 500K - 1M
                        </a>
                        <a href="{{ route('products.index', ['price_min' => 1000000]) }}"
                            class="btn btn-ghost btn-xs w-full justify-start text-left">
                            🏷️ Trên 1M
                        </a>
                    </div>

                    {{-- Sort --}}
                    <div class="divider my-4"></div>
                    <div>
                        <label class="block text-sm font-semibold text-base-content mb-2">📊 Sắp xếp</label>
                        <select name="sort" class="select select-bordered select-sm w-full rounded-lg">
                            <option value="">Mặc định</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="space-y-2 pt-4">
                        <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg">
                            🔍 Tìm kiếm
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-ghost btn-sm w-full rounded-lg">
                            ↻ Đặt lại
                        </a>
                    </div>
                </form>
            </aside>

            {{-- Products Grid --}}
            <div class="lg:col-span-3">
                @if ($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                        @foreach ($products as $product)
                            <x-client.product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <p class="text-base-content/40 text-lg">Không tìm thấy sản phẩm nào</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm mt-4 rounded-lg">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (session('success'))
        <x-toast type="success" :messages="[session('success')]" />
    @endif

@endsection