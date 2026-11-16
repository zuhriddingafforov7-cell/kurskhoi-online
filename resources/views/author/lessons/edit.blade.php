<x-app-layout :title="'Таҳрири дарс — Biyomuz Academy'">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    Модул: {{ $lesson->module->title }}
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Таҳрири дарс ва маводҳо</h1>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('author.lessons.preview', $lesson) }}" class="px-4 py-2 bg-[#ECFDF5] hover:bg-[#D1FAE5] text-[#00875A] border border-[#A7F3D0] text-xs font-semibold rounded-xl transition">
                    👁 Пешнамоиш чун донишҷӯ
                </a>
                <a href="{{ route('author.courses.show', $lesson->module->course) }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                    ← Бозгашт
                </a>
            </div>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('author.lessons.update', $lesson) }}" enctype="multipart/form-data" class="space-y-6" x-data="materialsUploader()">
                @csrf
                @method('PUT')

                <!-- Маълумоти асосӣ -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-[#1E293B] border-b border-slate-100 pb-2">Маълумоти асосӣ</h3>

                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 mb-1">
                            Номи дарс <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $lesson->title) }}"
                            required
                            class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                        >
                        @error('title')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="content_type" class="block text-xs font-bold text-slate-700 mb-1">
                                Намуди асосии мавод <span class="text-rose-500">*</span>
                            </label>
                            <select
                                id="content_type"
                                name="content_type"
                                x-model="type"
                                required
                                class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                            >
                                <option value="text">Матнӣ (Назария ва дастурҳо)</option>
                                <option value="video">Видеодарс</option>
                                <option value="file">Ҳуҷҷат ё презентатсия</option>
                            </select>
                        </div>

                        <div>
                            <label for="order_index" class="block text-xs font-bold text-slate-700 mb-1">
                                Тартиби дарс (Рақам) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="order_index"
                                name="order_index"
                                value="{{ old('order_index', $lesson->order_index) }}"
                                min="1"
                                required
                                class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="duration_minutes" class="block text-xs font-bold text-slate-700 mb-1">
                                Давомнокии тахминӣ (дақиқа, ихтиёрӣ)
                            </label>
                            <input
                                type="number"
                                id="duration_minutes"
                                name="duration_minutes"
                                value="{{ old('duration_minutes', $lesson->duration_minutes) }}"
                                min="1"
                                max="600"
                                placeholder="Масалан: 15"
                                class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                            >
                            @error('duration_minutes')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-700 mb-1">
                                Ҳолати дарс
                            </label>
                            <select
                                id="status"
                                name="status"
                                class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                            >
                                <option value="published" @selected(old('status', $lesson->status) === 'published')>Нашршуда (барои донишҷӯён дастрас)</option>
                                <option value="draft" @selected(old('status', $lesson->status) === 'draft')>Сиёҳнавис (пинҳон)</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Пайванди видео -->
                    <div x-show="type === 'video'">
                        <label for="video_url" class="block text-xs font-bold text-slate-700 mb-1">
                            Истинод ба видео (YouTube ё дигар):
                        </label>
                        <input
                            type="url"
                            id="video_url"
                            name="video_url"
                            value="{{ old('video_url', $lesson->video_url) }}"
                            class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                        >
                        @error('video_url')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Матни дарс -->
                    <div>
                        <label for="content_text" class="block text-xs font-bold text-slate-700 mb-1">
                            Матни назариявии дарс:
                        </label>
                        <textarea
                            id="content_text"
                            name="content_text"
                            rows="6"
                            class="w-full text-sm p-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                        >{{ old('content_text', $lesson->content_text) }}</textarea>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     МАВОДҲОИ МАВҶУДА ВА ИДОРАКУНИИ ОНҲО
                     ═══════════════════════════════════════════════════════════ -->
                @if($lesson->materials && $lesson->materials->count() > 0)
                    <div class="space-y-3 pt-4 border-t border-slate-200">
                        <h3 class="text-sm font-bold text-[#1E293B] flex items-center justify-between">
                            <span>Маводҳои аллакай боршуда ({{ $lesson->materials->count() }})</span>
                            <span class="text-xs text-slate-400 font-normal">Барои нест кардан тугмаи партовро пахш намоед</span>
                        </h3>

                        <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl bg-slate-50/50 overflow-hidden">
                            @foreach($lesson->materials as $mat)
                                <div class="p-4 space-y-2.5 text-xs bg-white">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase shrink-0
                                                @if($mat->file_type === 'video') bg-blue-100 text-blue-800
                                                @elseif($mat->file_type === 'pdf') bg-rose-100 text-rose-800
                                                @elseif($mat->file_type === 'document') bg-indigo-100 text-indigo-800
                                                @elseif($mat->file_type === 'presentation') bg-orange-100 text-orange-800
                                                @elseif($mat->file_type === 'link') bg-teal-100 text-teal-800
                                                @else bg-emerald-100 text-emerald-800 @endif">
                                                {{ $mat->file_type }}
                                            </span>
                                            <a
                                                href="{{ $mat->is_link ? $mat->file_path : route('materials.download', $mat) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="font-bold text-slate-800 hover:text-[#00875A] truncate"
                                            >
                                                {{ $mat->title }} ↗
                                            </a>
                                            <span class="text-[11px] text-slate-400">
                                                @if($mat->is_link)
                                                    Пайванди беруна
                                                @else
                                                    {{ $mat->human_file_size }} • {{ basename($mat->file_path) }}
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <button
                                                type="submit"
                                                form="update-mat-{{ $mat->id }}"
                                                class="px-2.5 py-1 bg-[#ECFDF5] hover:bg-[#D1FAE5] text-[#00875A] font-semibold rounded-lg border border-[#A7F3D0] transition"
                                            >
                                                Захира
                                            </button>
                                            <button
                                                type="submit"
                                                form="delete-mat-{{ $mat->id }}"
                                                class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 transition"
                                                title="Нест кардани ин мавод"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-2">
                                        <div class="sm:col-span-2">
                                            <input
                                                type="text"
                                                name="title"
                                                form="update-mat-{{ $mat->id }}"
                                                value="{{ $mat->title }}"
                                                required
                                                placeholder="Номи мавод"
                                                class="w-full text-xs p-2 border border-slate-200 rounded-lg focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                            >
                                        </div>
                                        <div class="sm:col-span-3">
                                            <input
                                                type="text"
                                                name="description"
                                                form="update-mat-{{ $mat->id }}"
                                                value="{{ $mat->description }}"
                                                placeholder="Тавсифи кӯтоҳи мавод (ихтиёрӣ)"
                                                class="w-full text-xs p-2 border border-slate-200 rounded-lg focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                            >
                                        </div>
                                        <div>
                                            <input
                                                type="number"
                                                name="order_index"
                                                form="update-mat-{{ $mat->id }}"
                                                value="{{ $mat->order_index }}"
                                                min="1"
                                                required
                                                title="Тартиби мавод"
                                                class="w-full text-xs p-2 border border-slate-200 rounded-lg focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                            >
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- БОРГУЗОРИИ МАВОДҲОИ НАВ -->
                <div class="space-y-4 pt-4 border-t border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-sm font-bold text-[#1E293B] flex items-center gap-2">
                                <span class="flex h-5 w-5 items-center justify-center rounded-md bg-[#00875A] text-white text-xs">+</span>
                                Иловаи маводҳои нави таълимӣ
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Видео (MP4/WebM), ҳуҷҷатҳо (PDF/Word), презентатсия (PowerPoint) ё тасвирҳоро интихоб кунед.</p>
                        </div>
                    </div>

                    <!-- Майдони интихоби файлҳо -->
                    <div class="border-2 border-dashed border-slate-300 hover:border-[#00875A] rounded-2xl p-6 text-center bg-slate-50/50 hover:bg-[#ECFDF5]/30 transition-all cursor-pointer relative">
                        <input
                            type="file"
                            name="materials[]"
                            id="materials_input"
                            multiple
                            accept=".mp4,.webm,.pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.webp"
                            @change="handleFileSelect($event)"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        >
                        <div class="space-y-2 pointer-events-none">
                            <div class="mx-auto w-12 h-12 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#00875A]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="text-sm font-semibold text-slate-700">
                                Барои иловаи файлҳои нав пахш кунед ё ба ин ҷо кашед
                            </div>
                        </div>
                    </div>

                    <!-- Пешнамоиши файлҳои нав -->
                    <template x-if="files.length > 0">
                        <div class="space-y-2">
                            <div class="text-xs font-bold text-slate-700 flex items-center justify-between">
                                <span>Файлҳои нави интихобшуда (<span x-text="files.length"></span>):</span>
                                <button type="button" @click="clearFiles()" class="text-rose-500 hover:underline text-[11px]">Тоза кардан</button>
                            </div>

                            <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl bg-white overflow-hidden">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="p-3 flex items-center justify-between gap-3 text-xs">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase shrink-0" :class="getBadgeClass(file.ext)">
                                                <span x-text="file.ext"></span>
                                            </span>
                                            <div class="min-w-0 flex-1 space-y-1.5">
                                                <input
                                                    type="text"
                                                    :name="'material_titles[' + index + ']'"
                                                    x-model="file.title"
                                                    placeholder="Номи мавод..."
                                                    class="w-full text-xs font-semibold p-1.5 border border-slate-200 rounded-lg focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                                >
                                                <input
                                                    type="text"
                                                    :name="'material_descriptions[' + index + ']'"
                                                    x-model="file.description"
                                                    placeholder="Тавсифи кӯтоҳи мавод (ихтиёрӣ)..."
                                                    class="w-full text-[11px] p-1.5 border border-slate-200 rounded-lg focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                                >
                                                <span class="text-[11px] text-slate-400 block" x-text="formatSize(file.size)"></span>
                                            </div>
                                        </div>

                                        <button
                                            type="button"
                                            @click="removeFile(index)"
                                            class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 transition"
                                            title="Хориҷ кардан"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Пайвандҳои беруна ва видеои иловагӣ -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <h4 class="text-xs font-bold text-[#1E293B]">Пайвандҳои беруна ва видеоҳои иловагӣ (YouTube / Vimeo / Мақолаҳо)</h4>
                                <p class="text-[11px] text-slate-400">Метавонед барои дарс манбаъҳои иловагии онлайн илова кунед.</p>
                            </div>
                            <button
                                type="button"
                                @click="addLink()"
                                class="px-3 py-1.5 bg-[#ECFDF5] hover:bg-[#D1FAE5] text-[#00875A] text-xs font-semibold rounded-lg border border-[#A7F3D0] transition shrink-0"
                            >
                                + Иловаи пайванд
                            </button>
                        </div>

                        <template x-if="links.length > 0">
                            <div class="space-y-3">
                                <template x-for="(lnk, idx) in links" :key="idx">
                                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 space-y-2.5">
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5">
                                            <div>
                                                <select name="link_types[]" x-model="lnk.type" class="w-full text-xs p-2 border border-slate-200 rounded-lg bg-white focus:ring-1 focus:ring-[#00875A] focus:outline-none">
                                                    <option value="link">Пайванд / Мақола</option>
                                                    <option value="video">Видео (YouTube/Vimeo)</option>
                                                </select>
                                            </div>
                                            <div class="sm:col-span-3 flex items-center gap-2">
                                                <input
                                                    type="text"
                                                    name="link_titles[]"
                                                    x-model="lnk.title"
                                                    placeholder="Унвони пайванд ё видео..."
                                                    class="w-full text-xs p-2 border border-slate-200 rounded-lg bg-white focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                                >
                                                <button
                                                    type="button"
                                                    @click="removeLink(idx)"
                                                    class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 transition shrink-0"
                                                    title="Хориҷ кардан"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                            <input
                                                type="url"
                                                name="link_urls[]"
                                                x-model="lnk.url"
                                                placeholder="https://..."
                                                class="w-full text-xs p-2 border border-slate-200 rounded-lg bg-white focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                            >
                                            <input
                                                type="text"
                                                name="link_descriptions[]"
                                                x-model="lnk.description"
                                                placeholder="Тавсифи кӯтоҳ (ихтиёрӣ)..."
                                                class="w-full text-xs p-2 border border-slate-200 rounded-lg bg-white focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                            >
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('author.courses.show', $lesson->module->course) }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Захираи тағйирот ва маводҳо
                    </button>
                </div>
            </form>

            <!-- Формаҳои алоҳида барои таҳрир ва нест кардани маводҳои мавҷуда -->
            @if($lesson->materials)
                @foreach($lesson->materials as $mat)
                    <form id="update-mat-{{ $mat->id }}" method="POST" action="{{ route('author.materials.update', $mat) }}" class="hidden">
                        @csrf
                        @method('PUT')
                    </form>
                    <form id="delete-mat-{{ $mat->id }}" method="POST" action="{{ route('author.materials.destroy', $mat) }}" onsubmit="return confirm('Оё шумо мутмаин ҳастед, ки ин маводро нест кардан мехоҳед?');" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        function materialsUploader() {
            return {
                type: '{{ old('content_type', $lesson->content_type) }}',
                files: [],
                links: [],
                addLink() {
                    this.links.push({ type: 'link', title: '', url: '', description: '' });
                },
                removeLink(index) {
                    this.links.splice(index, 1);
                },
                syncInputFiles() {
                    const input = document.getElementById('materials_input');
                    if (!input || typeof DataTransfer === 'undefined') return;
                    const dt = new DataTransfer();
                    this.files.forEach(item => {
                        if (item.file) dt.items.add(item.file);
                    });
                    input.files = dt.files;
                },
                handleFileSelect(event) {
                    const selected = Array.from(event.target.files);
                    selected.forEach(f => {
                        const ext = f.name.split('.').pop().toLowerCase();
                        const title = f.name.substring(0, f.name.lastIndexOf('.')) || f.name;
                        this.files.push({
                            file: f,
                            name: f.name,
                            size: f.size,
                            ext: ext,
                            title: title,
                            description: ''
                        });
                    });
                    this.syncInputFiles();
                },
                removeFile(index) {
                    this.files.splice(index, 1);
                    this.syncInputFiles();
                },
                clearFiles() {
                    this.files = [];
                    const input = document.getElementById('materials_input');
                    if (input) input.value = '';
                },
                formatSize(bytes) {
                    if (!bytes) return '';
                    const units = ['B', 'KB', 'MB', 'GB'];
                    let i = 0;
                    while (bytes > 1024 && i < units.length - 1) {
                        bytes /= 1024;
                        i++;
                    }
                    return Math.round(bytes * 10) / 10 + ' ' + units[i];
                },
                getBadgeClass(ext) {
                    if (['mp4', 'webm'].includes(ext)) return 'bg-blue-100 text-blue-800';
                    if (['pdf'].includes(ext)) return 'bg-rose-100 text-rose-800';
                    if (['doc', 'docx'].includes(ext)) return 'bg-indigo-100 text-indigo-800';
                    if (['ppt', 'pptx'].includes(ext)) return 'bg-orange-100 text-orange-800';
                    if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) return 'bg-emerald-100 text-emerald-800';
                    return 'bg-slate-100 text-slate-800';
                }
            };
        }
    </script>
</x-app-layout>
