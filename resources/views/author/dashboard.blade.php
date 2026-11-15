<x-app-layout title="Панели муаллиф" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳаи саҳифа -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 mb-2">
                    Муаллифӣ
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">Панели кории муаллиф</h1>
                <p class="text-sm text-slate-500 mt-1">Хуш омадед, {{ auth()->user()->name }}! Курсҳо ва маводи таълимии худро идора кунед.</p>
            </div>
            <div>
                <a href="{{ route('author.courses.index') }}" class="inline-flex items-center px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm shadow-[#00875A]/20 transition">
                    + Курсҳои ман
                </a>
            </div>
        </div>

        <!-- Омори муаллиф -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Курсҳои ман</div>
                <div class="text-2xl font-bold text-[#00875A] mt-2">{{ isset($courses) ? $courses->count() : (auth()->user()->courses()->count() ?? 0) }}</div>
                <div class="text-xs text-slate-400 mt-1">Миқдори курсҳои эҷодшуда</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Донишҷӯён</div>
                <div class="text-2xl font-bold text-[#1E293B] mt-2">{{ $totalStudents ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">Донишҷӯёни фаъол дар курсҳо</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Супоришҳо барои санҷиш</div>
                <div class="text-2xl font-bold text-amber-600 mt-2">{{ $pendingSubmissions ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">Вазифаҳои санҷишнашуда</div>
            </div>
        </div>

        <!-- Кортҳои роҳнамо -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 class="text-lg font-bold text-[#1E293B] mb-4">Амалҳои фаврӣ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-slate-200 bg-[#F8FAFC]">
                    <h3 class="font-semibold text-[#1E293B] text-sm">Ирсоли курс барои модератсия</h3>
                    <p class="text-xs text-slate-500 mt-1">Курси навро бо ном ва тавсиф созед, то аз ҷониби маъмур баррасӣ шавад.</p>
                    <a href="{{ route('author.courses.index') }}" class="inline-block mt-3 text-xs font-semibold text-[#00875A] hover:text-[#064E3B]">
                        Гузариш ба рӯйхати курсҳо →
                    </a>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 bg-[#F8FAFC]">
                    <h3 class="font-semibold text-[#1E293B] text-sm">Санҷиши супоришҳо</h3>
                    <p class="text-xs text-slate-500 mt-1">Корҳои фиристодаи донишҷӯёнро тафтиш карда, баҳо ва тавсияҳо диҳед.</p>
                    <a href="{{ route('author.submissions.index') }}" class="inline-block mt-3 text-xs font-semibold text-[#00875A] hover:text-[#064E3B]">
                        Санҷиши супоришҳо →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
