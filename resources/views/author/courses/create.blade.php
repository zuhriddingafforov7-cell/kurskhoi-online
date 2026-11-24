<x-app-layout :title="'Эҷоди курси нав — Biyomuz Academy'">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    Кабинети муаллиф
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Эҷоди курси нав</h1>
                <p class="text-xs text-slate-500 mt-1">Маълумоти курсро пур карда, ҳамчун лоиҳа захира кунед ё ба модератсия фиристед.</p>
            </div>

            <a href="{{ route('author.courses.index') }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition shrink-0">
                ← Бозгашт
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
            @if(!empty($fieldErrors))
                <div class="p-4 mb-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm" role="alert">
                    <div class="font-bold mb-1">Лутфан хатогиҳои зеринро ислоҳ кунед:</div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($fieldErrors as $messages)
                            @foreach($messages as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $valTitle = $old['title'] ?? old('title', '');
                $valCategory = (string) ($old['category_id'] ?? old('category_id', ''));
                $valLevel = $old['level'] ?? old('level', 'beginner');
                $valDescription = $old['description'] ?? old('description', '');
                $valStatus = $old['status'] ?? old('status', 'pending');
                $errTitle = $fieldErrors['title'][0] ?? $errors->first('title');
                $errCategory = $fieldErrors['category_id'][0] ?? $errors->first('category_id');
                $errDescription = $fieldErrors['description'][0] ?? $errors->first('description');
                $errCover = $fieldErrors['cover_image'][0] ?? $errors->first('cover_image');
            @endphp

            <form method="POST" action="{{ route('author.courses.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1">
                        Номи курс <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ $valTitle }}"
                        required
                        placeholder="Масалан: Асосҳои барномасозии Python"
                        class="w-full text-sm p-3 border {{ $errTitle ? 'border-rose-400' : 'border-slate-300' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                    @if($errTitle)
                        <p class="text-xs text-rose-500 mt-1">{{ $errTitle }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1">
                            Категория <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="w-full text-sm p-3 border {{ $errCategory ? 'border-rose-400' : 'border-slate-300' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                        >
                            <option value="">-- Категорияро интихоб кунед --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $valCategory === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name ?? $category->title }}
                                </option>
                            @endforeach
                        </select>
                        @if($errCategory)
                            <p class="text-xs text-rose-500 mt-1">{{ $errCategory }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="level" class="block text-xs font-bold text-slate-700 mb-1">
                            Сатҳи омӯзиш
                        </label>
                        <select
                            id="level"
                            name="level"
                            class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                        >
                            <option value="beginner" {{ $valLevel === 'beginner' ? 'selected' : '' }}>Ибтидоӣ</option>
                            <option value="intermediate" {{ $valLevel === 'intermediate' ? 'selected' : '' }}>Миёна</option>
                            <option value="advanced" {{ $valLevel === 'advanced' ? 'selected' : '' }}>Пешрафта</option>
                        </select>
                    </div>
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
                        class="w-full text-sm p-3 border {{ $errDescription ? 'border-rose-400' : 'border-slate-300' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >{{ $valDescription }}</textarea>
                    @if($errDescription)
                        <p class="text-xs text-rose-500 mt-1">{{ $errDescription }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="cover_image" class="block text-xs font-bold text-slate-700 mb-1">
                            Сурати муқова (JPG, PNG, WEBP — то 5 МБ, ихтиёрӣ):
                        </label>
                        <input
                            type="file"
                            id="cover_image"
                            name="cover_image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#ECFDF5] file:text-[#00875A]"
                        >
                        @if($errCover)
                            <p class="text-xs text-rose-500 mt-1">{{ $errCover }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1">
                            Ҳолати захиракунӣ
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                        >
                            <option value="pending" {{ $valStatus === 'pending' ? 'selected' : '' }}>Ирсол ба модератсия (Pending)</option>
                            <option value="draft" {{ $valStatus === 'draft' ? 'selected' : '' }}>Нигоҳдорӣ ҳамчун лоиҳа (Draft)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('author.courses.index') }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                        Сохтан ва идома додан →
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
