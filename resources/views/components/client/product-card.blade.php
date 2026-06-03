@props(['product'])

<div class="group relative bg-white rounded-2xl border border-base-200 overflow-hidden
            transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">

    {{-- Ảnh sản phẩm --}}
    <div class="relative overflow-hidden h-44 bg-base-200">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
        </a>

        @if ($product->discount_percent)
            <span class="absolute top-2.5 left-2.5 badge badge-error badge-sm font-bold">
                −{{ $product->discount_percent }}%
            </span>
        @endif
    </div>

    {{-- Nội dung card --}}
    <div class="p-3.5 flex flex-col gap-2">

        {{-- Tên sản phẩm --}}
        <a href="{{ route('products.show', $product->slug) }}" class="text-sm font-semibold text-base-content leading-snug
                  line-clamp-2 hover:text-error transition-colors duration-150">
            {{ $product->name }}
        </a>

        {{-- Giá --}}
        <div class="flex items-baseline gap-1.5">
            <span class="text-base font-bold text-error">
                {{ number_format($product->final_price ?? $product->price, 0, ',', '.') }}đ
            </span>

            @if ($product->discount_percent)
                <span class="text-xs text-base-content/40 line-through">
                    {{ number_format($product->price, 0, ',', '.') }}đ
                </span>
            @endif
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-base-200">

            <div class="rating rating-xs">
                @for ($i = 1; $i <= 5; $i++)
                    <input type="radio" class="mask mask-star-2 bg-warning" {{ $i === 0 ? 'checked' : '' }} disabled />
                @endfor
            </div>

            @if ($product)
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-ghost btn-sm btn-square rounded-xl
                               text-error hover:bg-error/10 tooltip tooltip-left" data-tip="Thêm vào giỏ">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                    </button>
                </form>
            @else
                <span class="text-[10px] font-semibold uppercase tracking-wide text-base-content/30">
                    Hết hàng
                </span>
            @endif
        </div>
    </div>
</div>