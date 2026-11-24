<x-app-layout :title="__('Идоракунии курсҳо') . ' — Biyomuz Academy'" :withSidebar="true">
    <div
        class="space-y-6"
        x-data="{
            deleteOpen: false,
            deleteUrl: '',
            deleteTitle: ''
        }"
        @keydown.escape.window="deleteOpen = false"
    >
        {{-- Сарлавҳа ва тугмаҳои асосӣ --}}
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    {{ $isAdmin ? __('Панели маъмур') : __('Панели муаллиф') }} • PDO Repository
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">{{ __('Идоракунии курсҳо') }}</h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Рӯйхати курсҳо аз пойгоҳи додаи MariaDB бо ҷустуҷӯ, филтрҳо, JOIN ва саҳифабандӣ (10 курс дар як саҳифа).
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('reports.courses') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 border border-[#00875A]/30 bg-[#ECFDF5] text-[#00875A] hover:bg-[#D1FAE5] text-xs sm:text-sm font-semibold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>{{ __('Ҳисоботи курсҳо') }}</span>
                </a>
                <a href="{{ route('manage.courses.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('Иловаи курс') }}</span>
                </a>
            </div>
        </div>

        {{-- Нишондиҳандаҳои мухтасар --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <div class="text-xs text-slate-500">Ҳамаи курсҳо</div>
                <div class="text-xl font-extrabold text-[#1E293B] mt-1">{{ $statusCounts['total'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-emerald-200/80 shadow-xs">
                <div class="text-xs text-emerald-700">Нашршуда</div>
                <div class="text-xl font-extrabold text-[#00875A] mt-1">{{ $statusCounts['published'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-amber-200/80 shadow-xs">
                <div class="text-xs text-amber-700">Дар баррасӣ</div>
                <div class="text-xl font-extrabold text-amber-600 mt-1">{{ $statusCounts['pending'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                <div class="text-xs text-slate-500">Лоиҳа (Draft)</div>
                <div class="text-xl font-extrabold text-slate-700 mt-1">{{ $statusCounts['draft'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-rose-200/80 shadow-xs col-span-2 sm:col-span-1">
                <div class="text-xs text-rose-700">Радшуда</div>
                <div class="text-xl font-extrabold text-rose-600 mt-1">{{ $statusCounts['rejected'] }}</div>
            </div>
        </div>

        {{-- Шакли ҷустуҷӯ ва филтрҳо --}}
        <form method="GET" action="{{ route('manage.courses.index') }}" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                <div class="{{ $isAdmin ? 'lg:col-span-4' : 'lg:col-span-5' }}">
                    <label for="filter_q" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Ҷустуҷӯ</label>
                    <input
                        type="text"
                        id="filter_q"
                        name="q"
                        value="{{ $filters['q'] }}"
                        placeholder="Ном ё тавсифи курс..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none"
                    >
                </div>

                <div class="lg:col-span-3">
                    <label for="filter_category" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Категория</label>
                    <select id="filter_category" name="category_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none">
                        <option value="">{{ __('Ҳамаи категорияҳо') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (int) ($filters['category_id'] ?? 0) === (int) $cat->id ? 'selected' : '' }}>
                                {{ $cat->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label for="filter_status" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Ҳолат</label>
                    <select id="filter_status" name="status" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none">
                        <option value="">Ҳамаи ҳолатҳо</option>
                        <option value="published" {{ $filters['status'] === 'published' ? 'selected' : '' }}>Нашршуда</option>
                        <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>Дар баррасӣ</option>
                        <option value="draft" {{ $filters['status'] === 'draft' ? 'selected' : '' }}>Лоиҳа</option>
                        <option value="rejected" {{ $filters['status'] === 'rejected' ? 'selected' : '' }}>Радшуда</option>
                    </select>
                </div>

                @if($isAdmin)
                    <div class="lg:col-span-3">
                        <label for="filter_author" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Муаллиф</label>
                        <select id="filter_author" name="author_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none">
                            <option value="">{{ __('Ҳамаи муаллимон') }}</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ (int) ($filters['author_id'] ?? 0) === (int) $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="lg:col-span-2">
                        <label for="filter_sort_author" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Мураттабсозӣ</label>
                        <select id="filter_sort_author" name="sort" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] outline-none">
                            <option value="latest" {{ $filters['sort'] === 'latest' ? 'selected' : '' }}>Навтарин</option>
                            <option value="title" {{ $filters['sort'] === 'title' ? 'selected' : '' }}>Аз рӯйи ном</option>
                            <option value="popular" {{ $filters['sort'] === 'popular' ? 'selected' : '' }}>Машҳуртарин</option>
                            <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>Кӯҳнатарин</option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="text-xs text-slate-500">
                    Ёфт шуд: <strong class="text-[#1E293B]">{{ $pagination['total'] }}</strong> курс •
                    Саҳифаи <strong class="text-[#1E293B]">{{ $pagination['page'] }}</strong> аз <strong class="text-[#1E293B]">{{ $pagination['pages'] }}</strong>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($isAdmin)
                        <select name="sort" aria-label="Мураттабсозӣ" class="px-3 py-2 border border-slate-200 rounded-xl text-xs bg-white text-slate-700">
                            <option value="latest" {{ $filters['sort'] === 'latest' ? 'selected' : '' }}>Навтарин</option>
                            <option value="title" {{ $filters['sort'] === 'title' ? 'selected' : '' }}>Аз рӯйи ном</option>
                            <option value="popular" {{ $filters['sort'] === 'popular' ? 'selected' : '' }}>Машҳуртарин</option>
                            <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>Кӯҳнатарин</option>
                        </select>
                    @endif

                    @if($filters['q'] !== '' || $filters['category_id'] || $filters['status'] || $filters['author_id'] || $filters['sort'] !== 'latest')
                        <a href="{{ route('manage.courses.index') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                            {{ __('Тоза') }}
                        </a>
                    @endif

                    <button type="submit" class="px-5 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white rounded-xl text-xs font-bold shadow-xs transition">
                        {{ __('Ҷустуҷӯ') }}
                    </button>
                </div>
            </div>
        </form>

        @if($courses->count() > 0)
            {{-- Ҷадвал барои экранҳои калон (Desktop / Tablet) --}}
            <div class="hidden md:block bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3.5">#</th>
                                <th class="px-4 py-3.5">Номи курс</th>
                                <th class="px-4 py-3.5">Категория</th>
                                <th class="px-4 py-3.5">Муаллиф</th>
                                <th class="px-4 py-3.5 text-center">Донишҷӯ / Модул</th>
                                <th class="px-4 py-3.5">Ҳолат</th>
                                <th class="px-4 py-3.5 text-right">Амалҳо</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($courses as $course)
                                <tr class="hover:bg-slate-50/70 transition align-top">
                                    <td class="px-4 py-3.5 text-xs font-mono text-slate-400">{{ $course['id'] }}</td>
                                    <td class="px-4 py-3.5 max-w-xs">
                                        <a href="{{ route('manage.courses.show', $course['id']) }}" class="font-bold text-[#1E293B] hover:text-[#00875A] transition block">
                                            {{ $course['title'] }}
                                        </a>
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $course['description'] }}</p>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#064E3B]">
                                            {{ $course['category_title'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="font-semibold text-slate-800 text-xs">{{ $course['author_name'] }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center text-xs whitespace-nowrap">
                                        <span class="font-bold text-[#00875A]">{{ $course['enrollments_count'] }}</span>
                                        <span class="text-slate-300 mx-1">/</span>
                                        <span class="text-slate-600">{{ $course['modules_count'] }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        @include('manage.courses._status-badge', ['status' => $course['status']])
                                    </td>
                                    <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('manage.courses.show', $course['id']) }}" class="px-2.5 py-1.5 text-xs font-semibold text-[#00875A] bg-[#ECFDF5] hover:bg-[#D1FAE5] rounded-lg transition">
                                                Дидан
                                            </a>
                                            <a href="{{ route('manage.courses.edit', $course['id']) }}" class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                                                Таҳрир
                                            </a>
                                            <button
                                                type="button"
                                                @click="deleteOpen = true; deleteUrl = @js(route('manage.courses.delete', $course['id'])); deleteTitle = @js($course['title'])"
                                                class="px-2.5 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition"
                                            >
                                                Нест кардан
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Кортҳо барои телефон (Mobile 390px) --}}
            <div class="md:hidden space-y-3">
                @foreach($courses as $course)
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <span class="text-[11px] font-mono text-slate-400">#{{ $course['id'] }}</span>
                                <a href="{{ route('manage.courses.show', $course['id']) }}" class="block font-bold text-base text-[#1E293B] hover:text-[#00875A] break-words">
                                    {{ $course['title'] }}
                                </a>
                            </div>
                            <div class="shrink-0">
                                @include('manage.courses._status-badge', ['status' => $course['status']])
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 line-clamp-2">{{ $course['description'] }}</p>

                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="px-2.5 py-1 rounded-full bg-[#ECFDF5] text-[#064E3B] font-semibold">
                                {{ $course['category_title'] }}
                            </span>
                            <span class="text-slate-600">Муаллиф: <strong>{{ $course['author_name'] }}</strong></span>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                            <span class="text-slate-500">
                                {{ $course['enrollments_count'] }} донишҷӯ • {{ $course['modules_count'] }} модул
                            </span>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('manage.courses.show', $course['id']) }}" class="px-2.5 py-1.5 font-semibold text-[#00875A] bg-[#ECFDF5] rounded-lg">Дидан</a>
                                <a href="{{ route('manage.courses.edit', $course['id']) }}" class="px-2.5 py-1.5 font-semibold text-slate-700 bg-slate-100 rounded-lg">Таҳрир</a>
                                <button
                                    type="button"
                                    @click="deleteOpen = true; deleteUrl = @js(route('manage.courses.delete', $course['id'])); deleteTitle = @js($course['title'])"
                                    class="px-2.5 py-1.5 font-semibold text-rose-600 bg-rose-50 rounded-lg"
                                >
                                    Нест
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Саҳифабандӣ бо нигоҳдории филтрҳо --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                <h3 class="text-lg font-bold text-slate-700">Ягон курс ёфт нашуд</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 mb-5">Параметрҳои ҷустуҷӯ ё филтрҳоро тағйир диҳед ё курси нав илова намоед.</p>
                <a href="{{ route('manage.courses.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs sm:text-sm font-semibold rounded-xl transition">
                    + Иловаи курси нав
                </a>
            </div>
        @endif

        {{-- Равзанаи тасдиқи нест кардани курс (POST + CSRF, бидуни innerHTML) --}}
        <div
            x-show="deleteOpen"
            x-cloak
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-modal-title"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
            style="display: none;"
        >
            <div @click.outside="deleteOpen = false" class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl p-6 space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <h2 id="delete-modal-title" class="text-lg font-bold text-[#1E293B]">Курсро нест мекунед?</h2>
                    <button type="button" @click="deleteOpen = false" aria-label="Пӯшидан" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Курси «<strong class="text-[#1E293B]" x-text="deleteTitle"></strong>» нест карда мешавад. Агар ба курс донишҷӯён номнавис шуда бошанд ё модулҳо дошта бошад, пойгоҳи дода несткуниро бехатар бозмедорад.
                </p>

                <form method="POST" :action="deleteUrl" class="flex items-center justify-end gap-2.5 pt-2">
                    @csrf
                    <button type="button" @click="deleteOpen = false" class="px-4 py-2 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Бекор кардан
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition">
                        Нест кардан
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
