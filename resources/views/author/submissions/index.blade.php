<x-app-layout title="Санҷиши супоришҳо — Biyomuz Academy" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳа ва Табҳои филтр -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 mb-2">
                    Санҷиши корҳои амалӣ
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Супоришҳои донишҷӯён</h1>
                <p class="text-xs text-slate-500 mt-1">Корҳои фиристодаи донишҷӯёнро санҷида, баҳо, ҳолат ва фикру мулоҳиза гузоред.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                <a href="{{ route('author.submissions.index', ['status' => 'submitted']) }}"
                   class="px-3.5 py-2 rounded-xl transition {{ $filter === 'submitted' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                    Интизор ({{ $counts['submitted'] ?? 0 }})
                </a>
                <a href="{{ route('author.submissions.index', ['status' => 'approved']) }}"
                   class="px-3.5 py-2 rounded-xl transition {{ $filter === 'approved' ? 'bg-[#00875A] text-white shadow-xs' : 'bg-[#ECFDF5] text-[#00875A] hover:bg-[#D1FAE5]' }}">
                    Тасдиқшуда ({{ $counts['approved'] ?? 0 }})
                </a>
                <a href="{{ route('author.submissions.index', ['status' => 'rejected']) }}"
                   class="px-3.5 py-2 rounded-xl transition {{ $filter === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Радшуда ({{ $counts['rejected'] ?? 0 }})
                </a>
                <a href="{{ route('author.submissions.index', ['status' => 'all']) }}"
                   class="px-3.5 py-2 rounded-xl transition {{ $filter === 'all' ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Ҳама ({{ $counts['all'] ?? 0 }})
                </a>
            </div>
        </div>

        @if($submissions->count() > 0)
            <div class="space-y-6">
                @foreach($submissions as $sub)
                    @php
                        $assignment = $sub->assignment;
                        $lesson = $assignment->lesson;
                        $course = $lesson->module->course;
                        $student = $sub->student;
                        $history = $sub->grading_history;
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ expanded: {{ $loop->first && $filter === 'submitted' ? 'true' : 'false' }} }">
                        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#ECFDF5] text-[#00875A]">
                                        {{ $course->title }}
                                    </span>
                                    <span class="text-xs text-slate-400">•</span>
                                    <span class="text-xs text-slate-500">{{ $lesson->title }}</span>
                                    @if($sub->isApproved())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                            ✓ Тасдиқшуда ({{ $sub->score }}/{{ $assignment->max_score }})
                                        </span>
                                    @elseif($sub->isRejected())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700">
                                            ✕ Радшуда ({{ $sub->score ?? 0 }}/{{ $assignment->max_score }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                            ⏳ Интизори санҷиш
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-base font-bold text-[#1E293B]">
                                    Супориш: {{ $assignment->title }}
                                </h3>

                                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 pt-1">
                                    <span>Донишҷӯ: <strong class="text-slate-800">{{ $student->name }}</strong> ({{ $student->email }})</span>
                                    <span>•</span>
                                    <span>Навсозӣ: {{ $sub->updated_at->format('d.m.Y H:i') }}</span>
                                    <span>•</span>
                                    <span>Макс. балл: <strong>{{ $assignment->max_score }}</strong></span>
                                </div>
                            </div>

                            <div>
                                <button
                                    @click="expanded = !expanded"
                                    type="button"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition"
                                >
                                    <span x-text="expanded ? 'Пӯшидан' : '{{ $sub->isPending() ? 'Санҷидан ва баҳо додан' : 'Дидан ё таҳрири баҳо' }}'"></span>
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Ҷавоби донишҷӯ ва Формаи баҳодиҳӣ -->
                        <div x-show="expanded" class="p-6 bg-slate-50/50 space-y-6">
                            <!-- Матни посухи донишҷӯ -->
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Ҷавоби донишҷӯ:</h4>
                                <div class="p-4 bg-white rounded-xl border border-slate-200 text-sm text-slate-700 leading-relaxed">
                                    {!! nl2br(e($sub->answer_text ?: 'Матн ворид карда нашудааст.')) !!}
                                </div>
                            </div>

                            @if($sub->file_path)
                                <div>
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Файли замимашуда (муҳофизатшуда):</h4>
                                    <a href="{{ route('submissions.download', $sub) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-[#ECFDF5] hover:text-[#00875A] text-xs font-semibold rounded-xl transition">
                                        <svg class="w-4 h-4 text-[#00875A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Зеркашии файл ({{ basename($sub->file_path) }})
                                    </a>
                                </div>
                            @endif

                            <!-- Таърихи санҷишҳо -->
                            @if(!empty($history))
                                <div class="p-4 bg-white rounded-xl border border-slate-200 space-y-2">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Таърихи баҳогузорӣ ({{ count($history) }}):</h4>
                                    <div class="divide-y divide-slate-100 text-xs">
                                        @foreach(array_reverse($history) as $item)
                                            <div class="py-2 flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                                <div>
                                                    <span class="font-semibold text-slate-700">{{ $item['graded_at'] ?? '' }}</span>
                                                    <span class="text-slate-400 mx-1">•</span>
                                                    <span class="font-bold {{ ($item['status'] ?? '') === 'approved' ? 'text-emerald-700' : 'text-rose-700' }}">
                                                        {{ ($item['status'] ?? '') === 'approved' ? 'Тасдиқ' : 'Рад' }} ({{ $item['score'] ?? 0 }}/{{ $item['max_score'] ?? $assignment->max_score }})
                                                    </span>
                                                    @if(!empty($item['feedback']))
                                                        <p class="text-slate-500 mt-0.5">{{ $item['feedback'] }}</p>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-slate-400">{{ $item['grader'] ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Формаи гузоштани баҳо -->
                            <div class="p-5 bg-white rounded-xl border border-slate-200">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#00875A] mb-3">Гузоштани баҳо, ҳолат ва шарҳ:</h4>

                                <form method="POST" action="{{ route('author.submissions.grade', $sub) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Хол (0..{{ $assignment->max_score }}):
                                            </label>
                                            <input
                                                type="number"
                                                name="score"
                                                min="0"
                                                max="{{ $assignment->max_score }}"
                                                value="{{ old('score', $sub->score) }}"
                                                required
                                                placeholder="{{ $assignment->max_score }}"
                                                class="w-full text-sm p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                                            >
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Қарори муаллиф:
                                            </label>
                                            <select
                                                name="status"
                                                class="w-full text-sm p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                                            >
                                                <option value="approved" @selected(!$sub->isRejected())>Тасдиқ кардан (Қабул)</option>
                                                <option value="rejected" @selected($sub->isRejected())>Рад кардан (Барои ислоҳ)</option>
                                            </select>
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Шарҳи муаллиф ба донишҷӯ:
                                            </label>
                                            <input
                                                type="text"
                                                name="feedback"
                                                value="{{ old('feedback', $sub->feedback) }}"
                                                placeholder="Аъло иҷро шуд! Ё кадом камбудиро бояд ислоҳ кунад..."
                                                class="w-full text-sm p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                                            >
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="px-5 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                            Сабти баҳо ва фиристодан
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div>
                    {{ $submissions->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="mx-auto w-12 h-12 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#00875A] mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-base font-bold text-[#1E293B]">Дар ин бахш супориш ёфт нашуд</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    @if($filter === 'submitted')
                        Дар ҳоли ҳозир ягон кори интизори санҷиш вуҷуд надорад.
                    @else
                        Мувофиқи филтри интихобшуда супориш мавҷуд нест.
                    @endif
                </p>
            </div>
        @endif
    </div>
</x-app-layout>
