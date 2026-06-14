<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') - {{ config('app.name', 'MacaBae') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Icon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/brand/logo.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F3F7FB] dark:bg-slate-950 antialiased h-screen overflow-hidden text-[#2F3951] dark:text-slate-100 transition-colors duration-200">
    <div class="flex h-full w-full">
        <div class="w-72 h-full flex-shrink-0 hidden md:block">
            @if(Auth::user()->role == 'pustakawan')
                @include('layouts.partials.sidebar-librarian')
            @elseif(Auth::user()->role == 'admin')
                @include('layouts.partials.sidebar-admin')
            @else
                @include('layouts.partials.sidebar-member')
            @endif
        </div>

        <div class="flex-1 flex flex-col min-w-0 h-full">
            @include('layouts.partials.header')

            <main class="rounded flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar">
                <div class="rounded-sm border border-gray-100 dark:border-slate-800 min-h-full p-8 shadow-sm bg-[#FEFEFE] dark:bg-slate-900 transition-colors duration-200">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/app-toast.js') }}"></script>
    <script src="{{ asset('assets/js/helpers.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                fireToast('success', "{!! session('success') !!}");
            @endif

            @if(session('error'))
                fireToast('error', "{!! session('error') !!}");
            @endif
        });
    </script>
    @stack('scripts')

    @if(Auth::check() && Auth::user()->role === 'member')
    {{-- ═══════════════════════════════════════════════════
         MacaBot — MacaBae Floating Chatbot (Member Only)
    ═══════════════════════════════════════════════════ --}}
    <style>
        /* ── Container ── */
        #macabot-wrap { position:fixed; bottom:28px; right:28px; z-index:9999; display:flex; flex-direction:column; align-items:flex-end; gap:12px; }

        /* ── Toggle Button ── */
        #macabot-toggle {
            width:58px; height:58px; border-radius:50%; border:none; cursor:pointer;
            background: linear-gradient(135deg,#4D9BE2,#3a7fc1);
            box-shadow: 0 8px 32px rgba(77,155,226,.45);
            display:flex; align-items:center; justify-content:center;
            transition: transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .2s;
            position:relative;
        }
        #macabot-toggle:hover { transform:scale(1.1); box-shadow:0 12px 36px rgba(77,155,226,.55); }
        #macabot-toggle.macabot-active { background:linear-gradient(135deg,#e25252,#c43a3a); }
        #macabot-toggle svg { width:26px; height:26px; color:#fff; transition:opacity .2s; }
        #macabot-icon-chat, #macabot-icon-close { position:absolute; transition:opacity .2s, transform .2s; }
        #macabot-toggle:not(.macabot-active) #macabot-icon-close { opacity:0; transform:rotate(-90deg); }
        #macabot-toggle.macabot-active #macabot-icon-chat { opacity:0; transform:rotate(90deg); }

        /* ── Badge ── */
        #macabot-badge {
            position:absolute; top:-4px; right:-4px;
            background:#ef4444; color:#fff; font-size:10px; font-weight:700;
            min-width:18px; height:18px; border-radius:9px; padding:0 4px;
            display:flex; align-items:center; justify-content:center;
            border:2px solid #fff; animation:macabot-pulse 1.5s infinite;
        }
        @keyframes macabot-pulse { 0%,100%{transform:scale(1)}50%{transform:scale(1.15)} }

        /* ── Panel ── */
        #macabot-panel {
            width:360px; max-width:calc(100vw - 32px);
            background:#fff; border-radius:24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.18), 0 4px 16px rgba(0,0,0,.08);
            display:flex; flex-direction:column; overflow:hidden;
            transform:translateY(20px) scale(.95); opacity:0; pointer-events:none;
            transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
            max-height:520px;
        }
        .dark #macabot-panel { background:#1e293b; box-shadow:0 20px 60px rgba(0,0,0,.5); }
        #macabot-panel.macabot-open { transform:translateY(0) scale(1); opacity:1; pointer-events:all; }

        /* ── Header ── */
        #macabot-header {
            padding:16px 20px; display:flex; align-items:center; gap:12px;
            background:linear-gradient(135deg,#4D9BE2,#3a7fc1);
            flex-shrink:0;
        }
        #macabot-avatar {
            width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,.2);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        #macabot-header h3 { margin:0; color:#fff; font-size:15px; font-weight:700; }
        #macabot-header p  { margin:0; color:rgba(255,255,255,.8); font-size:11px; }
        .macabot-online { width:8px; height:8px; border-radius:50%; background:#4ade80; display:inline-block; margin-right:4px; animation:macabot-pulse 2s infinite; }

        /* ── Body ── */
        #macabot-body {
            flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:10px;
            background:#f8fafc; scroll-behavior:smooth;
        }
        .dark #macabot-body { background:#0f172a; }
        #macabot-body::-webkit-scrollbar { width:4px; }
        #macabot-body::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }
        .dark #macabot-body::-webkit-scrollbar-thumb { background:#334155; }

        /* ── Messages ── */
        .macabot-msg { display:flex; }
        .macabot-msg.macabot-user { justify-content:flex-end; }
        .macabot-msg.macabot-bot  { justify-content:flex-start; }
        .macabot-bubble {
            max-width:80%; padding:10px 14px; border-radius:18px;
            font-size:12.5px; line-height:1.55; word-break:break-word;
        }
        .macabot-user .macabot-bubble {
            background:linear-gradient(135deg,#4D9BE2,#3a7fc1);
            color:#fff; border-bottom-right-radius:4px;
        }
        .macabot-bot .macabot-bubble {
            background:#fff; color:#2F3951;
            border:1px solid #e2e8f0; border-bottom-left-radius:4px;
            box-shadow:0 2px 8px rgba(0,0,0,.06);
        }
        .dark .macabot-bot .macabot-bubble { background:#1e293b; color:#e2e8f0; border-color:#334155; }
        .chatbot-link {
            color:#4D9BE2; font-weight:600; text-decoration:underline;
        }
        .chatbot-link:hover { color:#3a7fc1; }

        /* ── Typing ── */
        .macabot-typing { display:flex; align-items:center; gap:4px; padding:12px 16px !important; }
        .macabot-typing span {
            width:7px; height:7px; border-radius:50%; background:#94a3b8;
            display:inline-block; animation:macabot-bounce .9s infinite ease-in-out;
        }
        .macabot-typing span:nth-child(2) { animation-delay:.2s; }
        .macabot-typing span:nth-child(3) { animation-delay:.4s; }
        @keyframes macabot-bounce { 0%,80%,100%{transform:translateY(0)}40%{transform:translateY(-7px)} }

        /* ── Quick Replies ── */
        #macabot-quick-wrap { padding:8px 16px; display:flex; flex-wrap:wrap; gap:6px; background:#f8fafc; border-top:1px solid #f1f5f9; flex-shrink:0; }
        .dark #macabot-quick-wrap { background:#0f172a; border-color:#1e293b; }
        .macabot-quick {
            padding:5px 12px; border-radius:20px; border:1.5px solid #e2e8f0;
            background:#fff; color:#4D9BE2; font-size:11px; font-weight:600;
            cursor:pointer; transition:all .2s; white-space:nowrap;
        }
        .dark .macabot-quick { background:#1e293b; border-color:#334155; color:#60a5fa; }
        .macabot-quick:hover { background:#4D9BE2; color:#fff; border-color:#4D9BE2; }

        /* ── Input Area ── */
        #macabot-footer { padding:12px 16px; background:#fff; border-top:1px solid #f1f5f9; flex-shrink:0; }
        .dark #macabot-footer { background:#1e293b; border-color:#334155; }
        #macabot-form { display:flex; gap:8px; align-items:center; }
        #macabot-input {
            flex:1; padding:10px 14px; border-radius:14px; border:1.5px solid #e2e8f0;
            background:#f8fafc; font-size:13px; color:#2F3951; outline:none;
            transition:border-color .2s;
        }
        .dark #macabot-input { background:#0f172a; border-color:#334155; color:#e2e8f0; }
        #macabot-input:focus { border-color:#4D9BE2; background:#fff; }
        .dark #macabot-input:focus { background:#1e293b; }
        #macabot-send {
            width:40px; height:40px; border-radius:12px; border:none; cursor:pointer;
            background:linear-gradient(135deg,#4D9BE2,#3a7fc1); color:#fff;
            display:flex; align-items:center; justify-content:center;
            transition:transform .2s, box-shadow .2s; flex-shrink:0;
        }
        #macabot-send:hover { transform:scale(1.08); box-shadow:0 4px 12px rgba(77,155,226,.4); }
        #macabot-send svg { width:18px; height:18px; }

        @media (max-width:480px) {
            #macabot-wrap { bottom:16px; right:16px; }
            #macabot-panel { width:calc(100vw - 32px); }
        }
    </style>

    <div id="macabot-wrap">
        {{-- Chat Panel --}}
        <div id="macabot-panel" role="dialog" aria-label="MacaBot Chat">

            {{-- Header --}}
            <div id="macabot-header">
                <div id="macabot-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" width="22" height="22">
                        <path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7H3a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2z" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" stroke-linecap="round"/>
                        <circle cx="9" cy="11" r="1" fill="white" stroke="none"/>
                        <circle cx="15" cy="11" r="1" fill="white" stroke="none"/>
                    </svg>
                </div>
                <div>
                    <h3>MacaBot 🤖</h3>
                    <p><span class="macabot-online"></span>Asisten Perpustakaan MacaBae</p>
                </div>
            </div>

            {{-- Messages Body --}}
            <div id="macabot-body">
                {{-- Welcome message --}}
                <div class="macabot-msg macabot-bot">
                    <div class="macabot-bubble">
                        Halo, <b>{{ explode(' ', Auth::user()->name)[0] }}</b>! 👋<br><br>
                        Aku <b>MacaBot</b>, asisten perpustakaan MacaBae.<br>
                        Ada yang bisa aku bantu hari ini? 😊
                    </div>
                </div>
            </div>

            {{-- Quick Replies --}}
            <div id="macabot-quick-wrap">
                <button class="macabot-quick" data-msg="cari buku">🔍 Cari Buku</button>
                <button class="macabot-quick" data-msg="pinjaman saya">📋 Pinjaman</button>
                <button class="macabot-quick" data-msg="denda saya">💸 Denda</button>
                <button class="macabot-quick" data-msg="cara pinjam">❓ Cara Pinjam</button>
            </div>

            {{-- Input Footer --}}
            <div id="macabot-footer">
                <form id="macabot-form" autocomplete="off">
                    <input id="macabot-input" type="text" placeholder="Ketik pesan..." maxlength="200" />
                    <button id="macabot-send" type="submit" aria-label="Kirim">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Toggle Button --}}
        <button id="macabot-toggle" aria-label="MacaBot Chat">
            <span id="macabot-badge" class="hidden"></span>
            {{-- Chat Icon --}}
            <svg id="macabot-icon-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="10" r=".5" fill="currentColor"/>
                <circle cx="8" cy="10" r=".5" fill="currentColor"/>
                <circle cx="16" cy="10" r=".5" fill="currentColor"/>
            </svg>
            {{-- Close Icon --}}
            <svg id="macabot-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18" stroke-linecap="round"/>
                <line x1="6" y1="6" x2="18" y2="18" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <script src="{{ asset('assets/js/chatbot.js') }}"></script>
    @endif
</body>
</html>