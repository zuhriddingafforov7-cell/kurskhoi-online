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
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.certificates.export') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Содироти хатмкунандагон (CSV)
                </a>
                <span class="text-xs text-slate-400">Нақши фаъол: <strong class="text-slate-700">Admin</strong></span>
            </div>
        </div>

        <!-- Кортҳои оморӣ (Stats) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Истифодабарандагон</div>
                <div class="text-2xl font-bold text-[#1E293B] mt-2">{{ $stats['total_users'] }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $stats['students'] }} донишҷӯ • {{ $stats['authors'] }} муаллиф</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Курсҳо</div>
                <div class="text-2xl font-bold text-[#00875A] mt-2">{{ $stats['total_courses'] }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $stats['published_courses'] }} нашршуда • {{ $stats['rejected_courses'] }} радшуда</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Дар интизори тасдиқ</div>
                <div class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['pending_courses'] }}</div>
                <div class="text-xs text-slate-400 mt-1">Барои модератсия</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Номнависӣ ва Сертификатҳо</div>
                <div class="text-2xl font-bold text-[#10B981] mt-2">{{ $stats['total_enrollments'] }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $stats['total_certificates'] }} сертификат • {{ $stats['total_categories'] }} категория</div>
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

        @if(isset($pendingCourses) && $pendingCourses->count() > 0)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-[#1E293B]">Курсҳои мунтазири тасдиқ</h2>
                    <a href="{{ route('admin.courses.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-[#00875A] hover:underline">Ҳамаро дидан →</a>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($pendingCourses as $pending)
                        <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <div class="font-bold text-sm text-[#1E293B]">{{ $pending->title }}</div>
                                <div class="text-slate-500">Муаллиф: {{ $pending->author->name ?? 'Номаълум' }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.courses.approve', $pending) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 bg-[#00875A] hover:bg-[#064E3B] text-white font-semibold rounded-lg transition">
                                        Тасдиқ
                                    </button>
                                </form>
                                <a href="{{ route('admin.courses.index', ['status' => 'pending']) }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition">
                                    Ба модератсия
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-[#1E293B]">Курсҳои охирин</h2>
                    <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-[#00875A] hover:underline">Ҳама →</a>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($recentCourses ?? [] as $rc)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-[#1E293B] truncate">{{ $rc->title }}</div>
                                <div class="text-slate-400">{{ $rc->author->name ?? 'Номаълум' }} • {{ $rc->category->name ?? 'Категория' }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0
                                {{ $rc->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($rc->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ ucfirst($rc->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400 py-4 text-center">Курсҳо нестанд.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-[#1E293B]">Корбарони навтарин</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#00875A] hover:underline">Ҳама →</a>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($recentUsers ?? [] as $ru)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-[#1E293B] truncate">{{ $ru->name }}</div>
                                <div class="text-slate-400 truncate">{{ $ru->email }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 shrink-0 uppercase">
                                {{ $ru->role }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400 py-4 text-center">Корбарон нестанд.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
