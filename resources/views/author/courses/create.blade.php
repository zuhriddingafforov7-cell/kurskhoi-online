<x-app-layout title="Эҷоди курси нав — Biyomuz Academy">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Сарлавҳа -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    Кабинети муаллиф
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Эҷоди курси нав</h1>
                <p class="text-xs text-slate-500 mt-1">Курсро бо маълумотҳои асосӣ пур карда, барои модератсия пешниҳод намоед.</p>
            </div>

            <a href="{{ route('author.courses.index') }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                ← Бозгашт
            </a>
        </div>

        <!-- Форма -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('author.courses.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1">
                        Номи курс <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Масалан: Асосҳои барномасозии Python"
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1">
                        Категория <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                    >
                        <option value="">-- Категорияро интихоб кунед --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name ?? $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 mb-1">
                        Тавсифи муфассали курс <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        required
                        placeholder="Курс чиро меомӯзонад, барои кӣ пешбинӣ шудааст ва талаботи пешакӣ чист..."
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('author.courses.index') }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Сохтан ва идома додан →
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
