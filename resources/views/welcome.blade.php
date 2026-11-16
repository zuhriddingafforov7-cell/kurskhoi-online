<x-app-layout :fullWidth="true">
    {{-- HERO SECTION — Biyomuz Academy --}}
    <section class="relative bg-gradient-to-br from-[#064E3B] via-[#00875A] to-[#10B981] text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.2&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 lg:py-24 relative">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-sm text-xs sm:text-sm font-semibold mb-5 border border-white/20 shadow-xs">
                    <span>🎓</span>
                    <span>{{ __('Платформаи миллии омӯзиши онлайн') }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                    Biyomuz <span class="text-[#D1FAE5]">Academy</span>
                </h1>

                <p class="mt-4 text-lg sm:text-2xl text-[#D1FAE5] font-medium italic">
                    «{{ __('Имрӯз биёмӯз, фардо муваффақ шав!') }}»
                </p>

                <p class="mt-3 text-sm sm:text-lg text-white/90 max-w-xl leading-relaxed">
                    Курсҳои касбӣ аз беҳтарин муаллифон бо забони тоҷикӣ, дарсҳои пайдарпай, супоришҳои амалӣ ва шаҳодатномаи расмии хатм.
                </p>

                {{-- Ҷустуҷӯи фаврии курсҳо дар Hero --}}
                <form action="{{ route('courses.index') }}" method="GET" class="mt-7 max-w-xl">
                    <div class="flex flex-col sm:flex-row gap-2.5 bg-white/15 p-2 rounded-2xl backdrop-blur-sm border border-white/20">
                        <input
                            type="text"
                            name="search"
                            placeholder="{{ __('Ҷустуҷӯи курс...') }}"
                            class="flex-1 px-4 py-3 rounded-xl bg-white text-[#1E293B] placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]"
                        >
                        <button type="submit" class="px-6 py-3 bg-[#064E3B] hover:bg-[#022C22] text-white font-bold rounded-xl text-sm transition shrink-0 shadow-sm">
                            {{ __('Ҷустуҷӯ') }}
                        </button>
                    </div>
                </form>

                <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('courses.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 bg-white text-[#064E3B] font-bold rounded-xl sm:rounded-2xl hover:bg-[#D1FAE5] transition-all duration-200 shadow-lg text-base">
                        {{ __('Каталоги курсҳо') }} →
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 border-2 border-white/50 text-white font-bold rounded-xl sm:rounded-2xl hover:bg-white/10 transition-all duration-200 text-base">
                            {{ __('Бақайдгирӣ — Ройгон') }}
                        </a>
                    @else
                        @php
                            $dashRoute = match(auth()->user()->role) {
                                'admin' => 'admin.dashboard',
                                'author' => 'author.dashboard',
                                default => 'student.dashboard',
                            };
                        @endphp
                        <a href="{{ route($dashRoute) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 bg-[#064E3B] text-white font-bold rounded-xl sm:rounded-2xl hover:bg-[#022C22] transition-all duration-200 text-base border border-white/20">
                            {{ __('Ба панели идоракунӣ') }} →
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    {{-- ОМОРИ ВОҚЕИИ ПЛАТФОРМА --}}
    <section class="py-8 sm:py-10 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8 text-center">
                <div class="p-3 sm:p-4 rounded-2xl bg-[#F8FAFC] border border-slate-100">
                    <div class="text-2xl sm:text-4xl font-black text-[#00875A]">{{ $stats['courses_count'] ?? $featuredCourses->count() }}</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Курсҳои нашршуда</div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl bg-[#F8FAFC] border border-slate-100">
                    <div class="text-2xl sm:text-4xl font-black text-[#10B981]">{{ $stats['categories_count'] ?? $categories->count() }}</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">{{ __('Категорияҳо') }}</div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl bg-[#F8FAFC] border border-slate-100">
                    <div class="text-2xl sm:text-4xl font-black text-[#064E3B]">{{ $stats['students_count'] ?? 0 }}</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Донишҷӯён</div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl bg-[#F8FAFC] border border-slate-100">
                    <div class="text-2xl sm:text-4xl font-black text-[#00875A]">{{ $stats['certificates_count'] ?? 0 }}</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Шаҳодатномаҳои PDF</div>
                </div>
            </div>
        </div>
    </section>

    {{-- КАТЕГОРИЯҲО --}}
    @if($categories->count() > 0)
    <section class="py-12 sm:py-16 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 sm:mb-10">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">{{ __('Категорияҳои омӯзишӣ') }}</h2>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500">Самти дилхоҳатонро интихоб намуда, ба омӯзиш оғоз кунед</p>
            </div>
            <div class="flex flex-wrap justify-center gap-2.5 sm:gap-3">
                @foreach($categories as $category)
                    <a href="{{ route('courses.index', ['category' => $category->id]) }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:border-[#00875A]/50 hover:text-[#00875A] hover:bg-[#ECFDF5] hover:shadow-xs transition-all duration-200">
                        <span>{{ $category->name }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-xs text-slate-500 font-normal">{{ $category->courses_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- КУРСҲОИ МАШҲУР --}}
    @if(isset($popularCourses) && $popularCourses->count() > 0)
    <section class="py-12 sm:py-16 bg-white border-t border-slate-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] mb-2">Интихоби донишҷӯён</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">{{ __('Курсҳои машҳур') }}</h2>
                </div>
                <a href="{{ route('courses.index', ['sort' => 'popular']) }}" class="text-sm font-semibold text-[#00875A] hover:text-[#064E3B] transition">
                    {{ __('Ҳамаи курсҳоро бинед') }} →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($popularCourses as $course)
                    <x-course-card :course="$course" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- КУРСҲОИ НАВТАРИН --}}
    @if($featuredCourses->count() > 0)
    <section class="py-12 sm:py-16 bg-[#F8FAFC] border-t border-slate-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">{{ __('Курсҳои навтарин') }}</h2>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">Донишҳои навро аз мутахассисони пешқадам касб кунед</p>
                </div>
                <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-[#00875A] hover:text-[#064E3B] transition">
                    {{ __('Каталоги курсҳо') }} →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($featuredCourses as $course)
                    <x-course-card :course="$course" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- БАРТАРИҲОИ ПЛАТФОРМА --}}
    <section id="about" class="py-12 sm:py-20 bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] mb-3">
                    Biyomuz Academy чист?
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-[#1E293B] tracking-tight">
                    Бартариҳои омӯзиш дар Biyomuz Academy
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                    Сохтори муназзами сезинагии «Курс → Модул → Дарс» ба шумо имкон медиҳад, ки донишҳои муосири соҳаи IT-ро бо забони тоҷикӣ пайдарпай омӯзед.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-[#F8FAFC] p-6 sm:p-8 rounded-2xl border border-slate-200 hover:border-[#A7F3D0] transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-[#00875A] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1E293B] mb-2">Омӯзиши пайдарпай</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Дарсҳо аз рӯйи модулҳои мантиқӣ сохта шудаанд. То аз худ накардани дарси пешина, дарси оянда қулф мемонад, то дониши устувор ҳосил шавад.
                    </p>
                </div>

                <div class="bg-[#F8FAFC] p-6 sm:p-8 rounded-2xl border border-slate-200 hover:border-[#A7F3D0] transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-[#00875A] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1E293B] mb-2">Супоришҳои амалӣ</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Шумо кори амалиро пешниҳод мекунед ва муаллифи курс онро бо баҳо ва шарҳи муфассал месанҷад.
                    </p>
                </div>

                <div class="bg-[#F8FAFC] p-6 sm:p-8 rounded-2xl border border-slate-200 hover:border-[#A7F3D0] transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-[#00875A] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1E293B] mb-2">Сертификати PDF</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Пас аз анҷоми пурраи дарсҳо ва қабул шудани супоришҳо, шаҳодатномаи PDF бо рамзи беназири санҷишӣ сохта мешавад.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- МУАЛЛИМОНИ ПЛАТФОРМА (танҳо муаллимони воқеии Database) --}}
    @if(isset($instructors) && $instructors->count() > 0)
    <section class="py-12 sm:py-16 bg-[#F8FAFC] border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">{{ __('Муаллимони пешқадам') }}</h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-500">Мутахассисоне, ки курсҳои таълимии платформаро таҳия мекунанд</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($instructors as $instructor)
                    <a href="{{ route('courses.index', ['author' => $instructor->id]) }}" class="bg-white p-6 rounded-2xl border border-slate-200 hover:border-[#00875A]/40 hover:shadow-md transition flex flex-col items-center text-center">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#00875A] to-[#10B981] text-white flex items-center justify-center font-black text-xl mb-3 shadow-xs">
                            {{ mb_substr($instructor->name, 0, 1) }}
                        </div>
                        <h3 class="text-base font-bold text-[#1E293B]">{{ $instructor->name }}</h3>
                        <p class="text-xs text-[#00875A] font-semibold mt-1">{{ $instructor->courses_count }} курси нашршуда</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CALL TO ACTION --}}
    <section class="py-12 sm:py-16 bg-[#00875A] text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <h2 class="text-2xl sm:text-3xl font-extrabold">{{ __('Омодаед ба омӯзиш шурӯъ кунед?') }}</h2>
            <p class="text-sm sm:text-base text-[#D1FAE5] max-w-xl mx-auto">
                Курси мувофиқро интихоб кунед, донишҳои амалӣ гиред ва бо шаҳодатномаи расмӣ малакаи худро тасдиқ намоед.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-3 pt-2">
                <a href="{{ route('courses.index') }}" class="px-7 py-3.5 bg-white text-[#064E3B] font-bold rounded-xl hover:bg-[#D1FAE5] transition shadow-md text-sm sm:text-base">
                    {{ __('Каталоги курсҳо') }}
                </a>
                @guest
                    <a href="{{ route('register') }}" class="px-7 py-3.5 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#022C22] border border-white/20 transition text-sm sm:text-base">
                        {{ __('Бақайдгирӣ — Ройгон') }}
                    </a>
                @endguest
            </div>
        </div>
    </section>
</x-app-layout>
