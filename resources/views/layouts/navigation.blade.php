<nav x-data="{ sidebarOpen: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Hamburger for sidebar (mobile only) -->
            <div class="flex items-center lg:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <!-- Logo -->
            <div class="shrink-0 flex items-center min-w-[60px]">
                <a href="{{ route('home') }}">
                    <x-application-logo class="block h-12 w-auto" />
                </a>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <div :class="[sidebarOpen ? 'w-64 left-0' : 'w-0 -left-64', 'fixed top-0 h-full bg-white shadow-lg overflow-hidden transition-all duration-300 ease-in-out z-50 border-r border-gray-200 lg:hidden']">
        <!-- Close button for mobile -->
        <div class="flex justify-end lg:hidden p-2">
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('home') ? 'bg-gray-100' : '' }}">
                Home
            </a>
            <a href="{{ route('lyrics.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('lyrics.index') ? 'bg-gray-100' : '' }}">
                Lyrics
            </a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : '' }}">
                        Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-100' : '' }}">
                        My Dashboard
                    </a>
                @endif
                <a href="{{ route('bookmarks.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('bookmarks.index') ? 'bg-gray-100' : '' }}">
                    My Bookmarks
                </a>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-gray-100' : '' }}">
                    Profile Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-lg">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('login') ? 'bg-gray-100' : '' }}">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('register') ? 'bg-gray-100' : '' }}">
                    Register
                </a>
            @endauth
        </nav>
    </div>
</nav>
