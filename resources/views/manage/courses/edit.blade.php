<x-app-layout :title="'Таҳрири «' . $course['title'] . '» — Biyomuz Academy'" :withSidebar="true">
    <div class="max-w-3xl space-y-6">
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    PDO CRUD • UPDATE (#{{ $course['id'] }})
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Таҳрири курс</h1>
                <p class="text-xs text-slate-500 mt-1">Тағйиротро ворид намоед; номи такрории курси дигар бо HTTP 422 рад мешавад.</p>
            </div>
            <a href="{{ route('manage.courses.show', $course['id']) }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition shrink-0">
                ← Бозгашт ба курс
            </a>
        </div>

        <div class="bg-white p-5 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
            <form method="POST" action="{{ route('manage.courses.update', $course['id']) }}" novalidate>
                @csrf
                @include('manage.courses._form')

                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('manage.courses.show', $course['id']) }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                        Захираи тағйирот
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
