<header class="bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 py-3 px-6 md:px-10 sticky top-0 z-40 transition-colors duration-200">
    <div class="flex items-center justify-between">
        
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-[#4D9BE2] p-2 rounded-xl hover:bg-[#F3F7FB] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <div class="text-md text-[#4D9BE2] py-2 rounded-xl font-semibold self-start md:self-center flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 shrink-0">
                    <rect x="3" y="4" width="18" height="16" rx="3.5" ry="3.5"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2 md:gap-2">
            
            <div x-data="{ open: false }" @click.away="open = false" class="relative py-1">
                @php
                    $notifications = Auth::user()->notifications()->take(5)->get();
                    $unreadCount = Auth::user()->unreadNotifications()->count();
                @endphp
                <button @click="open = !open" class="relative p-3 rounded-2xl bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] hover:bg-[#e6f0f8] dark:hover:bg-slate-700 hover:scale-105 transition-all duration-300 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($unreadCount > 0)
                        <span class="absolute top-1.5 right-1.5 h-3.5 w-3.5 bg-red-500 rounded-full border-2 border-white dark:border-slate-800 flex items-center justify-center text-[8px] text-white font-bold">{{ $unreadCount }}</span>
                    @endif
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                     class="absolute right-0 top-full pt-2 w-80 z-50"
                     style="display: none;">
                     
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-4">
                        <div class="flex items-center justify-between border-b border-gray-50 dark:border-slate-800 pb-3 mb-3">
                            <p class="text-xs text-gray-500 dark:text-slate-400 uppercase font-bold tracking-widest">Notifikasi</p>
                            @if($unreadCount > 0)
                                <a href="{{ route('notifications.readAll') }}" class="text-[10px] text-[#4D9BE2] hover:underline font-bold">Tandai dibaca</a>
                            @endif
                        </div>
                        
                        <div class="space-y-3 max-h-64 overflow-y-auto custom-scrollbar">
                            @forelse($notifications as $notif)
                                <div class="p-2.5 rounded-xl transition-all {{ $notif->read_at ? 'bg-transparent' : 'bg-[#F3F7FB]/50 dark:bg-slate-800/30 border-l-2 border-[#4D9BE2]' }}">
                                    <div class="flex items-start gap-2.5">
                                        <div class="mt-0.5 flex-shrink-0">
                                            @if(($notif->data['type'] ?? '') === 'success')
                                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-emerald-100 dark:bg-emerald-950/30 text-emerald-500">
                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @elseif(($notif->data['type'] ?? '') === 'danger')
                                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-red-100 dark:bg-red-950/30 text-red-500">
                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100 dark:bg-blue-950/30 text-blue-500">
                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 text-left leading-tight">
                                            <p class="text-xs font-bold text-[#2F3951] dark:text-slate-200">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                                            <p class="text-[10px] text-gray-500 dark:text-slate-400 mt-0.5 leading-normal">{{ $notif->data['message'] ?? '' }}</p>
                                            <p class="text-[8px] text-gray-400 dark:text-slate-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-6 text-center text-xs text-gray-400 dark:text-slate-500">Tidak ada notifikasi baru.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-8 w-[1px] bg-gray-100 dark:bg-slate-800 mx-1"></div>

            <div x-data="{ open: false }" @click.away="open = false" class="relative py-1">
                
            <button @click="open = !open" class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all duration-300 focus:outline-none">
                <div class="h-10 w-10 md:h-11 md:w-11 rounded-full overflow-hidden border-2 border-[#4D9BE2]/20 shadow-sm flex items-center justify-center bg-[#4D9BE2] text-white font-bold">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                
                <div class="text-left hidden md:block leading-tight">
                    <p class="text-sm font-bold text-[#2F3951] dark:text-slate-100">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-400 dark:text-slate-400 font-medium capitalize mt-0.5">{{ Auth::user()->role }} MacaBae</p>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="h-4 w-4 text-gray-400 transition-transform duration-300 ease-in-out" 
                    :style="open ? 'transform: rotate(180deg);' : 'transform: rotate(0deg);'"
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                     class="absolute right-0 top-full pt-2 w-56 z-50"
                     style="display: none;">
                     
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-2">
                        <div class="px-4 py-3 border-b border-gray-50 dark:border-slate-800 mb-1">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Pengaturan Akun</p>
                        </div>
                        
                        <a href="{{ Auth::user()->role === 'member' ? route('member.pengaturan.index') : route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-slate-300 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2] transition-all group/item">
                            <div class="p-2 rounded-lg bg-gray-50 dark:bg-slate-800 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Profil Lengkap</span>
                        </a>

                        <a href="{{ Auth::user()->role === 'member' ? route('member.pengaturan.index') : route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-slate-300 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2] transition-all group/item">
                            <div class="p-2 rounded-lg bg-gray-50 dark:bg-slate-800 group-hover/item:bg-white dark:group-hover/item:bg-slate-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Ubah Password</span>
                        </a>

                        <div class="my-1 border-t border-gray-50 dark:border-slate-800"></div>

                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all font-semibold text-left">
                                <div class="p-2 rounded-lg bg-red-100/50 dark:bg-red-950/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>