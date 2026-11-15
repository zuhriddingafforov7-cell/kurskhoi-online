<x-app-layout :title="$course->title . ' — Biyomuz Academy'">
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-xs text-slate-500 gap-2">
            <a href="{{ route('student.dashboard') }}" class="hover:text-[#00875A]">Кабинет</a>
            <span>/</span>
            <a href="{{ route('student.my-courses') }}" class="hover:text-[#00875A]">Курсҳои ман</a>
            <span>/</span>
            <span class="text-slate-800 font-medium truncate">{{ $course->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Тарафи чап: Маълумоти курс ва Модулҳо (2 сутун) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0]">
                            {{ $course->category->name ?? $course->category->title ?? 'Барномасозӣ' }}
                        </span>
                        <span class="text-xs text-slate-400">|</span>
                        <span class="text-xs text-slate-500">Муаллиф: <strong class="text-slate-700">{{ $course->author->name ?? 'Муаллиф' }}</strong></span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B] tracking-tight">
                        {{ $course->title }}
                    </h1>

                    <div class="prose prose-slate max-w-none text-sm text-slate-600 mt-4 leading-relaxed">
                        {{ $course->description }}
                    </div>
                </div>

                <!-- Барномаи курс (Модулҳо ва дарсҳо) -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-lg font-bold text-[#1E293B]">Барномаи таълимии курс</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Ҳамагӣ: {{ $course->modules->count() }} модул, {{ $course->modules->flatMap->lessons->count() }} дарс</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($course->modules as $mIndex => $module)
                            <div class="border border-slate-200 rounded-xl overflow-hidden" x-data="{ open: true }">
                                <button
                                    @click="open = !open"
                                    type="button"
                                    class="w-full flex items-center justify-between p-4 bg-slate-50/80 hover:bg-slate-100/70 text-left transition"
                                >
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#ECFDF5] text-[#00875A] font-bold text-xs">
                                            {{ $mIndex + 1 }}
                                        </span>
                                        <span class="text-sm font-bold text-[#1E293B]">{{ $module->title }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-slate-500">{{ $module->lessons->count() }} дарс</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </button>

                                <div x-show="open" class="divide-y divide-slate-100">
                                    @forelse($module->lessons as $lIndex => $lesson)
                                        <div class="p-3.5 px-4 flex items-center justify-between text-sm hover:bg-slate-50/50 transition">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs text-slate-400 font-medium">{{ $mIndex + 1 }}.{{ $lIndex + 1 }}</span>
                                                <span class="font-medium text-slate-800">{{ $lesson->title }}</span>
                                                @if($lesson->assignment)
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-amber-50 text-amber-700 border border-amber-200">
                                                        Супориш
                                                    </span>
                                                @endif
                                            </div>

                                            <div>
                                                @if($isEnrolled)
                                                    <a href="{{ route('student.lessons.show', $lesson) }}" class="inline-flex items-center text-xs font-semibold text-[#00875A] hover:underline">
                                                        Гузаштан →
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400">Баста</span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 text-xs text-slate-400 text-center">Дар ин модул ҳанӯз дарс илова нашудааст.</div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-slate-500">
                                Барномаи ин курс ҳоло дар ҳоли омодасозӣ аст.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Тарафи рост: Корти номнависӣ ва ҳолат (1 сутун) -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5 sticky top-24">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="text-xs text-slate-400 uppercase font-semibold">Нархи курс</span>
                        <div class="text-3xl font-extrabold text-[#00875A] mt-1">
                            {{ $course->price > 0 ? number_format($course->price, 0) . ' сомонӣ' : 'Ройгон' }}
                        </div>
                    </div>

                    @if($isEnrolled)
                        <div class="p-4 bg-[#ECFDF5] border border-[#A7F3D0] rounded-xl space-y-2">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#00875A]">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Шумо ба ин курс номнавис шудаед!
                            </div>
                            <div class="text-xs text-slate-600">
                                Ҳолат: <strong class="text-slate-800">{{ $enrollment->status === 'completed' ? 'Анҷомёфта' : 'Фаъол' }}</strong>
                            </div>
                        </div>

                        @php
                            $firstLesson = $course->modules->flatMap->lessons->first();
                        @endphp
                        @if($firstLesson)
                            <a href="{{ route('student.lessons.show', $firstLesson) }}" class="w-full flex items-center justify-center px-5 py-3 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                Идомаи омӯзиши дарсҳо →
                            </a>
                        @endif

                        @if($enrollment && $enrollment->certificate)
                            <a href="{{ route('student.certificates.download', $enrollment->certificate) }}" class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Зеркашии Сертификат
                            </a>
                        @endif
                    @else
                        <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-5 py-3 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                Номнавис шудан ба курс
                            </button>
                        </form>
                    @endif

                    <div class="space-y-2 pt-2 text-xs text-slate-500 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#00875A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Дастрасии пурра ба тамоми маводҳо
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#00875A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Супоришҳои амалӣ ва санҷиши муаллиф
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#00875A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Шаҳодатномаи хатм бо рамзи нодир
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
