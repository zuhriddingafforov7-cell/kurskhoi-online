<x-app-layout :title="__('Каталоги курсҳо')" :withSidebar="true">
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div>
                <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">{{ __('Каталоги курсҳо') }}</h1>
                <p class="text-sm text-slate-500 mt-1">Курси дилхоҳатонро интихоб карда, ба омӯзиш оғоз кунед.</p>
            </div>

            <form method="GET" action="{{ route('student.courses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('Ҷустуҷӯи курс...') }}"
                    class="px-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00875A]"
                >

                <select name="category" class="px-3 py-2 text-sm border border-slate-300 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00875A]">
                    <option value="">{{ __('Ҳамаи категорияҳо') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name ?? $category->title }}
                        </option>
                    @endforeach
                </select>

                <select name="level" class="px-3 py-2 text-sm border border-slate-300 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00875A]">
                    <option value="">{{ __('Ҳамаи сатҳҳо') }}</option>
                    <option value="beginner" {{ request('level') === 'beginner' ? 'selected' : '' }}>{{ __('Ибтидоӣ') }}</option>
                    <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>{{ __('Миёна') }}</option>
                    <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>{{ __('Пешрафта') }}</option>
                </select>

                <select name="sort" class="px-3 py-2 text-sm border border-slate-300 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00875A]">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>{{ __('Навтарин') }}</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>{{ __('Машҳуртарин') }}</option>
                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>{{ __('Аз рӯйи ном') }}</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl transition">
                    {{ __('Ҷустуҷӯ') }}
                </button>
            </form>
        </div>

        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <x-course-card :course="$course" :href="route('student.courses.show', $course)" />
                @endforeach
            </div>

            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <p class="text-base text-slate-600">Ҳеҷ курсе ёфт нашуд.</p>
                <a href="{{ route('student.courses.index') }}" class="inline-flex items-center px-4 py-2 mt-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Ҳамаи курсҳо
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
