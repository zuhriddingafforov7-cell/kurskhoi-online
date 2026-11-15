<x-app-layout>
    <x-slot name="title">Каталоги курсҳо</x-slot>

    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">Каталоги курсҳо</h1>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">Курси мувофиқро аз беҳтарин муаллифон интихоб кунед</p>
    </div>

    <!-- Ҷустуҷӯ ва филтр (Мутобиқ ба телефон ва компютер) -->
    <form method="GET" action="{{ route('courses.index') }}" class="mb-8 flex flex-col sm:flex-row gap-2.5 sm:gap-3">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Ҷустуҷӯи курс..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none transition">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <select name="category" class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none transition bg-white text-slate-700">
            <option value="">Ҳамаи категорияҳо</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }} ({{ $cat->courses_count }})
                </option>
            @endforeach
        </select>
        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white rounded-xl text-sm font-semibold shadow-sm transition">
            Ҷустуҷӯ
        </button>
        @if(request()->hasAny(['search','category']))
            <a href="{{ route('courses.index') }}" class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-600 hover:bg-slate-50 transition text-center">
                Тоза
            </a>
        @endif
    </form>

    @if($courses->count() > 0)
        {{-- Телефон (320px–767px): 1 сутун, Планшет (768px–1023px): 2 сутун, Ноутбук ва Монитор (1024px+): 3 сутун --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($courses as $course)
                <a href="{{ route('courses.show', $course) }}" class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-[#00875A]/5 hover:border-[#A7F3D0] transition-all duration-300 flex flex-col">
                    <div class="h-40 sm:h-44 bg-gradient-to-br from-[#064E3B] via-[#00875A] to-[#10B981] flex items-center justify-center relative overflow-hidden shrink-0">
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
                            {{ Str::limit($course->description, 90) }}
                        </p>
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#00875A] font-bold text-xs shrink-0">
                                    {{ mb_substr($course->author->name ?? 'M', 0, 1) }}
                                </div>
                                <span class="font-medium text-slate-700 truncate max-w-[120px]">{{ $course->author->name ?? '—' }}</span>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    {{ $course->enrollments_count }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    {{ $course->modules_count }} мод.
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $courses->links() }}
        </div>
    @else
        <div class="text-center py-16 sm:py-24 bg-white rounded-2xl border border-dashed border-slate-300">
            <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">📚</div>
            <h3 class="text-base sm:text-lg font-semibold text-slate-700">Курсе ёфт нашуд</h3>
            <p class="text-slate-500 mt-1 sm:mt-2 text-xs sm:text-sm">Параметрҳои ҷустуҷӯро тағйир диҳед</p>
        </div>
    @endif
</x-app-layout>
