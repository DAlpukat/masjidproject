<?php
 $logoRoute = 'home';

if (auth()->check()) {
    if (auth()->user()->is_admin) {
        // Jika Admin, Logo menuju Dashboard Admin
        $logoRoute = 'admin.dashboard';
    } else {
        // Jika User Biasa, Logo menuju Ruangan Saya
        $logoRoute = 'user.rooms';
    }
}
?>

<nav x-data="{ open: false }" class="">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side: Logo & Links -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route($logoRoute) }}" class="flex items-center gap-2 group">
                        <!-- Ganti logo dengan text gradient agar modern, atau pakai komponen asli -->
                        <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">
                            MyLayanan
                        </span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-1 sm:-my-px sm:ml-8 sm:flex items-center">
                    
                    <!-- Menu Admin Dashboard -->
                    @if(auth()->check() && auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" 
                           class="{{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'text-gray-500 hover:text-pink-600' }} px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Admin Dashboard
                        </a>
                    @endif

                    <!-- Menu Ruangan Saya -->
                    <a href="{{ route('user.rooms') }}" 
                       class="{{ request()->routeIs('user.rooms') ? 'nav-link-active' : 'text-gray-500 hover:text-pink-600' }} px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        Ruangan Saya
                    </a>

                    <!-- Menu Gabung Room (User Biasa) -->
                    @if(auth()->check() && !auth()->user()->is_admin)
                        <a href="{{ route('home') }}" 
                           class="{{ request()->routeIs('home') ? 'nav-link-active' : 'text-gray-500 hover:text-pink-600' }} px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Cari Room
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Side: Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-xl text-gray-700 bg-white/50 hover:bg-white hover:text-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all duration-200">
                           <span class="mr-2">{{ auth()->user()->name ?? auth()->user()->email ?? 'User' }}</span>
                           <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Dropdown Content Container -->
                        <div class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-100 rounded-xl shadow-xl">
                            <x-dropdown-link :href="route('profile.edit')" class="block w-full text-left px-4 py-2 hover:bg-pink-50 hover:text-pink-600 rounded-lg transition-colors">
                                Profile Settings
                            </x-dropdown-link>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors font-medium">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-pink-600 hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-pink-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open }" class="hidden sm:hidden border-t border-gray-100 bg-white/90 backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1 px-2">
            
            @if(auth()->check() && auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-pink-50 text-pink-700' : 'text-gray-600 hover:bg-gray-50' }} block pl-3 pr-4 py-2 rounded-lg text-base font-medium">
                    Admin Dashboard
                </a>
            @endif

            <a href="{{ route('user.rooms') }}" class="{{ request()->routeIs('user.rooms') ? 'bg-pink-50 text-pink-700' : 'text-gray-600 hover:bg-gray-50' }} block pl-3 pr-4 py-2 rounded-lg text-base font-medium">
                Ruangan Saya
            </a>

            @if(auth()->check() && !auth()->user()->is_admin)
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-pink-50 text-pink-700' : 'text-gray-600 hover:bg-gray-50' }} block pl-3 pr-4 py-2 rounded-lg text-base font-medium">
                    Cari Room
                </a>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-base text-lg font-bold text-gray-800">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block pl-3 pr-4 py-2 rounded-lg text-base font-medium text-red-600 hover:bg-red-50">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>