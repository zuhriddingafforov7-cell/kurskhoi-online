<x-app-layout :title="$assignment->title . ' — Biyomuz Academy'">
    <div class="space-y-6">
        <nav class="flex items-center text-xs text-slate-500 gap-2 flex-wrap">
            <a href="{{ route('student.dashboard') }}" class="hover:text-[#00875A]">Кабинет</a>
            <span>/</span>
            <a href="{{ route('student.lessons.show', $lesson) }}" class="hover:text-[#00875A]">{{ $lesson->title }}</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold truncate">{{ $assignment->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Тарафи чап: Мазмуни супориш ва Форма --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            Супориши амалӣ
                        </span>
                        <span class="text-xs font-bold text-slate-600">
                            Баҳои максималӣ: <strong class="text-[#00875A] text-sm">{{ $assignment->max_score }} хол</strong>
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">
                        {{ $assignment->title }}
                    </h1>

                    <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed bg-slate-50 p-5 rounded-xl border border-slate-200/70">
                        {!! nl2br(e($assignment->description)) !!}
                    </div>
                </div>

                {{-- Натиҷаи санҷиш ва формаи ирсол --}}
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-6">
                    <h2 class="text-lg font-bold text-[#1E293B]">
                        Ирсол ва натиҷаи супориш
                    </h2>

                    @if($submission && $submission->isApproved())
                        <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-xl space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Кори шумо тасдиқ (қабул) шуд!</span>
                                <span class="px-3 py-1 bg-emerald-600 text-white rounded-full text-xs font-black">
                                    Баҳо: {{ $submission->score }} / {{ $assignment->max_score }}
                                </span>
                            </div>
                            @if($submission->feedback)
                                <div class="text-xs sm:text-sm text-emerald-900 bg-white/70 p-3 rounded-lg border border-emerald-200/70">
                                    <strong>Шарҳи муаллиф:</strong> {{ $submission->feedback }}
                                </div>
                            @endif
                        </div>
                    @elseif($submission && $submission->isRejected())
                        <div class="p-5 bg-rose-50 border border-rose-200 rounded-xl space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Супориш барои ислоҳ баргардонида шуд</span>
                                @if($submission->score !== null)
                                    <span class="px-3 py-1 bg-rose-600 text-white rounded-full text-xs font-black">
                                        Баҳо: {{ $submission->score }} / {{ $assignment->max_score }}
                                    </span>
                                @endif
                            </div>
                            @if($submission->feedback)
                                <div class="text-xs sm:text-sm text-rose-900 bg-white/80 p-3 rounded-lg border border-rose-200/70">
                                    <strong>Эрод ва тавсияи муаллиф:</strong> {{ $submission->feedback }}
                                </div>
                            @endif
                        </div>
                    @elseif($submission && $submission->isPending())
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs sm:text-sm text-amber-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Кори шумо фиристода шудааст ва дар интизори санҷиши муаллиф қарор дорад. Агар лозим бошад, пеш аз санҷиш онро нав карда метавонед.</span>
                        </div>
                    @endif

                    @if(!$submission || !$submission->isApproved())
                        <form method="POST" action="{{ route('student.assignments.submit', $assignment) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label for="answer_text" class="block text-xs font-bold text-slate-700 mb-1">
                                    Матни ҷавоби шумо:
                                </label>
                                <textarea
                                    id="answer_text"
                                    name="answer_text"
                                    rows="6"
                                    placeholder="Матни посух, шарҳи кор ё истинод ба лоиҳаро ворид кунед..."
                                    class="w-full text-sm p-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                                >{{ old('answer_text', $submission->answer_text ?? '') }}</textarea>
                                @error('answer_text')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="file" class="block text-xs font-bold text-slate-700 mb-1">
                                    Замима кардани файл (ихтиёрӣ, то 5 МБ — форматҳои PDF, ZIP, DOC, DOCX):
                                </label>
                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    accept=".pdf,.zip,.doc,.docx"
                                    class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#ECFDF5] file:text-[#00875A] hover:file:bg-[#D1FAE5] cursor-pointer"
                                >
                                @error('file')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-xs transition"
                            >
                                {{ $submission ? 'Навсозӣ ва дубора фиристодан' : 'Ирсоли супориш ба муаллиф' }}
                            </button>
                        </form>
                    @endif

                    {{-- Таърихи санҷиш --}}
                    @if($submission && count($submission->grading_history) > 0)
                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Таърихи баҳогузорӣ</h3>
                            <div class="space-y-2">
                                @foreach(array_reverse($submission->grading_history) as $item)
                                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <span class="font-bold text-slate-800">{{ $item['graded_at'] ?? '' }}</span>
                                            <span class="text-slate-400 mx-1">•</span>
                                            <span class="font-semibold {{ ($item['status'] ?? '') === 'approved' ? 'text-emerald-700' : 'text-rose-700' }}">
                                                {{ ($item['status'] ?? '') === 'approved' ? 'Қабул' : 'Рад' }}
                                            </span>
                                            @if(!empty($item['feedback']))
                                                <p class="text-slate-600 mt-1">{{ $item['feedback'] }}</p>
                                            @endif
                                        </div>
                                        <span class="font-bold text-[#1E293B] shrink-0">{{ $item['score'] ?? 0 }} / {{ $item['max_score'] ?? $assignment->max_score }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Тарафи рост: Ҳолат --}}
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4 sticky top-24">
                    <h3 class="text-sm font-bold text-[#1E293B] border-b border-slate-100 pb-3">
                        Ҳолати иҷро
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between items-center py-1 gap-2">
                            <span class="text-slate-500">Ҳолат:</span>
                            @if(!$submission)
                                <span class="font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">Супорида нашудааст</span>
                            @elseif($submission->isApproved())
                                <span class="font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">Қабул шуд</span>
                            @elseif($submission->isRejected())
                                <span class="font-semibold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-full">Рад шуд</span>
                            @else
                                <span class="font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">Дар интизори санҷиш</span>
                            @endif
                        </div>

                        @if($submission)
                            @if($submission->score !== null)
                                <div class="flex justify-between items-center py-1 border-t border-slate-100">
                                    <span class="text-slate-500">Баҳо:</span>
                                    <span class="font-bold text-[#00875A]">{{ $submission->score }} / {{ $assignment->max_score }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center py-1 border-t border-slate-100">
                                <span class="text-slate-500">Санаи навсозӣ:</span>
                                <span class="font-medium text-slate-700">{{ $submission->updated_at->format('d.m.Y H:i') }}</span>
                            </div>

                            @if($submission->file_path)
                                <div class="py-2 border-t border-slate-100">
                                    <span class="text-slate-500 block mb-1">Файли фиристодашуда:</span>
                                    <a href="{{ route('submissions.download', $submission) }}" class="text-[#00875A] hover:underline font-semibold flex items-center gap-1.5">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Зеркашии файл</span>
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('student.lessons.show', $lesson) }}" class="w-full flex items-center justify-center px-4 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-700 hover:bg-slate-50 transition">
                            ← Бозгашт ба дарс
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
