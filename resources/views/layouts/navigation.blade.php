<nav x-data="{ open: false }" class="bg-gradient-to-r from-green-600 to-green-700 border-b border-green-800 shadow-md fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">

        <!-- Logo + Judul (Kiri) -->
        <a href="{{ route('dashboard.public') }}" class="flex items-center space-x-3 shrink-0">
            <img src="{{ asset('images/logo-desa-gunungsari.png') }}" 
                 alt="Logo" 
                 class="h-9 w-auto"
                 onerror="this.onerror=null; this.src='data:image/svg+xml,...'">
            <div class="hidden sm:block leading-tight">
                <p class="text-sm font-bold text-white">SIPENDUDUK</p>
                <p class="text-[10px] text-green-200">Desa Gunungsari, Batu, Malang</p>
            </div>
        </a>

        <!-- Navigasi (Tengah) - Desktop -->
        <div class="hidden md:flex items-center space-x-1">
            <a href="{{ route('dashboard.public') }}" class="text-white hover:bg-green-500 rounded-md px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('dashboard.public') ? 'bg-green-500' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('penduduk.index') }}" class="text-white hover:bg-green-500 rounded-md px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('penduduk.*') ? 'bg-green-500' : '' }}">
                Data Penduduk
            </a>
            <a href="{{ route('analisis.index') }}" class="text-white hover:bg-green-500 rounded-md px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('analisis.*') ? 'bg-green-500' : '' }}">
                Analisis
            </a>
            <a href="{{ route('peramalan.index') }}" class="text-white hover:bg-green-500 rounded-md px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('peramalan.*') ? 'bg-green-500' : '' }}">
                Peramalan
            </a>
            <a href="{{ route('riwayat.index') }}" class="text-white hover:bg-green-500 rounded-md px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('riwayat.*') ? 'bg-green-500' : '' }}">
                Riwayat
            </a>
            <a href="{{ route('laporan.index') }}" class="text-white hover:bg-green-500 rounded-md px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('laporan.*') ? 'bg-green-500' : '' }}">
                Laporan
            </a>
        </div>

        <!-- Kanan: Login / User Dropdown -->
        <div class="flex items-center space-x-2">
            @auth
                <div class="hidden sm:flex items-center">
                    <x-dropdown align="right" width="40">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-white hover:text-green-200 py-1 px-2 rounded-md hover:bg-green-500 transition whitespace-nowrap">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="hover:bg-green-50 text-sm">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-green-50 text-sm">Log Out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-white hover:bg-green-500 rounded-md px-3 py-1.5 text-sm font-medium transition">
                    Login
                </a>
            @endauth

            <!-- Hamburger (Mobile) -->
            <button @click="open = ! open" 
                class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-white hover:text-green-200 hover:bg-green-500 focus:outline-none transition">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden bg-green-700 border-t border-green-600">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard.public') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Dashboard</a>
            <a href="{{ route('penduduk.index') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Data Penduduk</a>
            <a href="{{ route('analisis.index') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Analisis</a>
            <a href="{{ route('peramalan.index') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Peramalan</a>
            <a href="{{ route('riwayat.index') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Riwayat</a>
            <a href="{{ route('laporan.index') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Laporan</a>
        </div>
        <div class="pt-4 pb-1 border-t border-green-600">
            @auth
                <div class="px-4">
                    <div class="font-medium text-sm text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-green-200">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Log Out</a>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-white text-sm hover:bg-green-600">Login</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Spacer untuk navbar fixed -->
<div class="h-14"></div>