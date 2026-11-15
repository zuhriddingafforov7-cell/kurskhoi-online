<x-app-layout :fullWidth="true">
    {{-- ═══════════════════════════════════════════════════════════
         HERO SECTION — Biyomuz Academy
         Мутобиқ ба ҳамаи экранҳо (320px то 1920px+)
         ═══════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-br from-[#064E3B] via-[#00875A] to-[#10B981] text-white overflow-hidden">
        {{-- Заминаи графикии сабук --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.2&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="absolute top-0 right-0 -mt-16 -mr-16 sm:-mt-20 sm:-mr-20 w-72 h-72 sm:w-96 sm:h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-24 -ml-24 sm:-mb-32 sm:-ml-32 w-64 h-64 sm:w-80 sm:h-80 bg-[#10B981]/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32 relative">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-sm text-xs sm:text-sm font-semibold mb-6 border border-white/20 shadow-xs">
                    <span>🎓</span>
                    <span>Платформаи миллии омӯзиши онлайн</span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight sm:leading-none">
                    <span class="block">Biyomuz</span>
                    <span class="block text-[#D1FAE5]">Academy</span>
                </h1>
                <p class="mt-4 sm:mt-6 text-lg sm:text-2xl text-[#D1FAE5] font-medium italic">
                    «Имрӯз биёмӯз, фардо муваффақ шав!»
                </p>
                <p class="mt-3 sm:mt-4 text-sm sm:text-lg text-white/85 max-w-xl leading-relaxed">
                    Курсҳои касбии видеоӣ аз беҳтарин муаллифон. Бо забони тоҷикӣ, бо супоришҳои амалӣ ва шаҳодатномаи расмии хатм.
                </p>
                <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('courses.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3.5 sm:py-4 bg-white text-[#064E3B] font-bold rounded-xl sm:rounded-2xl hover:bg-[#D1FAE5] transition-all duration-200 shadow-lg hover:shadow-xl text-base sm:text-lg">
                        Каталоги курсҳо
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3.5 sm:py-4 border-2 border-white/40 text-white font-bold rounded-xl sm:rounded-2xl hover:bg-white/10 hover:border-white/60 transition-all duration-200 text-base sm:text-lg">
                            Бақайдгирӣ — Ройгон
                        </a>
                    @else
                        @php $dashRoute = match(auth()->user()->role) { 'admin' => 'admin.dashboard', 'author' => 'author.dashboard', default => 'student.dashboard' }; @endphp
                        <a href="{{ route($dashRoute) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3.5 sm:py-4 bg-[#064E3B] text-white font-bold rounded-xl sm:rounded-2xl hover:bg-[#022C22] transition-all duration-200 text-base sm:text-lg border border-white/20">
                            Ба панели идоракунӣ →
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         ОМОРИ ПЛАТФОРМА (Stats)
         2 сутун дар телефон, 4 сутун дар планшет ва болотар
         ═══════════════════════════════════════════════════════════ --}}
    <section class="py-8 sm:py-12 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8 text-center">
                <div class="p-3 sm:p-4 rounded-2xl hover:bg-[#F8FAFC] transition-colors">
                    <div class="text-2xl sm:text-4xl font-black text-[#00875A]">{{ $featuredCourses->count() }}+</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Курсҳои фаъол</div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl hover:bg-[#F8FAFC] transition-colors">
                    <div class="text-2xl sm:text-4xl font-black text-[#10B981]">{{ $categories->count() }}</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Категорияҳои таълимӣ</div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl hover:bg-[#F8FAFC] transition-colors">
                    <div class="text-2xl sm:text-4xl font-black text-[#064E3B]">24/7</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Дастрасии онлайн</div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl hover:bg-[#F8FAFC] transition-colors">
                    <div class="text-2xl sm:text-4xl font-black text-[#00875A]">PDF</div>
                    <div class="text-xs sm:text-sm font-medium text-slate-500 mt-1">Сертификати расмӣ</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         КАТЕГОРИЯҲО (Categories Chips)
         ═══════════════════════════════════════════════════════════ --}}
    @if($categories->count() > 0)
    <section class="py-12 sm:py-16 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 sm:mb-10">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">Категорияҳои омӯзишӣ</h2>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500">Самти дилхоҳатонро интихоб намуда, ба омӯзиш оғоз кунед</p>
            </div>
            <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                @foreach($categories as $category)
                    <a href="{{ route('courses.index', ['category' => $category->id]) }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:border-[#00875A]/50 hover:text-[#00875A] hover:bg-[#ECFDF5] hover:shadow-xs transition-all duration-200">
                        <span>{{ $category->name }}</span>
                        <span class="text-xs text-slate-400 font-normal">({{ $category->courses_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         КУРСҲОИ НАМОЁН (Featured Courses)
         Телефон (320px–767px): 1 сутун
         Планшет (768px–1023px): 2 сутун
         Ноутбук ва Монитор (1024px+): 3 сутун
         ═══════════════════════════════════════════════════════════ --}}
    @if($featuredCourses->count() > 0)
    <section class="py-12 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-14">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">Курсҳои навтарин</h2>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500">Донишҳои навро аз мутахассисони пешқадам касб кунед</p>
            </div>

            {{-- Шабакаи мутобиқшавандаи кортҳо --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($featuredCourses as $course)
                    <a href="{{ route('courses.show', $course) }}" class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-[#00875A]/5 hover:border-[#A7F3D0] transition-all duration-300 flex flex-col">
                        <div class="h-40 sm:h-44 bg-gradient-to-br from-[#064E3B] via-[#00875A] to-[#10B981] flex items-center justify-center relative overflow-hidden shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            <span class="text-5xl font-black text-white/20 group-hover:scale-110 transition-transform duration-300">{{ mb_substr($course->title, 0, 1) }}</span>
                            @if($course->category)
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm text-white">
                                    {{ $course->category->name }}
                                </span>
                            @endif
                        </div>
                        <div class="p-5 sm:p-6 flex flex-col flex-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#1E293B] group-hover:text-[#00875A] transition-colors duration-200 line-clamp-2 leading-snug">
                                {{ $course->title }}
                            </h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-500 line-clamp-2 flex-1 leading-relaxed">
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#00875A] font-bold text-xs shrink-0">
                                        {{ mb_substr($course->author->name ?? 'M', 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-700 truncate max-w-[120px]">{{ $course->author->name ?? 'Муаллиф' }}</span>
                                </div>
                                <span class="text-[#00875A] font-semibold shrink-0">{{ $course->enrollments_count }} талаба</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-10 sm:mt-14">
                <a href="{{ route('courses.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-[#00875A] hover:bg-[#064E3B] text-white font-semibold rounded-xl shadow-sm shadow-[#00875A]/20 hover:shadow-md transition-all duration-200 text-sm sm:text-base">
                    Ҳамаи курсҳоро бинед →
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         ДАР БОРАИ МО (About Us Section — Anchor #about)
         ═══════════════════════════════════════════════════════════ --}}
    <section id="about" class="py-12 sm:py-20 bg-[#F8FAFC] border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] mb-3">
                    Biyomuz Academy чист?
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-[#1E293B] tracking-tight">
                    Ояндаи омӯзиши касбӣ дар Тоҷикистон
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                    Мо ҳадаф дорем, ки ба ҳар як тоҷикистонӣ имкони омӯзиши донишҳои муосир ва серталаби бозори меҳнатро бо забони модарӣ фароҳам оварем.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs hover:border-[#A7F3D0] transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-[#00875A] flex items-center justify-center text-2xl font-bold mb-4">
                        🎯
                    </div>
                    <h3 class="text-lg font-bold text-[#1E293B] mb-2">Омӯзиши пайдарпай</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Дарсҳо аз рӯйи модулҳои мантиқӣ сохта шудаанд. То аз худ накардани мавзӯи пешина, дарси оянда қулф мемонад, то дониши мустаҳкам ба даст оред.
                    </p>
                </div>

                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs hover:border-[#A7F3D0] transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-[#00875A] flex items-center justify-center text-2xl font-bold mb-4">
                        📝
                    </div>
                    <h3 class="text-lg font-bold text-[#1E293B] mb-2">Супоришҳои амалӣ</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Ҳар як модул бо вазифаҳои воқеӣ ҳамроҳӣ мешавад. Шумо лоиҳаи худро месозед ва муаллиф онро тафтиш карда, баҳо ва тавсия медиҳад.
                    </p>
                </div>

                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs hover:border-[#A7F3D0] transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-[#00875A] flex items-center justify-center text-2xl font-bold mb-4">
                        📜
                    </div>
                    <h3 class="text-lg font-bold text-[#1E293B] mb-2">Сертификати тасдиқшуда</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Пас аз хатми пурраи курс ва супоридани ҳамаи вазифаҳо, ба шумо шаҳодатномаи рақамии PDF бо рақами беназири санҷишӣ дода мешавад.
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
