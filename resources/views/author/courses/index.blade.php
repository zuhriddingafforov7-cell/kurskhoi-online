<x-app-layout title="Курсҳои ман — Муаллиф" :withSidebar="true">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1E293B]">Курсҳои ман</h1>
            <p class="mt-1 text-slate-500">Идоракунӣ, таҳрир ва сохтани курсҳои нави таълимӣ</p>
        </div>
        <a href="{{ route('author.courses.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white font-semibold rounded-xl shadow-sm transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Курси нав
        </a>
    </div>

    @if($courses->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm text-slate-600 min-w-[640px]">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-400 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Номи курс</th>
                            <th class="px-6 py-4">Категория</th>
                            <th class="px-6 py-4">Талабагон</th>
                            <th class="px-6 py-4">Ҳолат</th>
                            <th class="px-6 py-4">Санаи эҷод</th>
                            <th class="px-6 py-4 text-right">Амалҳо</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($courses as $course)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-[#1E293B] text-base">{{ $course->title }}</div>
                                    <div class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ Str::limit($course->description, 60) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $course->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-[#1E293B]">{{ $course->enrollments_count }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($course->status === 'published')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span> Нашршуда
                                        </span>
                                    @elseif($course->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Дар баррасӣ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Радшуда
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    {{ $course->created_at->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('author.courses.show', $course) }}" class="p-2 text-[#00875A] hover:bg-[#ECFDF5] rounded-lg transition" title="Кушодан ва идоракунии модулҳо">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('author.courses.edit', $course) }}" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Таҳрир">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('author.courses.destroy', $course) }}" onsubmit="return confirm('Шумо мутмаин ҳастед, ки ин курсро нест кардан мехоҳед?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Нест кардан">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $courses->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-slate-300">
            <div class="w-16 h-16 rounded-2xl bg-[#ECFDF5] text-[#00875A] flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#1E293B]">Шумо то ҳол ягон курс насохтаед</h3>
            <p class="text-slate-500 text-sm mt-1 mb-6">Аввалин курси худро оғоз намоед ва дониши худро ба дигарон тақсим кунед.</p>
            <a href="{{ route('author.courses.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#00875A] hover:bg-[#064E3B] text-white font-semibold rounded-xl transition shadow">
                Сохтани аввалин курс
            </a>
        </div>
    @endif
    </div>
</x-app-layout>
