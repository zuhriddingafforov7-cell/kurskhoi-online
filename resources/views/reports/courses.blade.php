<x-app-layout :title="__('Ҳисоботи курсҳо') . ' — Biyomuz Academy'">
    <div class="space-y-6">
        {{-- Сарлавҳаи саҳифаи ҳисобот --}}
        <div class="bg-gradient-to-br from-[#064E3B] via-[#00875A] to-[#10B981] rounded-2xl p-6 sm:p-8 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm mb-3">
                    GROUP BY + LEFT JOIN • PDO Repository
                </span>
                <h1 class="text-2xl sm:text-3xl font-black leading-tight">
                    Шумораи курсҳо аз рӯйи категорияҳо
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-[#D1FAE5] max-w-2xl">
                    Таҳлили тақсимоти курсҳо, вазъияти модератсия ва шумораи донишҷӯёни номнависшуда дар ҳар категорияи таълимӣ.
                </p>
            </div>

            <div class="flex flex-wrap gap-2.5 shrink-0">
                <a href="{{ route('courses.index') }}" class="px-4 py-2.5 rounded-xl bg-white text-[#064E3B] hover:bg-[#ECFDF5] text-xs sm:text-sm font-bold transition">
                    {{ __('Каталоги курсҳо') }}
                </a>
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isAuthor())
                        <a href="{{ route('manage.courses.index') }}" class="px-4 py-2.5 rounded-xl bg-black/20 hover:bg-black/30 text-white text-xs sm:text-sm font-semibold border border-white/20 transition">
                            {{ __('Идоракунии курсҳо') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Кортҳои омори умумӣ --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="text-xs font-medium text-slate-500">Категорияҳо</div>
                <div class="text-2xl font-black text-[#1E293B] mt-1">{{ $totals['total_categories'] }}</div>
            </div>
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="text-xs font-medium text-slate-500">Ҳамаи курсҳо</div>
                <div class="text-2xl font-black text-[#00875A] mt-1">{{ $totals['total_courses'] }}</div>
            </div>
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-emerald-200 shadow-xs">
                <div class="text-xs font-medium text-emerald-700">Нашршуда</div>
                <div class="text-2xl font-black text-emerald-700 mt-1">{{ $totals['published_courses'] }}</div>
            </div>
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-amber-200 shadow-xs">
                <div class="text-xs font-medium text-amber-700">Дар баррасӣ</div>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $totals['pending_courses'] }}</div>
            </div>
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs col-span-2 md:col-span-1">
                <div class="text-xs font-medium text-slate-500">Номнависиҳо</div>
                <div class="text-2xl font-black text-[#064E3B] mt-1">{{ $totals['total_enrollments'] }}</div>
            </div>
        </div>

        {{-- Ҷадвали ҳисобот (Desktop & Tablet) --}}
        <div class="hidden md:block bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-4">Категория</th>
                            <th class="px-5 py-4 text-center">Ҳамагӣ курс</th>
                            <th class="px-5 py-4 text-center">Нашршуда</th>
                            <th class="px-5 py-4 text-center">Дар баррасӣ</th>
                            <th class="px-5 py-4 text-center">Лоиҳа</th>
                            <th class="px-5 py-4 text-center">Радшуда</th>
                            <th class="px-5 py-4 text-center">Донишҷӯён</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rows as $row)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4">
                                    <a href="{{ route('courses.index', ['category' => $row['category_id']]) }}" class="font-bold text-[#1E293B] hover:text-[#00875A] transition">
                                        {{ $row['category_title'] }}
                                    </a>
                                    <div class="text-xs text-slate-400 font-mono">{{ $row['category_slug'] }}</div>
                                </td>
                                <td class="px-5 py-4 text-center font-extrabold text-[#1E293B]">{{ $row['total_courses'] }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#ECFDF5] text-[#00875A]">
                                        {{ $row['published_courses'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center font-semibold text-amber-600">{{ $row['pending_courses'] }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-slate-600">{{ $row['draft_courses'] }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-rose-600">{{ $row['rejected_courses'] }}</td>
                                <td class="px-5 py-4 text-center font-bold text-[#064E3B]">{{ $row['total_enrollments'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-sm text-slate-400">Маълумоти категорияҳо ёфт нашуд.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-[#ECFDF5]/60 border-t-2 border-[#A7F3D0] font-extrabold text-[#064E3B]">
                        <tr>
                            <td class="px-5 py-4">Ҷамъбасти умумӣ</td>
                            <td class="px-5 py-4 text-center">{{ $totals['total_courses'] }}</td>
                            <td class="px-5 py-4 text-center">{{ $totals['published_courses'] }}</td>
                            <td class="px-5 py-4 text-center">{{ $totals['pending_courses'] }}</td>
                            <td class="px-5 py-4 text-center">{{ $totals['draft_courses'] }}</td>
                            <td class="px-5 py-4 text-center">{{ $totals['rejected_courses'] }}</td>
                            <td class="px-5 py-4 text-center">{{ $totals['total_enrollments'] }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Кортҳои ҳисобот барои мобилӣ (390px) --}}
        <div class="md:hidden space-y-3">
            @foreach($rows as $row)
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="font-bold text-base text-[#1E293B]">{{ $row['category_title'] }}</h3>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#ECFDF5] text-[#00875A]">
                            {{ $row['total_courses'] }} курс
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                        <div>Нашршуда: <strong class="text-emerald-700">{{ $row['published_courses'] }}</strong></div>
                        <div>Дар баррасӣ: <strong class="text-amber-600">{{ $row['pending_courses'] }}</strong></div>
                        <div>Лоиҳа: <strong>{{ $row['draft_courses'] }}</strong></div>
                        <div>Донишҷӯён: <strong class="text-[#064E3B]">{{ $row['total_enrollments'] }}</strong></div>
                    </div>
                </div>
            @endforeach

            <div class="bg-[#ECFDF5] p-4 rounded-2xl border border-[#A7F3D0] text-sm font-bold text-[#064E3B] flex items-center justify-between">
                <span>Ҷамъбасти умумӣ:</span>
                <span>{{ $totals['total_courses'] }} курс • {{ $totals['total_enrollments'] }} донишҷӯ</span>
            </div>
        </div>
    </div>
</x-app-layout>
