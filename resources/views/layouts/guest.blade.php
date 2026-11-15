<!DOCTYPE html>
<html lang="tg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Biyomuz Academy' }} — Платформаи курсҳои онлайн</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] text-[#1E293B] antialiased min-h-screen flex flex-col justify-center items-center py-6 sm:py-12 px-3 sm:px-6 lg:px-8 overflow-x-hidden font-sans">
    <div class="w-full max-w-md mx-auto">
        {{-- Бренд / Логотип --}}
        <div class="text-center mb-6 sm:mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 sm:gap-3 group">
                <span class="flex h-11 w-11 sm:h-12 sm:w-12 items-center justify-center rounded-xl sm:rounded-2xl bg-[#00875A] text-white font-black text-xl sm:text-2xl shadow-lg shadow-[#00875A]/20 group-hover:scale-105 transition-all duration-200">
                    B
                </span>
                <div class="text-left">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-[#1E293B] block leading-tight">Biyomuz Academy</span>
                    <span class="text-[10px] sm:text-xs text-slate-500 font-medium">Платформаи миллии таълими онлайн</span>
                </div>
            </a>
        </div>

        {{-- Корти асосӣ --}}
        <div class="bg-white py-6 sm:py-8 px-5 sm:px-10 shadow-xl shadow-slate-200/50 rounded-2xl sm:rounded-3xl border border-slate-100">
            {{ $slot }}
        </div>

        {{-- Поварақӣ --}}
        <div class="mt-6 sm:mt-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Biyomuz Academy. Ҳамаи ҳуқуқҳо маҳфузанд.
        </div>
    </div>
</body>
</html>
