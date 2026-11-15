<x-app-layout :title="'Иловаи дарс ба «' . $module->title . '» — Biyomuz Academy'">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-2">
                    Модул: {{ $module->title }}
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Иловаи дарси нав</h1>
                <p class="text-xs text-slate-500 mt-1">Маводи таълимӣ, видео, ҳуҷҷатҳо ва презентатсияҳоро илова намоед.</p>
            </div>
            <a href="{{ route('author.courses.show', $module->course) }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                ← Бозгашт
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('author.modules.lessons.store', $module) }}" enctype="multipart/form-data" class="space-y-6" x-data="materialsUploader()">
                @csrf

                <!-- Маълумоти асосии дарс -->
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
                            value="{{ old('title') }}"
                            required
                            placeholder="Масалан: Тағйирёбандаҳо ва намудҳои додаҳо"
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
                                value="{{ old('order_index', $module->lessons()->count() + 1) }}"
                                min="1"
                                required
                                class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                            >
                        </div>
                    </div>

                    <!-- Пайванди видеои беруна -->
                    <div x-show="type === 'video'">
                        <label for="video_url" class="block text-xs font-bold text-slate-700 mb-1">
                            Истинод ба видео (YouTube, Vimeo ё пайванди мустақим):
                        </label>
                        <input
                            type="url"
                            id="video_url"
                            name="video_url"
                            value="{{ old('video_url') }}"
                            placeholder="https://www.youtube.com/watch?v=..."
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
                            placeholder="Матни дарс, тавзеҳот, намунаҳои код ва дастурҳо..."
                            class="w-full text-sm p-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                        >{{ old('content_text') }}</textarea>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════════
                     БАХШИ МАВОДҲОИ ТАЪЛИМӢ (Боргузории бисёрфайла)
                     Дастгирӣ: MP4, WebM, PDF, DOC, DOCX, PPT, PPTX, JPG, PNG, WEBP
                     ═══════════════════════════════════════════════════════════ -->
                <div class="space-y-4 pt-4 border-t border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-sm font-bold text-[#1E293B] flex items-center gap-2">
                                <span class="flex h-5 w-5 items-center justify-center rounded-md bg-[#00875A] text-white text-xs">📎</span>
                                Маводҳои таълимии дарс
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Як ё якчанд файлро якбора интихоб ва бор кунед.</p>
                        </div>

                        <!-- Нишонаҳои форматҳои дастгиришаванда -->
                        <div class="flex flex-wrap items-center gap-1.5 text-[10px] font-bold">
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">MP4 / WebM</span>
                            <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">PDF</span>
                            <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">DOC / DOCX</span>
                            <span class="px-2 py-0.5 rounded bg-orange-50 text-orange-700 border border-orange-200">PPT / PPTX</span>
                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">JPG / PNG / WEBP</span>
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
                                Барои интихоби файлҳо пахш кунед ё ба ин ҷо кашед
                            </div>
                            <p class="text-xs text-slate-400">
                                Видеоҳо (то 100MB), ҳуҷҷатҳои PDF, Word, PowerPoint ва тасвирҳо (то 25MB)
                            </p>
                        </div>
                    </div>

                    <!-- Рӯйхати пешнамоиши файлҳои интихобшуда пеш аз фиристодан -->
                    <template x-if="files.length > 0">
                        <div class="space-y-2">
                            <div class="text-xs font-bold text-slate-700 flex items-center justify-between">
                                <span>Файлҳои интихобшуда (<span x-text="files.length"></span>):</span>
                                <button type="button" @click="clearFiles()" class="text-rose-500 hover:underline text-[11px]">Ҳамаро тоза кун</button>
                            </div>

                            <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl bg-white overflow-hidden">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="p-3 flex items-center justify-between gap-3 text-xs">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase shrink-0" :class="getBadgeClass(file.ext)">
                                                <span x-text="file.ext"></span>
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <input
                                                    type="text"
                                                    :name="'material_titles[' + index + ']'"
                                                    x-model="file.title"
                                                    placeholder="Номи мавод..."
                                                    class="w-full text-xs font-semibold p-1.5 border border-slate-200 rounded-lg focus:ring-1 focus:ring-[#00875A] focus:outline-none"
                                                >
                                                <span class="text-[11px] text-slate-400 block mt-0.5" x-text="formatSize(file.size)"></span>
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
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('author.courses.show', $module->course) }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Иловаи дарс ва маводҳо →
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function materialsUploader() {
            return {
                type: '{{ old('content_type', 'text') }}',
                files: [],
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
                            title: title
                        });
                    });
                },
                removeFile(index) {
                    this.files.splice(index, 1);
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
