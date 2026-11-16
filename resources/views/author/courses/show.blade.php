<x-app-layout :title="'Идоракунии «' . $course->title . '» — Biyomuz Academy'">
    <div class="space-y-6">
        {{-- Сарлавҳа ва Ҳолати курс --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <a href="{{ route('author.courses.index') }}" class="text-xs text-[#00875A] hover:underline font-semibold">
                        ← Ба курсҳои ман
                    </a>
                    <span class="text-xs text-slate-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                        @if($course->status === 'published') bg-emerald-100 text-emerald-800
                        @elseif($course->status === 'pending') bg-amber-100 text-amber-800
                        @elseif($course->status === 'rejected') bg-rose-100 text-rose-800
                        @else bg-slate-100 text-slate-700 @endif">
                        Ҳолат: {{ ucfirst($course->status) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A]">
                        {{ $course->category->name ?? $course->category->title ?? 'Категория' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        {{ $course->level_label }}
                    </span>
                </div>

                <h1 class="text-2xl font-bold text-[#1E293B]">{{ $course->title }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $course->modules->count() }} модул • {{ $course->modules->flatMap->lessons->count() }} дарс • {{ $enrollments->count() }} донишҷӯ
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if(in_array($course->status, ['draft', 'rejected'], true))
                    <form method="POST" action="{{ route('author.courses.submit', $course) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                            Ирсол ба модератсия
                        </button>
                    </form>
                @endif

                <a href="{{ route('author.courses.modules.create', $course) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    + Иловаи модул
                </a>

                <a href="{{ route('author.courses.edit', $course) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Таҳрири курс
                </a>

                <form method="POST" action="{{ route('author.courses.destroy', $course) }}" onsubmit="return confirm('Оё шумо мутмаин ҳастед, ки ин курсро нест кардан мехоҳед?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2 border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-xl transition">
                        Нест кардан
                    </button>
                </form>
            </div>
        </div>

        {{-- Сабаби радкунии маъмур --}}
        @if($course->status === 'rejected' && $course->rejection_reason)
            <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm space-y-1">
                <div class="font-bold text-rose-800">Сабаби рад кардани курс аз ҷониби маъмур:</div>
                <p>{{ $course->rejection_reason }}</p>
            </div>
        @endif

        {{-- Тавсиф ва Сохтори курс --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Тавсифи курс</h3>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $course->description }}</p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-[#1E293B]">Сохтори курс (Модулҳо ва Дарсҳо)</h2>
                    <a href="{{ route('author.courses.modules.create', $course) }}" class="text-xs font-semibold text-[#00875A] hover:underline">
                        + Модули нав
                    </a>
                </div>

                @forelse($course->modules as $module)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden" x-data="{ open: true }">
                        <div class="p-4 sm:p-5 bg-slate-50/80 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#00875A] text-white font-bold text-xs shrink-0">
                                    {{ $module->order_index }}
                                </span>
                                <div>
                                    <h3 class="text-sm font-bold text-[#1E293B]">{{ $module->title }}</h3>
                                    @if($module->description)
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $module->description }}</p>
                                    @endif
                                    <span class="text-xs text-slate-400">{{ $module->lessons->count() }} дарс</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('author.modules.lessons.create', $module) }}" class="inline-flex items-center px-3 py-1.5 bg-[#ECFDF5] text-[#00875A] hover:bg-[#D1FAE5] text-xs font-semibold rounded-lg border border-[#A7F3D0] transition">
                                    + Дарс илова кун
                                </a>

                                <a href="{{ route('author.modules.edit', $module) }}" class="px-2.5 py-1.5 text-xs text-slate-600 hover:text-slate-900 font-medium">
                                    Таҳрир
                                </a>

                                <form method="POST" action="{{ route('author.modules.destroy', $module) }}" onsubmit="return confirm('Ин модулро нест мекунед?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1.5 text-xs text-rose-500 hover:text-rose-700">
                                        Нест
                                    </button>
                                </form>

                                <button @click="open = !open" type="button" class="p-1 text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                        </div>

                        <div x-show="open" class="divide-y divide-slate-100">
                            @forelse($module->lessons as $lesson)
                                <div class="p-4 hover:bg-slate-50/50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-xs text-slate-400 font-mono">{{ $module->order_index }}.{{ $lesson->order_index }}</span>
                                            <span class="text-sm font-semibold text-slate-800">{{ $lesson->title }}</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                                                {{ $lesson->content_type }}
                                            </span>
                                            @if($lesson->status === 'draft')
                                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800">Лоиҳа</span>
                                            @endif
                                            @if($lesson->duration_minutes)
                                                <span class="text-xs text-slate-400">{{ $lesson->duration_minutes }} дақ</span>
                                            @endif
                                            @if($lesson->materials->count() > 0)
                                                <span class="text-[11px] text-[#00875A] font-medium">• {{ $lesson->materials->count() }} мавод</span>
                                            @endif
                                        </div>

                                        @if($lesson->assignment)
                                            <div class="flex items-center gap-2 pl-6 pt-1 text-xs text-amber-700">
                                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                                                <span>Супориш: <strong>{{ $lesson->assignment->title }}</strong> (Макс: {{ $lesson->assignment->max_score }} хол)</span>
                                                <a href="{{ route('author.assignments.edit', $lesson->assignment) }}" class="text-[#00875A] hover:underline font-semibold ml-2">
                                                    Таҳрир
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 text-xs flex-wrap">
                                        <a href="{{ route('author.lessons.preview', $lesson) }}" class="px-2.5 py-1 text-[#00875A] bg-[#ECFDF5] hover:bg-[#D1FAE5] rounded-lg font-medium transition">
                                            Пешнамоиш
                                        </a>

                                        @if(!$lesson->assignment)
                                            <a href="{{ route('author.lessons.assignments.create', $lesson) }}" class="px-2.5 py-1 text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg font-medium transition">
                                                + Супориш
                                            </a>
                                        @endif

                                        <a href="{{ route('author.lessons.edit', $lesson) }}" class="px-2.5 py-1 text-slate-600 hover:text-slate-900 font-medium">
                                            Таҳрир
                                        </a>

                                        <form method="POST" action="{{ route('author.lessons.destroy', $lesson) }}" onsubmit="return confirm('Ин дарсро нест мекунед?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 text-rose-500 hover:text-rose-700">
                                                Нест
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-xs text-slate-400 text-center">
                                    Дар ин модул ҳанӯз дарс илова нашудааст.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-sm text-slate-500">
                        Ин курс ҳанӯз ягон модул надорад. Тугмаи «+ Иловаи модул»-ро пахш намоед.
                    </div>
                @endforelse
            </div>

            {{-- Омори пешрафти донишҷӯён (FR-11) --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-[#1E293B]">Омори пешрафти донишҷӯён (FR-11)</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Рӯйхати донишҷӯёни номнависшуда ва фоизи азхудкунии курс</p>
                    </div>
                    <span class="text-xs font-semibold text-[#00875A]">Ҳамагӣ: {{ $enrollments->count() }}</span>
                </div>

                @if($enrollments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-3">Донишҷӯ</th>
                                    <th class="px-5 py-3">Email</th>
                                    <th class="px-5 py-3">Пешрафт</th>
                                    <th class="px-5 py-3">Ҳолат</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($enrollments as $enr)
                                    <tr>
                                        <td class="px-5 py-3 font-semibold text-slate-800">{{ $enr->user->name ?? '—' }}</td>
                                        <td class="px-5 py-3 text-slate-500">{{ $enr->user->email ?? '—' }}</td>
                                        <td class="px-5 py-3 w-48">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full bg-[#00875A]" style="width: {{ $enr->progress ?? 0 }}%"></div>
                                                </div>
                                                <span class="font-bold text-[#00875A]">{{ $enr->progress ?? 0 }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $enr->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $enr->status === 'completed' ? 'Хатмшуда' : 'Фаъол' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-slate-400">
                        Ҳоло ягон донишҷӯ ба ин курс номнавис нашудааст.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
