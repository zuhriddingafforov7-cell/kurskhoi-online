<x-app-layout title="Идоракунии категорияҳо — Biyomuz Academy" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳа -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200 mb-2">
                    Панели маъмур
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Категорияҳои таълимӣ</h1>
                <p class="text-xs text-slate-500 mt-1">Танзим ва илова намудани бахшҳои омӯзишӣ дар платформа.</p>
            </div>

            <div>
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    + Иловаи категория
                </a>
            </div>
        </div>

        <!-- Ҷадвал -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                            <th class="p-4 px-6">ID</th>
                            <th class="p-4 px-6">Номи категория</th>
                            <th class="p-4 px-6">Slug (URL)</th>
                            <th class="p-4 px-6">Шумораи курсҳо</th>
                            <th class="p-4 px-6 text-right">Амалҳо</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-4 px-6 font-mono text-slate-400">#{{ $category->id }}</td>
                                <td class="p-4 px-6 font-bold text-sm text-[#1E293B]">
                                    {{ $category->title ?? $category->name }}
                                    @if($category->description)
                                        <div class="text-[11px] text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $category->description }}</div>
                                    @endif
                                </td>
                                <td class="p-4 px-6 font-mono text-slate-500">{{ $category->slug ?? '-' }}</td>
                                <td class="p-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#ECFDF5] text-[#00875A]">
                                        {{ $category->courses_count ?? 0 }} курс
                                    </span>
                                </td>
                                <td class="p-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="px-2.5 py-1 text-slate-600 hover:text-[#00875A] font-medium transition">
                                            Таҳрир
                                        </a>

                                        @if(($category->courses_count ?? 0) === 0)
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Ин категорияро нест мекунед?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 text-rose-500 hover:text-rose-700 font-medium transition">
                                                    Нест кардан
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[11px] text-slate-300 italic" title="Категория дорои курсҳо аст ва нест карда намешавад">Баста</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400">
                                    Ягон категория вуҷуд надорад.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
