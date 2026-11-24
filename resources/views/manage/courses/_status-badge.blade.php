@php
    $badgeMap = [
        'published' => ['bg-[#ECFDF5] text-[#00875A] border-[#A7F3D0]', 'Нашршуда'],
        'pending'   => ['bg-amber-50 text-amber-700 border-amber-200', 'Дар баррасӣ'],
        'draft'     => ['bg-slate-100 text-slate-700 border-slate-200', 'Лоиҳа'],
        'rejected'  => ['bg-rose-50 text-rose-700 border-rose-200', 'Радшуда'],
    ];
    [$badgeClass, $badgeLabel] = $badgeMap[$status] ?? ['bg-slate-100 text-slate-700 border-slate-200', 'Номаълум'];
@endphp
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
    {{ $badgeLabel }}
</span>
