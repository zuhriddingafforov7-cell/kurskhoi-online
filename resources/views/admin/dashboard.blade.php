<x-app-layout title="Панели маъмур" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳаи саҳифа -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 mb-2">
                    Маъмурият
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">Панели идоракунии маъмур</h1>
                <p class="text-sm text-slate-500 mt-1">Хуш омадед, {{ auth()->user()->name }}! Аз ин ҷо платформаро идора кунед.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400">Нақши фаъол: <strong class="text-slate-700">Admin</strong></span>
            </div>
        </div>

        <!-- Кортҳои оморӣ (Stats) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Истифодабарандагон</div>
                <div class="text-2xl font-bold text-[#1E293B] mt-2">{{ \App\Models\User::count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Шумораи умумии корбарон</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Курсҳои муаллифон</div>
                <div class="text-2xl font-bold text-[#00875A] mt-2">{{ \App\Models\Course::count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Ҳамаи курсҳо дар система</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Дар интизори тасдиқ</div>
                <div class="text-2xl font-bold text-amber-600 mt-2">{{ \App\Models\Course::where('status', 'pending')->count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Барои модератсия</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Категорияҳо</div>
                <div class="text-2xl font-bold text-[#10B981] mt-2">{{ \App\Models\Category::count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Бахшҳои таълимӣ</div>
            </div>
        </div>

        <!-- Бахшҳои идоракунӣ -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h2 class="text-lg font-bold text-[#1E293B] mb-4">Фаъолиятҳои асосӣ</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl border border-slate-200 bg-[#F8FAFC]">
                    <h3 class="font-semibold text-[#1E293B] text-sm">Модератсияи курсҳо</h3>
                    <p class="text-xs text-slate-500 mt-1">Санҷиш ва тасдиқ ё рад кардани курсҳои нави муаллифон.</p>
                    <a href="{{ route('admin.courses.index') }}" class="inline-block mt-3 text-xs font-semibold text-[#00875A] hover:text-[#064E3B]">
                        Гузариш ба курсҳо →
                    </a>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 bg-[#F8FAFC]">
                    <h3 class="font-semibold text-[#1E293B] text-sm">Идоракунии категорияҳо</h3>
                    <p class="text-xs text-slate-500 mt-1">Илова, таҳрир ва нест кардани бахшҳои таълимии Biyomuz.</p>
                    <a href="{{ route('admin.categories.index') }}" class="inline-block mt-3 text-xs font-semibold text-[#00875A] hover:text-[#064E3B]">
                        Гузариш ба категорияҳо →
                    </a>
                </div>
                <div class="p-4 rounded-xl border border-slate-200 bg-[#F8FAFC]">
                    <h3 class="font-semibold text-[#1E293B] text-sm">Истифодабарандагон ва нақшҳо</h3>
                    <p class="text-xs text-slate-500 mt-1">Муайян кардани нақшҳо (student, author, admin) ва идоракунии профилҳо.</p>
                    <a href="{{ route('admin.users.index') }}" class="inline-block mt-3 text-xs font-semibold text-[#00875A] hover:text-[#064E3B]">
                        Гузариш ба корбарон →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
