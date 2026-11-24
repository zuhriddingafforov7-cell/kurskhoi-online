<x-app-layout :title="__('Каталоги курсҳо')">
    <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1E293B]">{{ __('Каталоги курсҳо') }}</h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">Курси мувофиқро бо ҷустуҷӯ, категория, сатҳи омӯзиш ва муаллиф интихоб кунед</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('reports.courses') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-[#00875A]/30 bg-[#ECFDF5] text-[#00875A] hover:bg-[#D1FAE5] text-xs font-semibold transition">
                <span>{{ __('Ҳисоботи курсҳо') }}</span>
            </a>
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isAuthor())
                    <a href="{{ route('manage.courses.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold transition">
                        <span>{{ __('Идоракунии курсҳо') }}</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    {{-- Филтрҳо ва мураттабсозӣ --}}
    <form method="GET" action="{{ route('courses.index') }}" class="mb-8 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="relative md:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('Ҷустуҷӯи курс...') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none transition">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="md:col-span-2">
                <select name="category" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none transition bg-white text-slate-700">
                    <option value="">{{ __('Ҳамаи категорияҳо') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->courses_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select name="level" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none transition bg-white text-slate-700">
                    <option value="">{{ __('Ҳамаи сатҳҳо') }}</option>
                    <option value="beginner" {{ request('level') === 'beginner' ? 'selected' : '' }}>{{ __('Ибтидоӣ') }}</option>
                    <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>{{ __('Миёна') }}</option>
                    <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>{{ __('Пешрафта') }}</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <select name="author" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none transition bg-white text-slate-700">
                    <option value="">{{ __('Ҳамаи муаллимон') }}</option>
                    @foreach($authors ?? [] as $author)
                        <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select name="sort" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none transition bg-white text-slate-700">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>{{ __('Навтарин') }}</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>{{ __('Машҳуртарин') }}</option>
                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>{{ __('Аз рӯйи ном') }}</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
            <span class="text-xs text-slate-500">
                Натиҷа: <strong class="text-[#1E293B]">{{ $courses->total() }}</strong> курс
            </span>
            <div class="flex items-center gap-2">
                @if(request()->hasAny(['search', 'q', 'category', 'category_id', 'level', 'author', 'author_id', 'sort']))
                    <a href="{{ route('courses.index') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        {{ __('Тоза') }}
                    </a>
                @endif
                <button type="submit" class="px-6 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white rounded-xl text-xs font-bold shadow-xs transition">
                    {{ __('Ҷустуҷӯ') }}
                </button>
            </div>
        </div>
    </form>

    @if($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($courses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $courses->links() }}
        </div>
    @else
        <div class="text-center py-16 sm:py-24 bg-white rounded-2xl border border-dashed border-slate-300">
            <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">📚</div>
            <h3 class="text-base sm:text-lg font-semibold text-slate-700">Курсе ёфт нашуд</h3>
            <p class="text-slate-500 mt-1 sm:mt-2 text-xs sm:text-sm">Параметрҳои ҷустуҷӯ ё филтрро тағйир диҳед</p>
        </div>
    @endif
</x-app-layout>
