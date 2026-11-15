<x-app-layout :title="'Тағйири нақши ' . $user->name . ' — Biyomuz Academy'">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200 mb-2">
                    Маъмурият
                </span>
                <h1 class="text-2xl font-bold text-[#1E293B]">Идоракунии нақш ва профил</h1>
            </div>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                ← Бозгашт
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                        Ному насаб <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Почтаи электронӣ (танҳо хондан):
                    </label>
                    <input
                        type="email"
                        value="{{ $user->email }}"
                        disabled
                        class="w-full text-sm p-3 border border-slate-200 bg-slate-50 text-slate-500 rounded-xl"
                    >
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 mb-1">
                        Нақши корбар <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none bg-white"
                    >
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>
                            Донишҷӯ (student) — омӯзиши курсҳо, супоришҳо ва гирифтани сертификат
                        </option>
                        <option value="author" {{ old('role', $user->role) === 'author' ? 'selected' : '' }}>
                            Муаллиф (author) — сохтани курсҳо, дарсҳо, супоришҳо ва баҳодиҳӣ
                        </option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                            Маъмур (admin) — модератсияи курсҳо, идоракунии корбарон ва категорияҳо
                        </option>
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 transition">
                        Бекор кардан
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Захира кардани тағйирот
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
