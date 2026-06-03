{{-- ===== NAVBAR CHÍNH ===== --}}
<nav class="bg-base-100 border-b border-base-200 sticky top-0">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center gap-3">

        {{-- Hamburger (drawer) --}}
        <label for="my-drawer-4" class="btn btn-ghost btn-square btn-sm rounded-xl
                      hover:bg-base-200 cursor-pointer lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </label>

        {{-- Logo --}}
        <a href="{{ route('index') }}" class="text-xl font-extrabold tracking-tight text-base-content shrink-0">
            Azus
        </a>

        {{-- Ô tìm kiếm --}}
        <form class="input input-sm flex-1 max-w-md rounded-xl border-base-200
                      focus-within:border-error focus-within:outline-none
                      bg-base-200/60 transition-colors mx-auto" action="{{ route('products.index') }}" method="GET">
            <svg class="h-4 w-4 opacity-40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </g>
            </svg>
            <input type="search" name="search" class="grow text-sm" placeholder="Tìm kiếm sản phẩm..." />
        </form>

        <div class="flex">
            <a href="{{ route('orders.lookup') }}" class="btn btn-ghost btn-sm rounded-xl">
                Tra cứu đơn hàng
            </a>

            <div class="indicator shrink-0">
                <span class="indicator-item badge badge-error badge-xs font-bold">
                    {{ Session::get('cart') ? count(Session::get('cart')) : 0 }}
                </span>
                <a href="{{ route('cart.index') }}" class="btn btn-ghost btn-square btn-sm rounded-xl
                      hover:bg-error/10 hover:text-error group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        class="size-5 group-hover:stroke-error transition-colors">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- ===== SUBNAV: Category & Brand ===== --}}
<div class="bg-base-100 border-b border-base-200 flex justify-center gap-1 px-4">

    {{-- Dropdown Category --}}
    <div class="dropdown dropdown-bottom dropdown-center">
        <div tabindex="0" role="button" class="btn btn-ghost btn-sm rounded-xl gap-1.5 font-medium text-sm">
            Danh mục
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="size-3.5">
                <path d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </div>

        <ul tabindex="0" class="dropdown-content menu bg-base-100 border border-base-200
                   rounded-2xl shadow-lg p-2 min-w-[180px] z-50">
            @foreach ($categories as $column)
                @foreach ($column as $item)
                    <li>
                        <a href="{{ route('products.index', ['category' => $item->slug]) }}" class="rounded-xl text-sm">
                            {{ $item->name }}
                        </a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>

    {{-- Dropdown Brand --}}
    <div class="dropdown dropdown-bottom dropdown-center">
        <div tabindex="0" role="button" class="btn btn-ghost btn-sm rounded-xl gap-1.5 font-medium text-sm">
            Thương hiệu
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="size-3.5">
                <path d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </div>

        <ul tabindex="0" class="dropdown-content menu bg-base-100 border border-base-200
                   rounded-2xl shadow-lg p-2 min-w-[160px] z-50">
            @foreach ($brands as $column)
                @foreach ($column as $item)
                    <li>
                        <a href="{{ route('products.index', ['brand' => $item->slug]) }}" class="rounded-xl text-sm">
                            {{ $item->name }}
                        </a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>

</div>