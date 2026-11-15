<x-app-layout :title="'Профили ' . $user->name . ' — Biyomuz Academy'">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#00875A] text-white font-extrabold text-2xl shadow-sm">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-[#1E293B]">{{ $user->name }}</h1>
                    <p class="text-xs text-slate-500 font-mono">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    Ивази нақш
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                    ← Рӯйхат
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Маълумоти асосӣ -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                    Маълумоти ҳисоб
                </h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block">ID:</span>
                        <span class="font-bold font-mono">#{{ $user->id }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Нақши системавӣ:</span>
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold
                            @if($user->role === 'admin') bg-rose-100 text-rose-700
                            @elseif($user->role === 'author') bg-amber-100 text-amber-700
                            @else bg-[#ECFDF5] text-[#00875A] @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Санаи бақайдгирӣ:</span>
                        <span class="font-medium text-slate-700">{{ $user->created_at ? $user->created_at->format('d.m.Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Фаъолият (2 сутун) -->
            <div class="md:col-span-2 space-y-6">
                <!-- Курсҳои муаллиф (агар бошад) -->
                @if($user->courses->count() > 0)
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                        <h3 class="text-sm font-bold text-[#1E293B]">Курсҳои сохташуда (Муаллиф)</h3>
                        <div class="divide-y divide-slate-100 text-xs">
                            @foreach($user->courses as $c)
                                <div class="py-2.5 flex items-center justify-between">
                                    <span class="font-bold text-slate-800">{{ $c->title }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $c->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $c->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Номнависиҳо (Донишҷӯ) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-[#1E293B]">Курсҳои номнависшуда ({{ $user->enrollments->count() }})</h3>
                    @if($user->enrollments->count() > 0)
                        <div class="divide-y divide-slate-100 text-xs">
                            @foreach($user->enrollments as $e)
                                <div class="py-2.5 flex items-center justify-between">
                                    <span class="font-medium text-slate-800">{{ $e->course->title ?? 'Курс' }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $e->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $e->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400">Ҳанӯз ба ягон курс номнавис нашудааст.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
