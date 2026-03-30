<footer class="bg-purple-950 border-t-2 border-gold-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-0.5">
                    <div class="w-10 h-10 bg-gold-500 rounded-full flex items-center justify-center mx-auto  shadow-xl">
                        <span class="font-display font-bold text-purple-950 text-2xl">3G</span>
                    </div>
                    <div class="tt" style="margin-right: 64px;">
                        <div class="font-bold text-white text-sm leading-none">3Gites-1975</div>
                        <div class="text-purple-400 text-xs">Class of 1975</div>
                    </div>
                </a>
                <p class="text-purple-300 text-sm italic font-display mt-3 leading-relaxed">"Perstare et Praestare"</p>
                <p class="text-purple-500 text-xs mt-0.5">To Persevere and Excel</p>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider mb-3">About Us</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Home</a></li>
                    <li><a href="{{ route('about') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">About</a></li>
                    <li><a href="{{ route('leadership') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Leadership</a></li>
                    <li><a href="{{ route('contact.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider mb-3">Members</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('members.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Directory</a></li>
                    <li><a href="{{ route('gallery.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Gallery</a></li>
                    <li><a href="{{ route('tributes.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">In Memoriam</a></li>
                    <li><a href="{{ route('guestbook.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Guestbook</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider mb-3">Community</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('events.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Events</a></li>
                    <li><a href="{{ route('posts.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Message Board</a></li>
                    @auth
                    <li><a href="{{ route('polls.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Polls</a></li>
                    <li><a href="{{ route('store.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Store</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider mb-3">Support</h4>
                <ul class="space-y-2">
                    @auth
                    <li><a href="{{ route('donate.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Donate</a></li>
                    @endauth
                    <li><a href="{{ route('contact.index') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Contact Us</a></li>
                    @guest
                    <li><a href="{{ route('login') }}" class="text-purple-400 hover:text-gold-400 text-sm transition-colors">Member Login</a></li>
                    @endguest
                </ul>
            </div>
        </div>

        <div class="border-t border-purple-900 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-purple-500 text-xs">&copy; {{ date('Y') }} 3Gites-1975. All rights reserved.</p>
            <p class="text-purple-600 text-xs">Clarendon College &bull; Manchester, Jamaica &bull; Est. 1913</p>
        </div>
    </div>
</footer>
