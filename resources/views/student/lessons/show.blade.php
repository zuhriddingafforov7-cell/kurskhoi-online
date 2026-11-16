<x-app-layout :title="$lesson->title . ' — ' . $course->title">
    @php
        $isPreview = $isPreview ?? false;
        $completedLessonIds = $completedLessonIds ?? [];
    @endphp

    <div x-data="{
            lightboxOpen: false,
            lightboxImg: '',
            lightboxTitle: '',
            openLightbox(url, title) {
                this.lightboxImg = url;
                this.lightboxTitle = title;
                this.lightboxOpen = true;
            }
         }"
         @keydown.escape.window="lightboxOpen = false"
         class="space-y-6">

        @if($isPreview)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2 font-semibold">
                    <span>👁️ Ҳолати пешнамоиши муаллиф (Preview) — ҳамаи маводҳо ва дарсҳо барои шумо кушодаанд.</span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('author.lessons.edit', $lesson) }}" class="px-3 py-1.5 bg-white border border-amber-300 rounded-xl font-semibold hover:bg-amber-100 transition">Таҳрири дарс</a>
                    <a href="{{ route('author.courses.show', $course) }}" class="px-3 py-1.5 bg-[#00875A] text-white rounded-xl font-semibold hover:bg-[#064E3B] transition">Ба саҳифаи курс</a>
                </div>
            </div>
        @endif

        {{-- Навигатсияи болоӣ (Breadcrumb) --}}
        <div class="bg-white p-4 px-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center text-xs text-slate-500 gap-2 truncate">
                @if($isPreview)
                    <a href="{{ route('author.courses.index') }}" class="hover:text-[#00875A] shrink-0 font-medium">Курсҳои ман</a>
                    <span>/</span>
                    <a href="{{ route('author.courses.show', $course) }}" class="hover:text-[#00875A] truncate font-medium">{{ $course->title }}</a>
                @else
                    <a href="{{ route('student.my-courses') }}" class="hover:text-[#00875A] shrink-0 font-medium">Курсҳои ман</a>
                    <span>/</span>
                    <a href="{{ route('student.courses.show', $course) }}" class="hover:text-[#00875A] truncate font-medium">{{ $course->title }}</a>
                @endif
                <span>/</span>
                <span class="text-slate-800 font-semibold truncate">{{ $lesson->title }}</span>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ $isPreview ? route('author.courses.show', $course) : route('student.courses.show', $course) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-[#ECFDF5] hover:text-[#00875A] hover:border-[#00875A]/40 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Ба саҳифаи курс</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Тарафи чап: Мазмуни асосии дарс (3 сутун) --}}
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0]">
                                    Модул: {{ $module->title }}
                                </span>
                                @if($lesson->duration_minutes)
                                    <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $lesson->duration_minutes }} дақиқа
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B] tracking-tight">
                                {{ $lesson->title }}
                            </h1>
                        </div>

                        <div>
                            @if($isPreview)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    Ҳолат: {{ $lesson->status === 'draft' ? 'Лоиҳа (Draft)' : 'Нашршуда' }}
                                </span>
                            @elseif($progress && $progress->is_completed)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Анҷом дода шуд
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    Дар ҳоли омӯзиш
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Видеои асосӣ (YouTube / Vimeo / Direct MP4) --}}
                    @if($lesson->video_url)
                        @php $embedUrl = $lesson->embed_video_url; @endphp
                        <div class="p-6 border-b border-slate-100 bg-black/95">
                            <div class="aspect-video w-full rounded-xl overflow-hidden shadow-inner flex items-center justify-center bg-slate-900">
                                @if(preg_match('/\.(mp4|webm)(\?.*)?$/i', $lesson->video_url))
                                    <video controls class="w-full h-full" preload="metadata">
                                        <source src="{{ $lesson->video_url }}">
                                    </video>
                                @else
                                    <iframe
                                        src="{{ $embedUrl }}"
                                        class="w-full h-full"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                    ></iframe>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Матни назариявии дарс --}}
                    <div class="p-6 sm:p-8 space-y-6">
                        @if($lesson->content_text)
                            <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-slate-700">
                                {!! nl2br(e($lesson->content_text)) !!}
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">Барои ин дарс маводи матнӣ сабт нашудааст.</p>
                        @endif

                        {{-- Файли аслии дарс (муҳофизатшуда бо route) --}}
                        @if($lesson->file_path)
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#ECFDF5] text-[#00875A]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800">Маводи асосии дарс</div>
                                        <div class="text-[11px] text-slate-400">Файли замимашуда барои омӯзиш</div>
                                    </div>
                                </div>
                                <a href="{{ route('lessons.attachment', $lesson) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-200 hover:bg-[#ECFDF5] hover:text-[#00875A] text-slate-700 text-xs font-semibold rounded-xl shadow-xs transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Зеркашӣ</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- МАВОДҲОИ ТАЪЛИМИИ ГУНОГУН (Видео, PDF, Тасвир, Word/PPTX, Пайвандҳо) --}}
                    @if($lesson->materials && $lesson->materials->count() > 0)
                        <div class="p-6 sm:p-8 bg-slate-50/70 border-t border-slate-200/80 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-[#1E293B] flex items-center gap-2">
                                        <svg class="w-5 h-5 text-[#00875A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        <span>Маводҳои таълимии дарс</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Ҳамаи видеоҳо, санадҳо, намоишҳо, тасвирҳо ва пайвандҳои таълимӣ</p>
                                </div>
                                <span class="self-start sm:self-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-xs">
                                    Ҳамагӣ: {{ $lesson->materials->count() }} мавод
                                </span>
                            </div>

                            {{-- 1. ВИДЕОҲО --}}
                            @php $videoMaterials = $lesson->materials->where('file_type', 'video'); @endphp
                            @if($videoMaterials->count() > 0)
                                <div class="space-y-4">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Видеодарсҳо ({{ $videoMaterials->count() }})</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($videoMaterials as $mat)
                                            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs space-y-3">
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="flex items-center gap-2.5">
                                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-rose-100 text-rose-700">Видео</span>
                                                        <h4 class="text-sm font-bold text-slate-800">{{ $mat->title }}</h4>
                                                    </div>
                                                    @if($mat->human_file_size)
                                                        <span class="text-xs text-slate-400 font-mono">{{ $mat->human_file_size }}</span>
                                                    @endif
                                                </div>

                                                @if(str_starts_with($mat->file_path, 'http'))
                                                    <div class="aspect-video w-full rounded-xl overflow-hidden shadow-inner bg-slate-900">
                                                        @if(preg_match('/\.(mp4|webm)(\?.*)?$/i', $mat->file_path))
                                                            <video controls class="w-full h-full" preload="metadata">
                                                                <source src="{{ $mat->file_path }}">
                                                            </video>
                                                        @else
                                                            <iframe src="{{ $mat->embed_url }}" class="w-full h-full" allowfullscreen></iframe>
                                                        @endif
                                                    </div>
                                                @else
                                                    <video controls class="w-full rounded-xl bg-black max-h-[480px] shadow-sm" preload="metadata">
                                                        <source src="{{ route('materials.stream', $mat) }}" type="{{ $mat->mime_type ?? 'video/mp4' }}">
                                                        Браузери шумо теги видеоро дастгирӣ намекунад.
                                                    </video>
                                                @endif

                                                @if($mat->description)
                                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $mat->description }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- 2. PDF (PREVIEW & DOWNLOAD) --}}
                            @php $pdfMaterials = $lesson->materials->where('file_type', 'pdf'); @endphp
                            @if($pdfMaterials->count() > 0)
                                <div class="space-y-4">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                        <span>Ҳуҷҷатҳои PDF ({{ $pdfMaterials->count() }})</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($pdfMaterials as $mat)
                                            <div x-data="{ showPreview: false }" class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-3">
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                    <div class="flex items-start gap-3">
                                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600 font-black text-xs border border-red-200">
                                                            PDF
                                                        </div>
                                                        <div>
                                                            <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $mat->title }}</h4>
                                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                                <span class="text-xs text-slate-400 font-mono">{{ $mat->human_file_size }}</span>
                                                                @if($mat->description)
                                                                    <span class="text-slate-300">•</span>
                                                                    <span class="text-xs text-slate-500">{{ $mat->description }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                                                        <button
                                                            @click="showPreview = !showPreview"
                                                            type="button"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-[#00875A] transition"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            <span x-text="showPreview ? 'Пӯшидани намоиш' : 'Дидан дар саҳифа'"></span>
                                                        </button>

                                                        <a
                                                            href="{{ route('materials.download', $mat) }}"
                                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-bold rounded-xl shadow-xs transition"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                            <span>Боргирӣ</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div x-show="showPreview" x-transition class="pt-3 border-t border-slate-100" style="display: none;">
                                                    <iframe
                                                        src="{{ route('materials.stream', $mat) }}#toolbar=1"
                                                        class="w-full h-[520px] rounded-xl border border-slate-200 bg-slate-50"
                                                    ></iframe>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- 3. ТАСВИРҲО --}}
                            @php $imageMaterials = $lesson->materials->where('file_type', 'image'); @endphp
                            @if($imageMaterials->count() > 0)
                                <div class="space-y-4">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Тасвирҳо ва диаграммаҳо ({{ $imageMaterials->count() }})</span>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                        @foreach($imageMaterials as $mat)
                                            <div
                                                @click="openLightbox('{{ route('materials.stream', $mat) }}', '{{ e($mat->title) }}')"
                                                class="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs cursor-pointer hover:border-[#00875A] hover:shadow-md transition-all duration-200"
                                            >
                                                <div class="aspect-video w-full bg-slate-100 overflow-hidden">
                                                    <img
                                                        src="{{ route('materials.stream', $mat) }}"
                                                        alt="{{ $mat->title }}"
                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                        loading="lazy"
                                                    />
                                                </div>
                                                <div class="p-2.5">
                                                    <div class="text-xs font-bold text-slate-800 truncate">{{ $mat->title }}</div>
                                                    <div class="text-[10px] text-slate-400 mt-0.5 flex items-center justify-between">
                                                        <span>Расм</span>
                                                        <span class="font-mono">{{ $mat->human_file_size }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- 4. WORD ВА POWERPOINT --}}
                            @php $docMaterials = $lesson->materials->whereIn('file_type', ['document', 'presentation']); @endphp
                            @if($docMaterials->count() > 0)
                                <div class="space-y-4">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        <span>Ҳуҷҷатҳои Word ва Презентатсияҳо ({{ $docMaterials->count() }})</span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($docMaterials as $mat)
                                            @php
                                                $isPpt = $mat->file_type === 'presentation';
                                                $badgeBg = $isPpt ? 'bg-orange-50 text-orange-600 border-orange-200' : 'bg-blue-50 text-blue-600 border-blue-200';
                                                $extLabel = strtoupper(pathinfo($mat->file_path, PATHINFO_EXTENSION) ?: ($isPpt ? 'PPTX' : 'DOCX'));
                                            @endphp
                                            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex items-center justify-between gap-3 hover:border-slate-300 transition">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl font-black text-xs border {{ $badgeBg }}">
                                                        {{ $extLabel }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <h4 class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $mat->title }}</h4>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <span class="text-[11px] text-slate-400 font-mono">{{ $mat->human_file_size }}</span>
                                                            @if($mat->description)
                                                                <span class="text-slate-300">•</span>
                                                                <span class="text-[11px] text-slate-500 truncate">{{ $mat->description }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <a
                                                    href="{{ route('materials.download', $mat) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-[#ECFDF5] hover:text-[#00875A] text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition shrink-0"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    <span>Боргирӣ</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- 5. ПАЙВАНДҲО ВА МАНБАЪҲОИ БЕРУНА --}}
                            @php $linkMaterials = $lesson->materials->where('file_type', 'link'); @endphp
                            @if($linkMaterials->count() > 0)
                                <div class="space-y-4">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                        <span>Пайвандҳо ва манбаъҳои таълимӣ ({{ $linkMaterials->count() }})</span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($linkMaterials as $mat)
                                            <a href="{{ $mat->file_path }}" target="_blank" rel="noopener noreferrer" class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex items-center justify-between gap-3 hover:border-[#00875A] hover:bg-[#ECFDF5]/30 transition">
                                                <div class="min-w-0">
                                                    <h4 class="text-xs sm:text-sm font-bold text-[#1E293B] truncate">{{ $mat->title }}</h4>
                                                    @if($mat->description)
                                                        <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $mat->description }}</p>
                                                    @endif
                                                    <span class="text-[10px] text-[#00875A] truncate block mt-1">{{ $mat->file_path }}</span>
                                                </div>
                                                <svg class="w-4 h-4 text-[#00875A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Бахши амалҳо ва анҷоми дарс --}}
                    <div class="p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        @if(!$isPreview)
                            <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}" class="w-full sm:w-auto">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 {{ $progress && $progress->is_completed ? 'bg-slate-200 text-slate-700 hover:bg-slate-300' : 'bg-[#00875A] hover:bg-[#064E3B] text-white shadow-sm shadow-[#00875A]/20' }} text-sm font-bold rounded-xl transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $progress && $progress->is_completed ? 'Аз нав анҷом додан' : 'Дарсро анҷом додам' }}</span>
                                </button>
                            </form>

                            @if($lesson->assignment)
                                <a
                                    href="{{ route('student.assignments.show', $lesson->assignment) }}"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-sm transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Супориши амалии дарс</span>
                                </a>
                            @endif
                        @else
                            <span class="text-xs text-slate-500">Дар ҳолати пешнамоиш тугмаи анҷоми дарс барои донишҷӯён фаъол мешавад.</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Тарафи рост: Мундариҷаи курс (Бе N+1 Query!) --}}
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs sticky top-24">
                    <h3 class="text-sm font-bold text-[#1E293B] mb-3 pb-2 border-b border-slate-100 flex items-center justify-between">
                        <span>Мундариҷаи курс</span>
                        <span class="text-[11px] font-semibold text-[#00875A]">{{ $course->modules->count() }} модул</span>
                    </h3>

                    @php
                        $prevModulesCompleted = true;
                    @endphp
                    <div class="space-y-3 max-h-[calc(100vh-12rem)] overflow-y-auto pr-1">
                        @foreach($course->modules as $m)
                            <div class="space-y-1.5">
                                <div class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#00875A]"></span>
                                    <span class="truncate">{{ $m->title }}</span>
                                </div>

                                <div class="space-y-1 pl-3 border-l-2 border-slate-100">
                                    @php
                                        $prevLessonsInModuleCompleted = true;
                                    @endphp
                                    @foreach($m->lessons as $l)
                                        @php
                                            $isCurrent = $l->id === $lesson->id;
                                            $isDone = in_array($l->id, $completedLessonIds, true);
                                            $isUnlocked = $isPreview || ($prevModulesCompleted && $prevLessonsInModuleCompleted);
                                            $targetRoute = $isPreview ? route('author.lessons.preview', $l) : route('student.lessons.show', $l);
                                        @endphp
                                        @if($isUnlocked)
                                            <a
                                                href="{{ $targetRoute }}"
                                                class="block px-2.5 py-1.5 rounded-lg text-xs transition {{ $isCurrent ? 'bg-[#ECFDF5] text-[#00875A] font-bold border border-[#A7F3D0]' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                                            >
                                                <div class="flex items-center justify-between gap-1">
                                                    <span class="truncate">{{ $l->title }}</span>
                                                    @if($isDone)
                                                        <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                                    @endif
                                                </div>
                                            </a>
                                        @else
                                            <span class="block px-2.5 py-1.5 rounded-lg text-xs text-slate-400 bg-slate-50/70 cursor-not-allowed">
                                                <div class="flex items-center justify-between gap-1">
                                                    <span class="truncate">{{ $l->title }}</span>
                                                    <span class="shrink-0">🔒</span>
                                                </div>
                                            </span>
                                        @endif
                                        @php
                                            if (!$isDone) {
                                                $prevLessonsInModuleCompleted = false;
                                            }
                                        @endphp
                                    @endforeach
                                    @php
                                        if (!$prevLessonsInModuleCompleted) {
                                            $prevModulesCompleted = false;
                                        }
                                    @endphp
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- LIGHTBOX MODAL БАРОИ ТАСВИРҲО --}}
        <div
            x-show="lightboxOpen"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/85 backdrop-blur-sm"
            style="display: none;"
            @click.self="lightboxOpen = false"
        >
            <div class="relative max-w-5xl w-full bg-white rounded-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
                <div class="p-4 bg-slate-900 text-white flex items-center justify-between gap-3">
                    <div class="text-sm font-bold truncate" x-text="lightboxTitle"></div>
                    <button @click="lightboxOpen = false" type="button" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-4 flex items-center justify-center bg-slate-950 overflow-auto flex-1">
                    <img :src="lightboxImg" :alt="lightboxTitle" class="max-h-[75vh] w-auto object-contain rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
