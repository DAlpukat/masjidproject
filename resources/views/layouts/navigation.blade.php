<?php
// LOGIKA REDIRECT LOGO
 $logoRoute = 'home'; // Default

if (auth()->check()) {
    if (auth()->user()->is_superadmin) {
        $logoRoute = 'superadmin.dashboard';
    } elseif (auth()->user()->is_admin) {
        $logoRoute = 'admin.dashboard';
    } else {
        $logoRoute = 'user.rooms';
    }
}
?>

<!-- NAVIGATION: Dark Glass Theme -->
<nav x-data="{ open: false }" class="fixed w-full z-50 nav-glass top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> 
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route($logoRoute) }}" class="flex items-center gap-2 group">
                        <span class="text-2xl font-black text-white tracking-wider border-b-2 border-transparent hover:border-white">
                            TRANS<span class="text-gray-400">PARANSI</span>
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-1 sm:-my-px sm:ml-8 sm:flex items-center">
                
                @if(auth()->check() && auth()->user()->is_superadmin)
                    <a href="{{ route('superadmin.dashboard') }}" 
                    class="bg-white/10 text-white border border-white/20 hover:bg-white/20 {{ request()->routeIs('superadmin.*') ? 'bg-white/20 ring-1 ring-white' : '' }} px-4 py-2 rounded-full text-sm font-bold">
                        ⚡ Superadmin Panel
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->is_admin && !auth()->user()->is_superadmin)
                    <a href="{{ route('admin.dashboard') }}" 
                    class="{{ request()->routeIs('admin.dashboard') ? 'text-white bg-white/20' : 'text-gray-400 hover:text-white hover:bg-white/10' }} px-3 py-2 rounded-md text-sm font-medium">
                        Admin Dashboard
                    </a>
                @endif

                @if(auth()->check() && !auth()->user()->is_superadmin)
                <a href="{{ route('user.rooms') }}" 
                    class="{{ request()->routeIs('user.rooms') ? 'text-white bg-white/20' : 'text-gray-300 hover:text-white hover:bg-white/10' }} px-4 py-2 rounded-lg text-sm font-bold">
                    Ruangan Saya
                </a>
                @endif

                @if(auth()->check() && !auth()->user()->is_admin && !auth()->user()->is_superadmin)
                <a href="{{ route('home') }}" 
                class="{{ request()->routeIs('home') ? 'text-white bg-white/20' : 'text-gray-300 hover:text-white hover:bg-white/10' }} px-4 py-2 rounded-lg text-sm font-bold">
                    Cari Room
                </a>
                @endif
            </div>
            </div>
           <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }">
                    
                    <button @click="open = !open" @click.outside="open = false" 
                        class="inline-flex items-center px-4 py-2 border border-white/20 text-sm leading-4 font-medium rounded-full text-white bg-white/5 hover:bg-white/10 hover:border-white/40 focus:outline-none transition-all duration-300">
                        
                        <span class="mr-2 font-semibold">{{ auth()->user()->name ?? 'Super Admin' }}</span>
                        
                        @if(auth()->user()->is_superadmin)
                            <span class="text-[10px] bg-purple-500/30 text-purple-200 border border-purple-500/50 px-1.5 py-0.5 rounded font-bold mr-1">SA</span>
                        @elseif(auth()->user()->is_admin)
                            <span class="text-[10px] bg-blue-500/30 text-blue-200 border border-blue-500/50 px-1.5 py-0.5 rounded font-bold mr-1">ADM</span>
                        @endif

                        <svg class="fill-current h-4 w-4 opacity-70 transition-transform duration-300" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                        class="absolute right-0 mt-3 w-48 rounded-2xl shadow-2xl bg-[#121212]/95 backdrop-blur-xl border border-white/10 py-2 z-[60] origin-top-right"
                        style="display: none;">
                        
                        <div class="px-4 py-1.5 border-b border-white/5 mb-1">
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Pengaturan</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
                            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profile Settings
                        </a>

                        <div class="border-t border-white/5 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors font-medium">
                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open }" class="hidden sm:hidden border-t border-white/10 bg-black/90 backdrop-blur-xl">
        <div class="pt-4 pb-4 space-y-2 px-4">

            @if(auth()->check() && auth()->user()->is_admin && !auth()->user()->is_superadmin)
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} block pl-3 pr-4 py-3 rounded-xl text-base font-medium transition-colors">
                    Admin Dashboard
                </a>
            @endif

            <a href="{{ route('user.rooms') }}" class="{{ request()->routeIs('user.rooms') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} block pl-3 pr-4 py-3 rounded-xl text-base font-medium transition-colors">
                Ruangan Saya
            </a>

            @if(auth()->check() && !auth()->user()->is_admin)
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} block pl-3 pr-4 py-3 rounded-xl text-base font-medium transition-colors">
                    Cari Room
                </a>
            @endif
        </div>

        <div class="pt-4 pb-4 border-t border-white/10 bg-black/50">
            <div class="px-4 mb-4">
                <div class="text-base font-bold text-white">{{ auth()->user()->name }}</div>
                <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-lg text-base font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>