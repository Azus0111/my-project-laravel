<div class="navbar bg-base-100 shadow-sm">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay hover-3d cursor-pointer"> <svg width="20"
            height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            class="inline-block h-5 w-5 stroke-current md:h-6 md:w-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </label>
    <div class="flex-1">
        <a class="btn btn-ghost text-xl">Azus</a>
    </div>
    <div class="flex gap-2 items-center">
        <label>{{ Auth::user()->name ?? "Guest" }}</label>
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img alt="?" src="{{ asset('storage/' . Auth::user()->avatar) }}" />
                </div>
            </div>
            <ul tabindex="-1" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li>
                    <a class="justify-between">
                        Profile
                        <span class="badge">New</span>
                    </a>
                </li>
                <li><a>Settings</a></li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <li>
                        <button type="submit" class="w-full">Logout</button>
                    </li>
                </form>
            </ul>
        </div>
    </div>
</div>