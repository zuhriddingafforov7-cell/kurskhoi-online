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
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                            <th class="p-4 px-6">ID</th>
                            <th class="p-4 px-6">Ному насаб</th>
                            <th class="p-4 px-6">Почтаи электронӣ</th>
                            <th class="p-4 px-6">Нақш</th>
                            <th class="p-4 px-6">Санаи сабтином</th>
                            <th class="p-4 px-6 text-right">Амалҳо</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-4 px-6 font-mono text-slate-400">#{{ $u->id }}</td>
                                <td class="p-4 px-6 font-bold text-sm text-[#1E293B]">
                                    {{ $u->name }}
                                </td>
                                <td class="p-4 px-6 font-mono text-slate-600">
                                    {{ $u->email }}
                                </td>
                                <td class="p-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold
                                        @if($u->role === 'admin') bg-rose-100 text-rose-700 border border-rose-200
                                        @elseif($u->role === 'author') bg-amber-100 text-amber-700 border border-amber-200
                                        @else bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] @endif">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td class="p-4 px-6 text-slate-500">
                                    {{ $u->created_at ? $u->created_at->format('d.m.Y H:i') : '-' }}
                                </td>
                                <td class="p-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $u) }}" class="px-2.5 py-1 text-slate-600 hover:text-[#00875A] font-medium transition">
                                            Профил
                                        </a>
                                        <a href="{{ route('admin.users.edit', $u) }}" class="px-2.5 py-1 text-[#00875A] hover:bg-[#ECFDF5] rounded-lg font-semibold transition">
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
