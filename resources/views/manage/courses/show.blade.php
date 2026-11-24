<x-app-layout :title="$course['title'] . ' — Идоракунии курс'" :withSidebar="true">
    <div class="max-w-4xl space-y-6" x-data="{ deleteOpen: false }" @keydown.escape.window="deleteOpen = false">
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <a href="{{ route('manage.courses.index') }}" class="text-xs font-semibold text-[#00875A] hover:underline">
                        ← Рӯйхати курсҳо
                    </a>
                    <span class="text-xs text-slate-300">|</span>
                    @include('manage.courses._status-badge', ['status' => $course['status']])
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#064E3B]">
                        {{ $course['category_title'] }}
                    </span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#1E293B] break-words">{{ $course['title'] }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Муаллиф: <strong class="text-slate-700">{{ $course['author_name'] }}</strong>
                    @if(!empty($course['author_email']))
                        ({{ $course['author_email'] }})
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 shrink-0">
                @if($course['status'] === 'published')
                    <a href="{{ route('courses.show', $course['id']) }}" class="px-3.5 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Саҳифаи оммавӣ ↗
                    </a>
                @endif
                <a href="{{ route('manage.courses.edit', $course['id']) }}" class="px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white rounded-xl text-xs font-semibold transition">
                    Таҳрир
                </a>
                <button
                    type="button"
                    @click="deleteOpen = true"
                    class="px-4 py-2 border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-semibold transition"
                >
                    Нест кардан
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="text-xs text-slate-400 font-semibold uppercase">Категория (JOIN)</div>
                <div class="text-base font-bold text-[#1E293B] mt-1">{{ $course['category_title'] }}</div>
                <div class="text-xs text-slate-400 mt-0.5">ID категория: #{{ $course['category_id'] ?? '—' }}</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="text-xs text-slate-400 font-semibold uppercase">Муаллиф (JOIN)</div>
                <div class="text-base font-bold text-[#1E293B] mt-1">{{ $course['author_name'] }}</div>
                <div class="text-xs text-slate-400 mt-0.5">{{ $course['author_email'] }}</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="text-xs text-slate-400 font-semibold uppercase">Вобастагиҳо</div>
                <div class="text-base font-bold text-[#00875A] mt-1">{{ $dependencies['enrollments'] }} донишҷӯ</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $dependencies['modules'] }} модули таълимӣ</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Тавсифи курс</h2>
            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $course['description'] }}</p>

            <div class="pt-4 mt-4 border-t border-slate-100 flex flex-wrap gap-6 text-xs text-slate-400">
                <span>ID: <strong class="text-slate-600">#{{ $course['id'] }}</strong></span>
                <span>Эҷод: <strong class="text-slate-600">{{ $course['created_at'] ?? '—' }}</strong></span>
                <span>Навсозӣ: <strong class="text-slate-600">{{ $course['updated_at'] ?? '—' }}</strong></span>
            </div>
        </div>

        {{-- Равзанаи тасдиқи нест кардан --}}
        <div
            x-show="deleteOpen"
            x-cloak
            role="dialog"
            aria-modal="true"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
            style="display: none;"
        >
            <div @click.outside="deleteOpen = false" class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl p-6 space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <h2 class="text-lg font-bold text-[#1E293B]">Курсро нест мекунед?</h2>
                    <button type="button" @click="deleteOpen = false" aria-label="Пӯшидан" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <p class="text-sm text-slate-600">
                    Курси «<strong class="text-[#1E293B]">{{ $course['title'] }}</strong>» нест карда мешавад. Ин амалро баргардонидан мумкин нест.
                </p>

                <form method="POST" action="{{ route('manage.courses.delete', $course['id']) }}" class="flex items-center justify-end gap-2.5 pt-2">
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
