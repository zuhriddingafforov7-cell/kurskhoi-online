<x-app-layout title="Каталоги курсҳо — Biyomuz Academy">
    <div class="space-y-6">
        <!-- Сарлавҳа ва Ҷустуҷӯ -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">Каталоги курсҳои таълимӣ</h1>
                <p class="text-sm text-slate-500 mt-1">Курси дилхоҳатонро интихоб карда, ба омӯзиш оғоз кунед.</p>
            </div>

            <!-- Формаи ҷустуҷӯ ва филтр -->
            <form method="GET" action="{{ route('student.courses.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <select name="category" onchange="this.form.submit()" class="px-3.5 py-2 text-sm border border-slate-300 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#00875A]">
                    <option value="">Ҳамаи категорияҳо</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name ?? $category->title }}
                        </option>
                    @endforeach
                </select>

                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Ҷустуҷӯи курс..."
                        class="w-full sm:w-64 pl-9 pr-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00875A]"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-sm font-semibold rounded-xl transition">
                    Ҷустуҷӯ
                </button>
            </form>
        </div>

        <!-- Рӯйхати курсҳо -->
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0]">
                                    {{ $course->category->name ?? $course->category->title ?? 'Барномасозӣ' }}
                                </span>
                                <span class="text-xs text-slate-500 font-medium">
                                    {{ $course->enrollments_count ?? 0 }} донишҷӯ
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-[#1E293B] hover:text-[#00875A] transition">
                                <a href="{{ route('student.courses.show', $course) }}">{{ $course->title }}</a>
                            </h3>

                            <p class="text-xs text-slate-500 mt-2 line-clamp-3">
                                {{ $course->description }}
                            </p>

                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>Муаллиф: <strong class="text-slate-800">{{ $course->author->name ?? 'Муаллиф' }}</strong></span>
                                <span class="font-bold text-[#00875A] text-sm">{{ $course->price > 0 ? number_format($course->price, 0) . ' сомонӣ' : 'Ройгон' }}</span>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('student.courses.show', $course) }}" class="text-xs font-semibold text-slate-600 hover:text-[#00875A] transition">
                                Муфассал
                            </a>

                            <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                    Номнавис шудан
                                </button>
                            </form>
                        </div>
                    </div>
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
