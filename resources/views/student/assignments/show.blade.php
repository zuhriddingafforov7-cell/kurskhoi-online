<x-app-layout :title="$assignment->title . ' — Biyomuz Academy'">
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-xs text-slate-500 gap-2">
            <a href="{{ route('student.dashboard') }}" class="hover:text-[#00875A]">Кабинет</a>
            <span>/</span>
            <a href="{{ route('student.lessons.show', $lesson) }}" class="hover:text-[#00875A]">{{ $lesson->title }}</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold truncate">{{ $assignment->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Тарафи чап: Мазмуни супориш ва Форма (2 сутун) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Тавсифи супориш -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
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

                <!-- Формаи ирсоли ҷавоб ё намоиши ҷавоби ирсолшуда -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                    <h2 class="text-lg font-bold text-[#1E293B]">
                        Ирсоли кори амалӣ
                    </h2>

                    @if($submission && $submission->status === 'graded')
                        <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-xl space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Кори шумо санҷида шуд!</span>
                                <span class="px-3 py-1 bg-emerald-600 text-white rounded-full text-xs font-black">
                                    Баҳо: {{ $submission->score }} / {{ $assignment->max_score }}
                                </span>
                            </div>

                            @if($submission->feedback)
                                <div class="text-xs text-emerald-900 mt-2">
                                    <strong>Шарҳи муаллиф:</strong> {{ $submission->feedback }}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(!$submission || $submission->status !== 'graded')
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
                                    placeholder="Матни посух, шарҳи код ё истинод ба GitHub/лоиҳаро дар ин ҷо ворид кунед..."
                                    class="w-full text-sm p-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                                >{{ old('answer_text', $submission->answer_text ?? '') }}</textarea>
                            </div>

                            <div>
                                <label for="file" class="block text-xs font-bold text-slate-700 mb-1">
                                    Замима кардани файл (ихтиёрӣ, то 20MB):
                                </label>
                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#ECFDF5] file:text-[#00875A] hover:file:bg-[#A7F3D0]/60 cursor-pointer"
                                >
                            </div>

                            <button
                                type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm transition"
                            >
                                {{ $submission ? 'Навсозӣ ва дубора фиристодан' : 'Ирсоли супориш ба муаллиф' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Тарафи рост: Ҳолат (1 сутун) -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-[#1E293B] border-b border-slate-100 pb-3">
                        Ҳолати иҷро
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Ҳолати супориш:</span>
                            @if(!$submission)
                                <span class="font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">Супорида нашудааст</span>
                            @elseif($submission->status === 'pending')
                                <span class="font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full">Дар интизори санҷиш</span>
                            @elseif($submission->status === 'graded')
                                <span class="font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">Санҷида шуд</span>
                            @endif
                        </div>

                        @if($submission)
                            <div class="flex justify-between items-center py-1 border-t border-slate-100">
                                <span class="text-slate-500">Санаи ирсол:</span>
                                <span class="font-medium text-slate-700">{{ $submission->updated_at->format('d.m.Y H:i') }}</span>
                            </div>

                            @if($submission->file_path)
                                <div class="py-2 border-t border-slate-100">
                                    <span class="text-slate-500 block mb-1">Файли шумо:</span>
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" download class="text-[#00875A] hover:underline font-semibold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Зеркашии файли фиристода
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('student.lessons.show', $lesson) }}" class="w-full flex items-center justify-center px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-700 hover:bg-slate-50 transition">
                            ← Бозгашт ба дарс
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
