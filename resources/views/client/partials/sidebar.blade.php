<div class="drawer-side is-drawer-close:overflow-visible">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
    <!-- Sidebar content here -->
    <ul class="menu bg-base-200 min-h-full w-80 p-4">
        <li>
            <div class="flex-1">
                <a class="btn btn-ghost text-xl">Azus</a>
            </div>
        </li>
        <!-- List item -->
        <li class="{{ request()->routeIs('index') ? "menu-active" : '' }}">
            <a href="{{ route('index') }}">
                <!-- Home icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round"
                    stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor"
                    class="my-1.5 inline-block size-4">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                    <path
                        d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                    </path>
                </svg>
                <span>Home</span>
            </a>
        </li>

        <li>
            <details close>
                <summary>Parent</summary>
                <ul>
                    <li><a>Submenu 1</a></li>
                    <li><a>Submenu 2</a></li>
                    <li>
                        <details close>
                            <summary>Parent</summary>
                            <ul>
                                <li><a>Submenu 1</a></li>
                                <li><a>Submenu 2</a></li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </details>
        </li>
    </ul>
</div>