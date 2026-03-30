<nav
    x-data="{ open: false, scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 20"
    :class="scrolled ? 'bg-white/95 backdrop-blur-sm shadow-sm border-b border-slate-100' : 'bg-purple-950/95 backdrop-blur-sm'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-10 h-10 bg-gold-500 rounded-full flex items-center justify-center mx-auto  shadow-xl">
                    <span class="font-display font-bold text-purple-950 text-2xl">3G</span>
                </div>
                <div class="hidden sm:block">
                    <div :class="scrolled ? 'text-purple-950' : 'text-white'" class="font-bold text-sm leading-tight transition-colors">3Gites-1975</div>
                    <div :class="scrolled ? 'text-slate-500' : 'text-purple-300'" class="text-xs transition-colors">Class of 1975</div>
                </div>
            </a>

            {{-- Desktop nav links --}}
            <div class="hidden lg:flex items-center gap-1">
                @auth
                <a href="{{ route('dashboard') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'font-bold' : '' }}">Dashboard</a>
                @endauth
                <a href="{{ route('members.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('members.*') ? 'font-bold' : '' }}">Members</a>
                <a href="{{ route('gallery.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('gallery.*') ? 'font-bold' : '' }}">Gallery</a>
                <a href="{{ route('events.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('events.*') ? 'font-bold' : '' }}">Events</a>
                <a href="{{ route('posts.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('posts.*') ? 'font-bold' : '' }}">Board</a>
                @auth
                <a href="{{ route('polls.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('polls.*') ? 'font-bold' : '' }}">Polls</a>
                <a href="{{ route('donate.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('donate.*') ? 'font-bold' : '' }}">Donate</a>
                <a href="{{ route('store.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('store.*') ? 'font-bold' : '' }}">Store</a>
                @endauth
                <a href="{{ route('tributes.index') }}" :class="scrolled ? 'text-slate-700 hover:text-purple-900' : 'text-purple-200 hover:text-white'" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('tributes.*') ? 'font-bold' : '' }}">In Memoriam</a>
                @can('admin')
                <a href="{{ route('admin.dashboard') }}" :class="scrolled ? 'text-purple-700 hover:text-purple-900' : 'text-gold-400 hover:text-gold-300'" class="px-3 py-2 text-sm font-bold rounded-lg transition-colors {{ request()->routeIs('admin.*') ? 'underline' : '' }}">Admin</a>
                @endcan
            </div>

            {{-- Right side --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth
                <div x-data="{ userMenu: false }" class="relative">
                    <button
                        @click="userMenu = !userMenu"
                        @click.away="userMenu = false"
                        class="flex items-center gap-2 focus:outline-none"
                    >
                        <div class="w-9 h-9 rounded-full bg-purple-700 border-2 border-purple-600 flex items-center justify-center text-white font-display font-bold text-sm overflow-hidden flex-shrink-0">
                            @php $headerPhoto = auth()->user()->photo_recent ?? auth()->user()->profile?->recent_photo ?? null; @endphp
                            @if($headerPhoto)
                                <img src="{{ Storage::url($headerPhoto) }}" class="w-full h-full object-cover" alt="{{ auth()->user()->name }}">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <span :class="scrolled ? 'text-slate-700' : 'text-white'" class="text-sm font-medium transition-colors">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4" :class="scrolled ? 'text-slate-400' : 'text-purple-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div
                        x-show="userMenu"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50"
                    >
                        @if(auth()->user()->member)
                        <a href="{{ route('members.show', auth()->user()->member) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-purple-900 transition-colors">My Profile</a>
                        @endif
                        <a href="{{ route('password.change') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-purple-900 transition-colors">Change Password</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Sign Out</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="bg-gold-500 hover:bg-gold-400 text-purple-950 font-bold px-4 py-2 rounded-xl text-sm transition-colors shadow">Login</a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button
                @click="open = !open"
                class="lg:hidden p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
                :class="scrolled ? 'text-slate-700' : 'text-white'"
                aria-label="Toggle menu"
            >
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu overlay --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-purple-950 border-t border-purple-900 px-4 py-4 space-y-1"
    >
        @auth
        <a href="{{ route('dashboard') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Dashboard</a>
        @endauth
        <a href="{{ route('members.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Members</a>
        <a href="{{ route('gallery.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Gallery</a>
        <a href="{{ route('events.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Events</a>
        <a href="{{ route('posts.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Message Board</a>
        @auth
        <a href="{{ route('polls.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Polls</a>
        <a href="{{ route('donate.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Donate</a>
        <a href="{{ route('store.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">Store</a>
        @endauth
        <a href="{{ route('tributes.index') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">In Memoriam</a>
        <a href="{{ route('about') }}" @click="open=false" class="block px-3 py-2.5 text-purple-200 hover:text-white hover:bg-purple-900 rounded-lg text-sm font-medium transition-colors">About</a>
        <div class="border-t border-purple-900 pt-3 mt-3">
            @auth
            <div class="flex items-center gap-3 px-3 pb-3">
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center text-white font-bold text-sm overflow-hidden flex-shrink-0">
                    @php $mobilePhoto = auth()->user()->photo_recent ?? auth()->user()->profile?->recent_photo ?? null; @endphp
                    @if($mobilePhoto)
                        <img src="{{ Storage::url($mobilePhoto) }}" class="w-full h-full object-cover" alt="{{ auth()->user()->name }}">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <span class="text-white font-medium text-sm">{{ auth()->user()->name }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2.5 text-red-400 hover:text-red-300 text-sm font-medium transition-colors">Sign Out</button>
            </form>
            @else
            <a href="{{ route('login') }}" @click="open=false" class="block bg-gold-500 hover:bg-gold-400 text-purple-950 font-bold px-4 py-3 rounded-xl text-sm transition-colors text-center">Sign In</a>
            @endauth
        </div>
    </div>
</nav>
