<x-app-layout title="Модератсияи курсҳо — Biyomuz Academy" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳа ва Филтрҳо -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200 mb-2">
                    Панели маъмур
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Модератсия ва идоракунии курсҳо</h1>
                <p class="text-xs text-slate-500 mt-1">Санҷиши сифати маводҳо, тасдиқ ва рад намудани курсҳои пешниҳодшуда.</p>
            </div>

            <!-- Филтри ҳолат -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.courses.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-medium {{ !request('status') ? 'bg-[#00875A] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Ҳама
                </a>
                <a href="{{ route('admin.courses.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-xl text-xs font-medium {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Мунтазир (Pending)
                </a>
                <a href="{{ route('admin.courses.index', ['status' => 'published']) }}" class="px-3 py-1.5 rounded-xl text-xs font-medium {{ request('status') === 'published' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Нашршуда (Published)
                </a>
                <a href="{{ route('admin.courses.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-xl text-xs font-medium {{ request('status') === 'rejected' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Радшуда (Rejected)
                </a>
            </div>
        </div>

        <!-- Ҷадвали курсҳо -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                            <th class="p-4 px-6">Номи курс</th>
                            <th class="p-4 px-6">Муаллиф</th>
                            <th class="p-4 px-6">Категория</th>
                            <th class="p-4 px-6">Донишҷӯён</th>
                            <th class="p-4 px-6">Ҳолат</th>
                            <th class="p-4 px-6 text-right">Амалҳои маъмур</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($courses as $course)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-4 px-6">
                                    <div class="font-bold text-sm text-[#1E293B]">{{ $course->title }}</div>
                                    <div class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $course->description }}</div>
                                </td>
                                <td class="p-4 px-6 text-slate-700 font-medium">
                                    {{ $course->author->name ?? 'Номаълум' }}
                                    <span class="block text-[11px] text-slate-400">{{ $course->author->email ?? '' }}</span>
                                </td>
                                <td class="p-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $course->category->name ?? $course->category->title ?? 'Категория' }}
                                    </span>
                                </td>
                                <td class="p-4 px-6 font-semibold text-slate-700">
                                    {{ $course->enrollments_count ?? 0 }}
                                </td>
                                <td class="p-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold
                                        @if($course->status === 'published') bg-emerald-100 text-emerald-800
                                        @elseif($course->status === 'pending') bg-amber-100 text-amber-800
                                        @elseif($course->status === 'rejected') bg-rose-100 text-rose-800
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td class="p-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('courses.show', $course) }}" target="_blank" class="px-2.5 py-1 text-slate-600 hover:text-[#00875A] font-medium transition" title="Пешнамоиш">
                                            Дидан ↗
                                        </a>

                                        @if($course->status !== 'published')
                                            <form method="POST" action="{{ route('admin.courses.approve', $course) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold shadow-xs transition">
                                                    Тасдиқ
                                                </button>
                                            </form>
                                        @endif

                                        @if($course->status !== 'rejected')
                                            <form method="POST" action="{{ route('admin.courses.reject', $course) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1 bg-rose-500 hover:bg-rose-600 text-white rounded-lg font-semibold shadow-xs transition">
                                                    Рад кардан
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">
                                    Дар ин категория ягон курс ёфт нашуд.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
