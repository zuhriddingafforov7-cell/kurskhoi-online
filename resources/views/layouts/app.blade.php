<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Biyomuz Academy' }} — {{ __('Платформаи курсҳои онлайн') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] text-[#1E293B] antialiased min-h-screen flex flex-col font-sans overflow-x-hidden">

    @php
        $currentLocale = app()->getLocale();
    @endphp

    {{-- HEADER — Biyomuz Academy Design System --}}
    <header
        x-data="{ mobileOpen: false, profileOpen: false, langOpen: false }"
        class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-xs"
    >
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 sm:h-20 items-center gap-3 lg:gap-6">

                {{-- Чап: Логотип ва Навигатсия --}}
                <div class="flex items-center gap-3 xl:gap-5 min-w-0">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group shrink-0">
                        <span class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-[#00875A] text-white font-black text-lg sm:text-xl shadow-sm shadow-[#00875A]/20 group-hover:scale-105 transition-transform duration-200">
                            B
                        </span>
                        <div class="flex flex-col">
                            <div class="flex items-baseline leading-none">
                                <span class="font-black text-xl sm:text-2xl text-[#1E293B] tracking-tight">Biyomuz</span>
                                <span class="text-2xl sm:text-3xl font-black text-[#00875A] leading-none">.</span>
                            </div>
                            <span class="hidden sm:block text-[9px] font-bold uppercase tracking-[0.18em] text-[#00875A]">Academy</span>
                        </div>
                    </a>

                    <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1 text-xs xl:text-sm font-semibold text-slate-600" aria-label="Навигатсияи асосӣ">
                        <a href="{{ route('courses.index') }}" class="px-2.5 py-2 rounded-xl whitespace-nowrap hover:bg-[#ECFDF5] hover:text-[#00875A] transition {{ request()->routeIs('courses.*') ? 'bg-[#ECFDF5] text-[#00875A]' : '' }}">
                            {{ __('Каталоги курсҳо') }}
                        </a>
                        <a href="{{ route('reports.courses') }}" class="px-2.5 py-2 rounded-xl whitespace-nowrap hover:bg-[#ECFDF5] hover:text-[#00875A] transition {{ request()->routeIs('reports.*') ? 'bg-[#ECFDF5] text-[#00875A]' : '' }}">
                            {{ __('Ҳисоботи курсҳо') }}
                        </a>
                        <a href="{{ url('/#about') }}" class="px-2.5 py-2 rounded-xl whitespace-nowrap hover:bg-[#ECFDF5] hover:text-[#00875A] transition">
                            {{ __('Дар бораи мо') }}
                        </a>
                        <a href="{{ url('/#contact') }}" class="px-2.5 py-2 rounded-xl whitespace-nowrap hover:bg-[#ECFDF5] hover:text-[#00875A] transition">
                            {{ __('Тамос') }}
                        </a>
                    </nav>
                </div>

                {{-- Марказ: Сатри ҷустуҷӯи курсҳо --}}
                <div class="flex-1 min-w-0 max-w-xs hidden 2xl:block mx-2">
                    <form action="{{ route('courses.index') }}" method="GET" class="relative w-full">
                        <div class="relative flex items-center">
                            <svg class="w-4 h-4 text-[#00875A] absolute left-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="{{ __('Ҷустуҷӯи курс...') }}"
                                aria-label="{{ __('Ҷустуҷӯи курс...') }}"
                                class="w-full pl-10 pr-4 py-2 bg-slate-100/90 hover:bg-slate-100 focus:bg-white text-xs sm:text-sm text-slate-800 placeholder-slate-400 rounded-2xl border border-transparent focus:border-[#00875A] focus:ring-2 focus:ring-[#00875A]/15 transition-all outline-none"
                            >
                        </div>
                    </form>
                </div>

                {{-- Рост: Забон ва Панели корбар --}}
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">

                    {{-- Интихоби забон (Тоҷикӣ / Русский) --}}
                    <div class="relative hidden sm:block">
                        <button
                            @click="langOpen = !langOpen"
                            @click.outside="langOpen = false"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-[#00875A] rounded-xl hover:bg-slate-100 border border-slate-200/70 transition-colors"
                            aria-expanded="false"
                        >
                            <span class="text-sm">{{ $currentLocale === 'ru' ? '🇷🇺' : '🇹🇯' }}</span>
                            <span>{{ $currentLocale === 'ru' ? 'Русский' : 'Тоҷикӣ' }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="{ 'rotate-180': langOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div
                            x-show="langOpen"
                            x-transition
                            class="absolute right-0 mt-2 w-40 bg-white border border-slate-200 rounded-2xl shadow-lg py-1.5 z-50"
                            style="display: none;"
                        >
                            <a href="{{ route('locale.switch', 'tg') }}" class="flex items-center justify-between gap-2 px-3.5 py-2 text-xs {{ $currentLocale === 'tg' ? 'font-bold text-[#00875A] bg-[#ECFDF5]' : 'text-slate-600 hover:bg-slate-50' }} transition">
                                <span class="flex items-center gap-2"><span>🇹🇯</span> Тоҷикӣ</span>
                                @if($currentLocale === 'tg') <span>✓</span> @endif
                            </a>
                            <a href="{{ route('locale.switch', 'ru') }}" class="flex items-center justify-between gap-2 px-3.5 py-2 text-xs {{ $currentLocale === 'ru' ? 'font-bold text-[#00875A] bg-[#ECFDF5]' : 'text-slate-600 hover:bg-slate-50' }} transition">
                                <span class="flex items-center gap-2"><span>🇷🇺</span> Русский</span>
                                @if($currentLocale === 'ru') <span>✓</span> @endif
                            </a>
                        </div>
                    </div>

                    @auth
                        {{-- Пайванди фаврӣ ба панел аз рӯйи нақш --}}
                        <div class="hidden 2xl:flex shrink-0">
                            @php
                                $quickRoute = auth()->user()->role === 'admin'
                                    ? route('admin.courses.index')
                                    : (auth()->user()->role === 'author'
                                        ? route('author.submissions.index')
                                        : route('student.my-courses'));
                                $quickLabel = auth()->user()->role === 'admin'
                                    ? __('Модератсияи курсҳо')
                                    : (auth()->user()->role === 'author'
                                        ? __('Санҷиши супоришҳо')
                                        : __('Курсҳои ман'));
                            @endphp
                            <a href="{{ $quickRoute }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold text-[#00875A] bg-[#ECFDF5] hover:bg-[#D1FAE5] border border-[#A7F3D0]/70 transition">
                                {{ $quickLabel }}
                            </a>
                        </div>

                        {{-- Аватар ва Профили корбар (Dropdown) --}}
                        <div class="relative">
                            <button
                                @click="profileOpen = !profileOpen"
                                @click.outside="profileOpen = false"
                                type="button"
                                class="flex items-center gap-2.5 p-1.5 pl-2.5 rounded-2xl hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-[#00875A]/30 group"
                            >
                                <div class="hidden sm:block text-right leading-tight">
                                    <span class="text-xs sm:text-sm font-bold text-slate-800 block whitespace-nowrap max-w-[220px] truncate">
                                        {{ auth()->user()->name }}
                                    </span>
                                    <span class="text-[11px] text-[#00875A] font-semibold block mt-0.5">
                                        @if(auth()->user()->role === 'admin')
                                            {{ __('Маъмури система') }}
                                        @elseif(auth()->user()->role === 'author')
                                            {{ __('Муаллими курс') }}
                                        @else
                                            {{ __('Донишҷӯ') }}
                                        @endif
                                    </span>
                                </div>

                                <div class="relative">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-tr from-[#00875A] to-[#10B981] text-white flex items-center justify-center font-black text-sm shadow-sm ring-2 ring-[#00875A]/20 group-hover:ring-[#00875A]/40 transition">
                                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                                </div>

                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 transition-transform duration-200 hidden sm:block" :class="{ 'rotate-180': profileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div
                                x-show="profileOpen"
                                x-transition
                                class="absolute right-0 mt-2 w-60 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50 divide-y divide-slate-100"
                                style="display: none;"
                            >
                                <div class="px-4 py-2.5">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    <span class="inline-block px-2 py-0.5 mt-1 rounded-md text-[10px] font-bold bg-[#ECFDF5] text-[#00875A]">
                                        @if(auth()->user()->role === 'admin')
                                            {{ __('Маъмур') }}
                                        @elseif(auth()->user()->role === 'author')
                                            {{ __('Муаллим') }}
                                        @else
                                            {{ __('Донишҷӯ') }}
                                        @endif
                                    </span>
                                </div>

                                <div class="py-1 text-xs">
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Панели маъмур') }}</a>
                                        <a href="{{ route('manage.courses.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Идоракунии курсҳо') }}</a>
                                        <a href="{{ route('admin.courses.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Модератсияи курсҳо') }}</a>
                                        <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Категорияҳо') }}</a>
                                        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Идоракунии корбарон') }}</a>
                                    @elseif(auth()->user()->role === 'author')
                                        <a href="{{ route('author.dashboard') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Панели муаллиф') }}</a>
                                        <a href="{{ route('manage.courses.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Идоракунии курсҳо') }}</a>
                                        <a href="{{ route('author.courses.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Курсҳои ман') }}</a>
                                        <a href="{{ route('author.submissions.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Санҷиши супоришҳо') }}</a>
                                    @else
                                        <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Пешрафт') }}</a>
                                        <a href="{{ route('student.my-courses') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Курсҳои ман') }}</a>
                                    @endif
                                    <a href="{{ route('courses.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Каталоги курсҳо') }}</a>
                                    <a href="{{ route('reports.courses') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Ҳисоботи курсҳо') }}</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-slate-700 hover:bg-[#ECFDF5] hover:text-[#00875A]">{{ __('Профили ман') }}</a>
                                </div>

                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            {{ __('Баромад аз система') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Тугмаҳои воридшавӣ барои меҳмонон --}}
                        <div class="hidden sm:flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:text-[#00875A] border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                                {{ __('Воридшавӣ') }}
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition">
                                {{ __('Бақайдгирӣ') }}
                            </a>
                        </div>
                    @endauth

                    {{-- Бургер-меню (Mobile & Tablet) --}}
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-[#00875A] hover:bg-slate-100 transition"
                        aria-label="{{ __('Кушодани меню') }}"
                    >
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" class="w-6 h-6 text-[#00875A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- МЕНЮИ МОБИЛӢ --}}
        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden border-t border-slate-100 bg-white max-h-[calc(100vh-4rem)] overflow-y-auto"
            style="display: none;"
        >
            <div class="px-4 py-4 space-y-2">
                {{-- Ҷустуҷӯ дар мобилӣ --}}
                <form action="{{ route('courses.index') }}" method="GET" class="md:hidden mb-2">
                    <div class="relative flex items-center">
                        <svg class="w-4 h-4 text-[#00875A] absolute left-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ __('Ҷустуҷӯи курс...') }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-100 text-sm text-slate-800 rounded-xl border border-slate-200 focus:bg-white focus:border-[#00875A] outline-none"
                        >
                    </div>
                </form>

                @auth
                    <div class="flex items-center gap-3 px-3 py-3 mb-2 bg-[#F8FAFC] border border-slate-200/70 rounded-2xl">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#00875A] text-white font-bold text-base shadow-sm">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-bold text-[#1E293B] truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</div>
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-semibold bg-[#ECFDF5] text-[#00875A]">
                                @if(auth()->user()->role === 'admin')
                                    {{ __('Маъмур') }}
                                @elseif(auth()->user()->role === 'author')
                                    {{ __('Муаллим') }}
                                @else
                                    {{ __('Донишҷӯ') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Панели маъмур') }}</a>
                        <a href="{{ route('manage.courses.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('manage.courses.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Идоракунии курсҳо') }}</a>
                        <a href="{{ route('admin.courses.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.courses.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Модератсияи курсҳо') }}</a>
                        <a href="{{ route('admin.categories.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Категорияҳо') }}</a>
                        <a href="{{ route('admin.users.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Идоракунии корбарон') }}</a>
                    @elseif(auth()->user()->role === 'author')
                        <a href="{{ route('author.dashboard') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('author.dashboard') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Панели муаллиф') }}</a>
                        <a href="{{ route('manage.courses.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('manage.courses.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Идоракунии курсҳо') }}</a>
                        <a href="{{ route('author.courses.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('author.courses.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Курсҳои ман') }}</a>
                        <a href="{{ route('author.submissions.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('author.submissions.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Санҷиши супоришҳо') }}</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('student.dashboard') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Пешрафт') }}</a>
                        <a href="{{ route('student.my-courses') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('student.my-courses') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Курсҳои ман') }}</a>
                    @endif

                    <a href="{{ route('courses.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('courses.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Каталоги курсҳо') }}</a>
                    <a href="{{ route('reports.courses') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Ҳисоботи курсҳо') }}</a>
                    <a href="{{ route('profile.edit') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-[#ECFDF5] text-[#00875A] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">{{ __('Профили ман') }}</a>

                    <div class="border-t border-slate-100 my-2 pt-3">
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 px-3 mb-2">{{ __('Забон / Язык') }}</div>
                        <div class="grid grid-cols-2 gap-2 px-1">
                            <a href="{{ route('locale.switch', 'tg') }}" class="text-center px-3 py-2.5 text-xs rounded-xl border {{ $currentLocale === 'tg' ? 'font-bold bg-[#ECFDF5] text-[#00875A] border-[#A7F3D0]' : 'font-medium text-slate-600 border-slate-200 hover:bg-slate-50' }}">🇹🇯 Тоҷикӣ</a>
                            <a href="{{ route('locale.switch', 'ru') }}" class="text-center px-3 py-2.5 text-xs rounded-xl border {{ $currentLocale === 'ru' ? 'font-bold bg-[#ECFDF5] text-[#00875A] border-[#A7F3D0]' : 'font-medium text-slate-600 border-slate-200 hover:bg-slate-50' }}">🇷🇺 Русский</a>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 my-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3.5 py-3 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                {{ __('Баромад аз система') }}
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('courses.index') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Каталоги курсҳо') }}</a>
                    <a href="{{ route('reports.courses') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Ҳисоботи курсҳо') }}</a>
                    <a href="{{ url('/#about') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Дар бораи мо') }}</a>
                    <a href="{{ url('/#contact') }}" @click="mobileOpen = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Тамос') }}</a>

                    <div class="border-t border-slate-100 my-2 pt-3">
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 px-3 mb-2">{{ __('Забон / Язык') }}</div>
                        <div class="grid grid-cols-2 gap-2 px-1">
                            <a href="{{ route('locale.switch', 'tg') }}" class="text-center px-3 py-2.5 text-xs rounded-xl border {{ $currentLocale === 'tg' ? 'font-bold bg-[#ECFDF5] text-[#00875A] border-[#A7F3D0]' : 'font-medium text-slate-600 border-slate-200 hover:bg-slate-50' }}">🇹🇯 Тоҷикӣ</a>
                            <a href="{{ route('locale.switch', 'ru') }}" class="text-center px-3 py-2.5 text-xs rounded-xl border {{ $currentLocale === 'ru' ? 'font-bold bg-[#ECFDF5] text-[#00875A] border-[#A7F3D0]' : 'font-medium text-slate-600 border-slate-200 hover:bg-slate-50' }}">🇷🇺 Русский</a>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 my-2 pt-3 space-y-2 px-1">
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 text-sm font-semibold text-[#00875A] border border-[#00875A]/30 rounded-xl hover:bg-[#ECFDF5] transition-colors">
                            {{ __('Воридшавӣ') }}
                        </a>
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 text-sm font-bold text-white bg-[#00875A] rounded-xl hover:bg-[#064E3B] shadow-sm shadow-[#00875A]/20 transition-colors">
                            {{ __('Бақайдгирӣ') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    {{-- Огоҳиҳо / Flash Messages --}}
    @if(session('success') || session('error') || session('info') || $errors->any())
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full" role="alert">
            @if(session('success'))
                <div class="p-4 mb-4 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] text-sm flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 text-[#00875A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="p-4 mb-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-sm flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs">
                    <div class="font-semibold mb-1">{{ __('Лутфан хатогиҳоро ислоҳ кунед:') }}</div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    {{-- Мундариҷаи асосӣ --}}
    <main class="flex-1 w-full">
        @if(isset($fullWidth) && $fullWidth)
            {{ $slot }}
        @elseif(isset($withSidebar) && $withSidebar && auth()->check())
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                <div class="flex flex-col lg:flex-row gap-6 items-start">
                    {{-- Панели паҳлӯӣ (Sidebar) --}}
                    <aside class="hidden lg:flex flex-col w-60 xl:w-64 shrink-0 bg-white rounded-3xl border border-slate-200/90 shadow-xs p-4 sticky top-24 space-y-4 self-start">
                        <nav class="space-y-1.5 text-sm font-medium" aria-label="Менюи кабинет">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    <span>{{ __('Панели маъмур') }}</span>
                                </a>
                                <a href="{{ route('manage.courses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('manage.courses.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>{{ __('Идоракунии курсҳо') }}</span>
                                </a>
                                <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.courses.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>{{ __('Модератсияи курсҳо') }}</span>
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <span>{{ __('Категорияҳо') }}</span>
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>{{ __('Идоракунии корбарон') }}</span>
                                </a>
                            @elseif(auth()->user()->role === 'author')
                                <a href="{{ route('author.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('author.dashboard') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    <span>{{ __('Панели муаллиф') }}</span>
                                </a>
                                <a href="{{ route('manage.courses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('manage.courses.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>{{ __('Идоракунии курсҳо') }}</span>
                                </a>
                                <a href="{{ route('author.courses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('author.courses.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>{{ __('Курсҳои ман') }}</span>
                                </a>
                                <a href="{{ route('author.submissions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('author.submissions.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>{{ __('Санҷиши супоришҳо') }}</span>
                                </a>
                            @else
                                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    <span>{{ __('Пешрафт') }}</span>
                                </a>
                                <a href="{{ route('student.my-courses') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('student.my-courses') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>{{ __('Курсҳои ман') }}</span>
                                </a>
                            @endif

                            <a href="{{ route('courses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('courses.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>{{ __('Каталоги курсҳо') }}</span>
                            </a>

                            <a href="{{ route('reports.courses') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>{{ __('Ҳисоботи курсҳо') }}</span>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-[#00875A] text-white font-bold shadow-sm' : 'text-slate-600 hover:text-[#00875A] hover:bg-[#ECFDF5]' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ __('Профили ман') }}</span>
                            </a>
                        </nav>
                    </aside>

                    {{-- Мундариҷаи саҳифа --}}
                    <div class="flex-1 min-w-0 w-full">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        @else
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                {{ $slot }}
            </div>
        @endif
    </main>

    {{-- FOOTER — Biyomuz Academy --}}
    <footer class="bg-[#064E3B] text-white py-12 sm:py-16 mt-auto">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-12">
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00875A] text-white font-black text-xl shadow-md">B</span>
                        <div>
                            <span class="font-extrabold text-lg text-white leading-none block">Biyomuz</span>
                            <span class="text-[10px] font-semibold text-[#A7F3D0] tracking-[0.2em] uppercase">Academy</span>
                        </div>
                    </div>
                    <p class="text-sm text-[#A7F3D0] italic">«{{ __('Имрӯз биёмӯз, фардо муваффақ шав!') }}»</p>
                    <p class="mt-3 text-xs sm:text-sm text-[#6EE7B7]/85 leading-relaxed">
                        Платформаи муосири миллии омӯзиши онлайн бо курсҳои касбӣ ва шаҳодатномаҳои расмии хатм.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-sm text-white uppercase tracking-wider mb-3 sm:mb-4">{{ __('Пайвандҳои муфид') }}</h4>
                    <ul class="space-y-2.5 text-sm text-[#A7F3D0]">
                        <li><a href="{{ route('courses.index') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span>→</span> {{ __('Каталоги курсҳо') }}</a></li>
                        <li><a href="{{ route('reports.courses') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span>→</span> {{ __('Ҳисоботи курсҳо') }}</a></li>
                        <li><a href="{{ url('/#about') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span>→</span> {{ __('Дар бораи мо') }}</a></li>
                        <li><a href="{{ url('/#contact') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span>→</span> {{ __('Тамос') }}</a></li>
                        @guest
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span>→</span> {{ __('Бақайдгирӣ') }}</a></li>
                        @endguest
                    </ul>
                </div>

                <div id="contact">
                    <h4 class="font-bold text-sm text-white uppercase tracking-wider mb-3 sm:mb-4">{{ __('Тамос') }}</h4>
                    <ul class="space-y-2.5 text-sm text-[#A7F3D0]">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>info@biyomuz.tj</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>ш. Хуҷанд / ш. Душанбе, Тоҷикистон</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>24/7 Онлайн</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-[#065F46] mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-[#6EE7B7] gap-3">
                <p>&copy; {{ date('Y') }} Biyomuz Academy. Ҳамаи ҳуқуқҳо маҳфузанд.</p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('locale.switch', 'tg') }}" class="{{ $currentLocale === 'tg' ? 'text-white font-bold underline' : 'text-[#A7F3D0] hover:text-white' }}">Тоҷикӣ</a>
                    <span>•</span>
                    <a href="{{ route('locale.switch', 'ru') }}" class="{{ $currentLocale === 'ru' ? 'text-white font-bold underline' : 'text-[#A7F3D0] hover:text-white' }}">Русский</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
