@props(['course', 'href' => null])

@php
    $link = $href ?? route('courses.show', $course);
    $levelColors = [
        'beginner'     => 'bg-emerald-500/90 text-white',
        'intermediate' => 'bg-sky-500/90 text-white',
        'advanced'     => 'bg-amber-500/90 text-white',
    ];
    $levelBadgeClass = $levelColors[$course->level] ?? 'bg-emerald-500/90 text-white';
@endphp

<a href="{{ $link }}" class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-[#00875A]/8 hover:border-[#A7F3D0] transition-all duration-300 flex flex-col">
    <div class="h-44 bg-gradient-to-br from-[#064E3B] via-[#00875A] to-[#10B981] flex items-center justify-center relative overflow-hidden shrink-0">
        @if($course->cover_image)
            <img src="{{ route('courses.cover', $course) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-black/10"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            <span class="text-5xl font-black text-white/25 group-hover:scale-110 transition-transform duration-300 select-none">{{ mb_substr($course->title, 0, 1) }}</span>
        @endif

        @if($course->category)
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 text-[#064E3B] shadow-xs">
                {{ $course->category->name }}
            </span>
        @endif

        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $levelBadgeClass }} shadow-xs">
            {{ $course->level_label }}
        </span>
    </div>

    <div class="p-5 sm:p-6 flex flex-col flex-1">
        <h3 class="text-base sm:text-lg font-bold text-[#1E293B] group-hover:text-[#00875A] transition-colors duration-200 line-clamp-2 leading-snug">
            {{ $course->title }}
        </h3>
        <p class="mt-2 text-xs sm:text-sm text-slate-500 line-clamp-2 flex-1 leading-relaxed">
            {{ Str::limit($course->description, 100) }}
        </p>

        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#00875A] font-bold text-xs shrink-0">
                    {{ mb_substr($course->author->name ?? 'М', 0, 1) }}
                </div>
                <span class="font-medium text-slate-700 truncate">{{ $course->author->name ?? 'Муаллиф' }}</span>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <span class="text-[#00875A] font-semibold">{{ $course->enrollments_count ?? 0 }} {{ __('донишҷӯ') }}</span>
                @if(isset($course->modules_count))
                    <span class="text-slate-400">• {{ $course->modules_count }} мод.</span>
                @endif
            </div>
        </div>
    </div>
</a>
