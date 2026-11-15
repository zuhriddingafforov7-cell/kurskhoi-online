<x-app-layout title="Пешрафт" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳаи саҳифа -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    Донишҷӯ
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">Пешрафти таълимии донишҷӯ</h1>
                <p class="text-sm text-slate-500 mt-1">Хуш омадед, {{ auth()->user()->name }}! Омӯзишро дар Biyomuz Academy идома диҳед.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('student.my-courses') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 hover:bg-[#ECFDF5] hover:border-[#00875A]/30 text-slate-700 hover:text-[#00875A] text-sm font-semibold rounded-xl transition">
                    Курсҳои ман
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Каталоги курсҳо
                </a>
            </div>
        </div>

        <!-- Омори донишҷӯ -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Курсҳои фаъол</div>
                <div class="text-2xl font-bold text-[#00875A] mt-2">{{ $activeCount ?? $enrollments->where('status', 'active')->count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Аз {{ $enrollments->count() }} курси номнависшуда</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Курсҳои хатмшуда</div>
                <div class="text-2xl font-bold text-[#1E293B] mt-2">{{ $completedCount ?? $enrollments->where('status', 'completed')->count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Бомуваффақият анҷомёфта</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Шаҳодатномаҳо</div>
                <div class="text-2xl font-bold text-[#10B981] mt-2">{{ $certCount ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">Сертификатҳои бадастомада</div>
            </div>
        </div>

        <!-- Рӯйхати курсҳои номнависшуда ва омӯзиши дарсҳо -->
        <div id="progress" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden scroll-mt-24">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-[#1E293B]">Омӯзиши дарсҳо</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Пешрафти худро пайгирӣ намуда, дарсҳоро идома диҳед</p>
                </div>
                @if($enrollments->count() > 0)
                    <a href="{{ route('student.my-courses') }}" class="text-xs font-semibold text-[#00875A] hover:underline">
                        Ҳамаашро дидан →
                    </a>
                @endif
            </div>

            @if($enrollments->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($enrollments as $enrollment)
                        <div class="p-6 hover:bg-slate-50/70 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $enrollment->status === 'completed' ? 'Хатм шуд' : 'Дар ҳоли омӯзиш' }}
                                    </span>
                                    @if($enrollment->certificate)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                            Сертификат дорад
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-[#1E293B]">
                                    {{ $enrollment->course->title }}
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Муаллиф: <span class="font-medium text-slate-700">{{ $enrollment->course->author->name ?? 'Номаълум' }}</span>
                                </p>

                                <!-- Прогресс бар -->
                                <div class="space-y-1 pt-1 max-w-md">
                                    <div class="flex justify-between text-xs font-medium text-slate-600">
                                        <span>Пешрафт</span>
                                        <span class="text-[#00875A] font-bold">{{ $enrollment->progress ?? 0 }}%</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-[#00875A] to-[#10B981] rounded-full transition-all duration-300" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('student.courses.show', $enrollment->course) }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                    Идомаи дарс →
                                </a>
                                @if($enrollment->certificate)
                                    <a href="{{ route('student.certificates.download', $enrollment->certificate) }}" class="inline-flex items-center justify-center px-3 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition">
                                        Сертификат
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="mx-auto w-12 h-12 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#00875A] mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-[#1E293B]">Шумо то ҳол ба ягон курс номнавис нашудаед</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-4">
                        Ба каталоги курсҳо равед ва курси дилхоҳатонро барои омӯзиш интихоб кунед.
                    </p>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-5 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        Интихоби курс →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
