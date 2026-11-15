<x-app-layout title="Курсҳои ман — Biyomuz Academy" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳа -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0]">
                        Омӯзиш
                    </span>
                    <span class="text-xs text-slate-400">|</span>
                    <span class="text-xs text-slate-500 font-medium">Ҳамагӣ: {{ $enrollments->count() }} курс</span>
                </div>
                <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">Курсҳои ман</h1>
                <p class="text-sm text-slate-500 mt-1">Рӯйхати ҳамаи курсҳое, ки шумо номнавис шудаед ва раванди омӯзиши онҳо.</p>
            </div>
            <div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    + Номнависӣ ба курси нав
                </a>
            </div>
        </div>

        @if($enrollments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrollments as $enrollment)
                    @php
                        $course = $enrollment->course;
                        $firstLesson = $course->modules->flatMap->lessons->first();
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $enrollment->status === 'completed' ? 'Анҷомёфта' : 'Фаъол' }}
                                </span>
                                @if($enrollment->certificate)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Шаҳодатнома
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-[#1E293B] line-clamp-2 hover:text-[#00875A] transition">
                                <a href="{{ route('student.courses.show', $course) }}">{{ $course->title }}</a>
                            </h3>

                            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Муаллиф: <span class="font-medium text-slate-700">{{ $course->author->name ?? 'Муаллиф' }}</span>
                            </p>

                            <!-- Пешрафт -->
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="flex justify-between items-center text-xs font-medium text-slate-600 mb-1.5">
                                    <span>Пешрафти омӯзиш</span>
                                    <span class="text-[#00875A] font-bold">{{ $enrollment->progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#00875A] to-[#10B981] rounded-full transition-all duration-300" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Тугмаҳо -->
                        <div class="p-4 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between gap-2">
                            <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center text-xs font-semibold text-[#00875A] hover:underline">
                                Барномаи курс →
                            </a>

                            <div class="flex items-center gap-2">
                                @if($enrollment->certificate)
                                    <a href="{{ route('student.certificates.download', $enrollment->certificate) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg shadow-xs transition" title="Зеркашии сертификат">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Сертификат
                                    </a>
                                @endif

                                @if($firstLesson)
                                    <a href="{{ route('student.lessons.show', $firstLesson) }}" class="inline-flex items-center px-3.5 py-1.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-lg shadow-xs transition">
                                        Идомаи дарс
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#00875A] mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1E293B]">Шумо ҳанӯз ба ягон курс номнавис нашудаед</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 mb-6">
                    Каталоги курсҳои моро аз назар гузаронед ва маҳорати нави касбии худро аз имрӯз оғоз намоед.
                </p>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-3 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Ба каталоги курсҳо гузаштан
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
