@if(!empty($fieldErrors))
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm mb-5" role="alert">
        <div class="font-bold mb-1">Лутфан хатогиҳои зеринро ислоҳ кунед (HTTP 422):</div>
        <ul class="list-disc list-inside space-y-0.5 text-xs">
            @foreach($fieldErrors as $messages)
                @foreach($messages as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-5">
    <div>
        <label for="title" class="block text-xs font-bold text-slate-700 mb-1">
            Номи курс <span class="text-rose-500">*</span>
        </label>
        <input
            type="text"
            id="title"
            name="title"
            value="{{ $old['title'] ?? '' }}"
            placeholder="Масалан: Барномасозии асинхронӣ бо PHP 8.5"
            class="w-full text-sm p-3 border {{ !empty($fieldErrors['title']) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-300' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
        >
        @if(!empty($fieldErrors['title']))
            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $fieldErrors['title'][0] }}</p>
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
                class="w-full text-sm p-3 border {{ !empty($fieldErrors['category_id']) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-300 bg-white' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
            >
                <option value="">-- Категорияро интихоб кунед --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) ($old['category_id'] ?? '') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->title }}
                    </option>
                @endforeach
            </select>
            @if(!empty($fieldErrors['category_id']))
                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $fieldErrors['category_id'][0] }}</p>
            @endif
        </div>

        <div>
            <label for="status" class="block text-xs font-bold text-slate-700 mb-1">
                Ҳолати курс <span class="text-rose-500">*</span>
            </label>
            <select
                id="status"
                name="status"
                class="w-full text-sm p-3 border {{ !empty($fieldErrors['status']) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-300 bg-white' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
            >
                <option value="draft" {{ ($old['status'] ?? '') === 'draft' ? 'selected' : '' }}>Лоиҳа (Draft)</option>
                <option value="pending" {{ ($old['status'] ?? 'pending') === 'pending' ? 'selected' : '' }}>Дар баррасӣ (Pending)</option>
                @if($isAdmin)
                    <option value="published" {{ ($old['status'] ?? '') === 'published' ? 'selected' : '' }}>Нашршуда (Published)</option>
                    <option value="rejected" {{ ($old['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Радшуда (Rejected)</option>
                @endif
            </select>
            @if(!empty($fieldErrors['status']))
                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $fieldErrors['status'][0] }}</p>
            @endif
        </div>
    </div>

    @if($isAdmin)
        <div>
            <label for="author_id" class="block text-xs font-bold text-slate-700 mb-1">
                Муаллифи курс
            </label>
            <select
                id="author_id"
                name="author_id"
                class="w-full text-sm p-3 border {{ !empty($fieldErrors['author_id']) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-300 bg-white' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
            >
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ (string) ($old['author_id'] ?? '') === (string) $author->id ? 'selected' : '' }}>
                        {{ $author->name }} ({{ $author->email }})
                    </option>
                @endforeach
            </select>
            @if(!empty($fieldErrors['author_id']))
                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $fieldErrors['author_id'][0] }}</p>
            @endif
        </div>
    @endif

    <div>
        <label for="description" class="block text-xs font-bold text-slate-700 mb-1">
            Тавсифи курс <span class="text-rose-500">*</span>
        </label>
        <textarea
            id="description"
            name="description"
            rows="6"
            placeholder="Мундариҷаи курс, мавзӯъҳои асосӣ ва натиҷаҳои омӯзишро нависед..."
            class="w-full text-sm p-3 border {{ !empty($fieldErrors['description']) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-300' }} rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
        >{{ $old['description'] ?? '' }}</textarea>
        @if(!empty($fieldErrors['description']))
            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $fieldErrors['description'][0] }}</p>
        @endif
    </div>
</div>
