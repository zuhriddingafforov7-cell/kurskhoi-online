<x-app-layout>
    <x-slot name="title">{{ $course->title }}</x-slot>

    <div class="max-w-5xl mx-auto">
        <!-- Сарлавҳаи курс (Мутобиқ ба ҳамаи экранҳо) -->
        <div class="bg-gradient-to-br from-[#064E3B] via-[#00875A] to-[#10B981] rounded-2xl p-6 sm:p-8 text-white mb-6 sm:mb-8 shadow-md relative overflow-hidden">
            @if($course->category)
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm mb-3 sm:mb-4">
                    {{ $course->category->name }}
                </span>
            @endif
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black mb-3 break-words leading-tight">
                {{ $course->title }}
            </h1>
            <p class="text-[#D1FAE5] text-sm sm:text-base max-w-2xl leading-relaxed">
                {{ $course->description }}
            </p>
            <div class="mt-5 sm:mt-6 flex flex-wrap items-center gap-3 sm:gap-6 text-xs sm:text-sm text-[#A7F3D0]">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#A7F3D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Муаллиф: <strong class="text-white">{{ $course->author->name ?? '—' }}</strong></span>
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#A7F3D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>{{ $course->enrollments_count ?? 0 }} талаба</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#A7F3D0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>{{ $course->modules->count() }} модул</span>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Мундариҷаи курс (Модулҳо ва дарсҳо) -->
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-[#1E293B]">Мундариҷаи курс</h2>

                @forelse($course->modules as $module)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                        <div class="px-4 sm:px-5 py-3.5 sm:py-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#ECFDF5] flex items-center justify-center text-[#00875A] font-bold text-xs sm:text-sm shrink-0">
                                {{ $module->order ?? $loop->iteration }}
                            </div>
                            <h3 class="font-bold text-[#1E293B] text-sm sm:text-base truncate flex-1">{{ $module->title }}</h3>
                            <span class="text-xs text-slate-400 shrink-0">{{ $module->lessons->count() }} дарс</span>
                        </div>
                        <ul class="divide-y divide-slate-100">
                            @foreach($module->lessons as $lesson)
                                <li class="flex items-center gap-3 px-4 sm:px-5 py-3 text-xs sm:text-sm">
                                    @if($isEnrolled)
                                        <a href="{{ route('student.lessons.show', $lesson) }}" class="flex items-center gap-2.5 w-full text-[#00875A] hover:text-[#064E3B] transition font-medium">
                                            <svg class="w-4 h-4 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="break-words flex-1">{{ $lesson->title }}</span>
                                        </a>
                                    @else
                                        <span class="flex items-center gap-2.5 w-full text-slate-600">
                                            <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            <span class="break-words flex-1">{{ $lesson->title }}</span>
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-200">
                        <p class="text-slate-400 text-sm">Модулҳо ҳанӯз илова нашудаанд</p>
                    </div>
                @endforelse
            </div>

            <!-- Панели амалиёт / Номнависӣ -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden lg:sticky lg:top-24">
                    <div class="p-5 sm:p-6">
                        @if($isEnrolled)
                            <div class="flex items-center gap-2 text-[#00875A] font-bold mb-4 text-sm bg-[#ECFDF5] p-3 rounded-xl border border-[#A7F3D0]">
                                <svg class="w-5 h-5 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Шумо номнавис шудаед</span>
                            </div>
                            @if($enrollment && $course->modules->first()?->lessons->first())
                                <a href="{{ route('student.lessons.show', $course->modules->first()->lessons->first()) }}"
                                    class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-[#00875A] hover:bg-[#064E3B] text-white font-bold rounded-xl transition shadow-sm text-sm sm:text-base">
                                    Идомаи омӯзиш →
                                </a>
                            @endif
                            <a href="{{ route('student.my-courses') }}" class="w-full mt-2.5 inline-flex items-center justify-center px-6 py-3 border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-[#ECFDF5] hover:text-[#00875A] transition text-sm">
                                Курсҳои ман
                            </a>
                        @elseif(auth()->check() && auth()->user()->isStudent())
                            <div class="text-center mb-5">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Арзиши курс</span>
                                <span class="text-2xl sm:text-3xl font-black text-[#00875A]">Ройгон</span>
                            </div>
                            <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3.5 bg-[#00875A] hover:bg-[#064E3B] text-white font-bold rounded-xl transition text-base shadow-sm shadow-[#00875A]/20">
                                    Номнависӣ ба курс
                                </button>
                            </form>
                        @elseif(!auth()->check())
                            <div class="text-center mb-5">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Барои дастрасӣ ба дарсҳо</span>
                                <span class="text-base sm:text-lg font-bold text-[#1E293B]">Ба система ворид шавед</span>
                            </div>
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-[#00875A] hover:bg-[#064E3B] text-white font-bold rounded-xl transition text-sm sm:text-base shadow-sm">
                                Воридшавӣ
                            </a>
                            <a href="{{ route('register') }}" class="w-full mt-2.5 inline-flex items-center justify-center px-6 py-3 border border-slate-200 text-slate-700 hover:text-[#00875A] hover:border-[#00875A]/30 hover:bg-[#ECFDF5] font-semibold rounded-xl transition text-sm">
                                Бақайдгирӣ — Ройгон
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
