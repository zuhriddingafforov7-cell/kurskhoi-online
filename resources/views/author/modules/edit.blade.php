<x-app-layout :title="'Таҳрири модул — Biyomuz Academy'">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    Курс: {{ $course->title }}
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Таҳрири модул</h1>
            </div>
            <a href="{{ route('author.courses.show', $course) }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                ← Бозгашт
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('author.modules.update', $module) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1">
                        Номи модул <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $module->title) }}"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order_index" class="block text-xs font-bold text-slate-700 mb-1">
                        Тартиби модул <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="order_index"
                        name="order_index"
                        value="{{ old('order_index', $module->order_index) }}"
                        min="1"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                    @error('order_index')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('author.courses.show', $course) }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Захира кардан
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
