<x-app-layout title="Модератсияи курсҳо — Biyomuz Academy" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳа ва Филтрҳо -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200 mb-2">
                        Панели маъмур
                    </span>
                    <h1 class="text-2xl font-bold text-[#1E293B]">Модератсия ва идоракунии курсҳо</h1>
                    <p class="text-sm text-slate-500 mt-1">Санҷиши сифати маводҳо, тасдиқ ва рад намудани курсҳои пешниҳодшуда.</p>
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <a href="{{ route('manage.courses.index') }}" class="px-3.5 py-1.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl transition">
                            Идоракунии курсҳо →
                        </a>
                        <a href="{{ route('reports.courses') }}" class="px-3.5 py-1.5 bg-[#ECFDF5] text-[#00875A] hover:bg-[#D1FAE5] border border-[#A7F3D0] text-xs font-semibold rounded-xl transition">
                            Ҳисобот аз рӯйи категорияҳо
                        </a>
                    </div>
                </div>

                <!-- Филтри ҳолат -->
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <a href="{{ route('admin.courses.index', array_filter(['search' => request('search')])) }}" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ !request('status') ? 'bg-[#00875A] text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Ҳама
                    </a>
                    <a href="{{ route('admin.courses.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                        Мунтазир
                    </a>
                    <a href="{{ route('admin.courses.index', array_filter(['status' => 'published', 'search' => request('search')])) }}" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ request('status') === 'published' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                        Нашршуда
                    </a>
                    <a href="{{ route('admin.courses.index', array_filter(['status' => 'rejected', 'search' => request('search')])) }}" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-800 hover:bg-rose-100' }}">
                        Радшуда
                    </a>
                </div>
            </div>

            <!-- Сатри ҷустуҷӯ ва филтри категория -->
            <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 pt-3 border-t border-slate-100">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Ҷустуҷӯи курс..."
                    class="text-sm px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none w-full sm:w-72 bg-slate-50/50 focus:bg-white"
                >
                <select name="category_id" class="text-sm px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white">
                    <option value="">{{ __('Ҳамаи категорияҳо') }}</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ (int) request('category_id') === (int) $cat->id ? 'selected' : '' }}>
                            {{ $cat->title }}
                        </option>
                    @endforeach
                </select>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-xl transition whitespace-nowrap">
                        Ёфтан
                    </button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('admin.courses.index', array_filter(['status' => request('status')])) }}" class="px-3 py-2 text-xs sm:text-sm font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition whitespace-nowrap">
                            Тоза
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Ҷадвали курсҳо -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-bold">
                            <th class="px-2.5 2xl:px-3.5 py-3.5">Номи курс</th>
                            <th class="px-2.5 2xl:px-3.5 py-3.5">Муаллиф</th>
                            <th class="px-2.5 2xl:px-3.5 py-3.5">Категория</th>
                            <th class="px-2.5 2xl:px-3.5 py-3.5 text-center whitespace-nowrap">Донишҷӯён</th>
                            <th class="px-2.5 2xl:px-3.5 py-3.5 whitespace-nowrap">Ҳолат</th>
                            <th class="px-2.5 2xl:px-3.5 py-3.5 text-right whitespace-nowrap">Амалҳо</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($courses as $course)
                            <tr class="hover:bg-slate-50/60 transition align-top">
                                <td class="px-2.5 2xl:px-3.5 py-4 min-w-[160px] max-w-[260px]">
                                    <div class="font-bold text-sm sm:text-base text-[#1E293B] leading-snug">{{ $course->title }}</div>
                                    <div class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">{{ $course->description }}</div>
                                    @if($course->status === 'rejected' && $course->rejection_reason)
                                        <div class="mt-2 text-xs text-rose-700 bg-rose-50 border border-rose-200 rounded-lg px-2.5 py-1.5">
                                            <strong>Сабаби рад:</strong> {{ $course->rejection_reason }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-2.5 2xl:px-3.5 py-4">
                                    <div class="text-sm font-semibold text-slate-800 leading-snug">{{ $course->author->name ?? 'Номаълум' }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5 break-all">{{ $course->author->email ?? '' }}</div>
                                </td>
                                <td class="px-2.5 2xl:px-3.5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 whitespace-nowrap">
                                        {{ $course->category->name ?? $course->category->title ?? 'Категория' }}
                                    </span>
                                </td>
                                <td class="px-2.5 2xl:px-3.5 py-4 text-center font-bold text-sm text-slate-700 whitespace-nowrap">
                                    {{ $course->enrollments_count ?? 0 }}
                                </td>
                                <td class="px-2.5 2xl:px-3.5 py-4 whitespace-nowrap">
                                    @php
                                        $statusLabel = match ($course->status) {
                                            'published' => 'Нашршуда',
                                            'pending'   => 'Мунтазир',
                                            'rejected'  => 'Радшуда',
                                            default     => 'Сиёҳнавис',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                        @if($course->status === 'published') bg-emerald-100 text-emerald-800
                                        @elseif($course->status === 'pending') bg-amber-100 text-amber-800
                                        @elseif($course->status === 'rejected') bg-rose-100 text-rose-800
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-2.5 2xl:px-3.5 py-4 text-right whitespace-nowrap align-top" x-data="{ rejecting: false }">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('courses.show', $course) }}" target="_blank" class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-[#00875A] hover:bg-[#ECFDF5] rounded-xl transition whitespace-nowrap" title="Пешнамоиш">
                                            Дидан ↗
                                        </a>

                                        @if($course->status !== 'published')
                                            <form method="POST" action="{{ route('admin.courses.approve', $course) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition whitespace-nowrap">
                                                    Тасдиқ
                                                </button>
                                            </form>
                                        @endif

                                        @if($course->status !== 'rejected')
                                            <button
                                                type="button"
                                                @click="rejecting = !rejecting"
                                                class="px-2.5 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-xl shadow-xs transition whitespace-nowrap"
                                            >
                                                Рад кардан
                                            </button>
                                        @endif
                                    </div>

                                    @if($course->status !== 'rejected')
                                        <form
                                            x-show="rejecting"
                                            x-cloak
                                            method="POST"
                                            action="{{ route('admin.courses.reject', $course) }}"
                                            class="mt-2 flex flex-col items-end gap-1.5"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input
                                                type="text"
                                                name="rejection_reason"
                                                placeholder="Сабаби рад кардан..."
                                                class="text-xs px-2.5 py-1.5 border border-rose-300 rounded-lg focus:ring-1 focus:ring-rose-500 focus:outline-none w-40 bg-white"
                                            >
                                            <button type="submit" class="w-40 text-center px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition whitespace-nowrap">
                                                Тасдиқи рад
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-sm text-slate-400">
                                    Дар ин категория ягон курс ёфт нашуд.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
