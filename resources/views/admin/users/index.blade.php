<x-app-layout title="Идоракунии корбарон — Biyomuz Academy" :withSidebar="true">
    <div class="space-y-6">
        <!-- Сарлавҳа -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200 mb-2">
                    Панели маъмур
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Идоракунии корбарони платформа</h1>
                <p class="text-xs text-slate-500 mt-1">Рӯйхати ҳамаи корбарон ва таъин намудани нақшҳо (Донишҷӯ, Муаллиф, Маъмур).</p>
            </div>

            <div class="text-xs font-semibold text-slate-500">
                Ҳамагӣ: <strong class="text-[#00875A]">{{ $users->total() }}</strong> корбар
            </div>
        </div>

        <!-- Ҷадвали корбарон -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-bold">
                            <th class="px-4 py-3.5">ID</th>
                            <th class="px-4 py-3.5">Ному насаб</th>
                            <th class="px-4 py-3.5">Почтаи электронӣ</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Нақш</th>
                            <th class="px-4 py-3.5 whitespace-nowrap">Санаи сабтином</th>
                            <th class="px-4 py-3.5 text-right whitespace-nowrap">Амалҳо</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-4 font-mono text-xs text-slate-400">#{{ $u->id }}</td>
                                <td class="px-4 py-4 font-bold text-sm text-[#1E293B]">
                                    {{ $u->name }}
                                </td>
                                <td class="px-4 py-4 font-mono text-xs sm:text-sm text-slate-600">
                                    {{ $u->email }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                        @if($u->role === 'admin') bg-rose-100 text-rose-700 border border-rose-200
                                        @elseif($u->role === 'author') bg-amber-100 text-amber-700 border border-amber-200
                                        @else bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] @endif">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs sm:text-sm text-slate-500 whitespace-nowrap">
                                    {{ $u->created_at ? $u->created_at->format('d.m.Y H:i') : '-' }}
                                </td>
                                <td class="px-4 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $u) }}" class="px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-[#00875A] hover:bg-slate-100 rounded-xl transition whitespace-nowrap">
                                            Профил
                                        </a>
                                        <a href="{{ route('admin.users.edit', $u) }}" class="px-3 py-1.5 text-xs font-semibold text-[#00875A] bg-[#ECFDF5] hover:bg-[#D1FAE5] rounded-xl transition whitespace-nowrap">
                                            Ивази нақш
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
