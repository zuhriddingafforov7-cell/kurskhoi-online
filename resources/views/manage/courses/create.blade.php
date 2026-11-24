<x-app-layout :title="'Иловаи курси нав — Biyomuz Academy'" :withSidebar="true">
    <div class="max-w-3xl space-y-6">
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    Идоракунии курсҳо
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Иловаи курси нав</h1>
                <p class="text-xs text-slate-500 mt-1">Маълумоти курси навро пур карда захира намоед.</p>
            </div>
            <a href="{{ route('manage.courses.index') }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition shrink-0">
                ← Бозгашт ба рӯйхат
            </a>
        </div>

        <div class="bg-white p-5 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
            <form method="POST" action="{{ route('manage.courses.store') }}" novalidate>
                @csrf
                @include('manage.courses._form')

                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('manage.courses.index') }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                        Сабти курси нав
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
