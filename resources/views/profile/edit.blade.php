<x-app-layout :title="__('Профили ман')" :withSidebar="true">
    <div class="max-w-3xl space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#00875A] text-white font-black text-2xl shadow-sm">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#00875A] border border-[#A7F3D0] mb-1">
                    {{ ucfirst($user->role) }}
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-[#1E293B]">{{ $user->name }}</h1>
                <p class="text-xs text-slate-500">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Таҳрири маълумоти асосӣ --}}
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-lg font-bold text-[#1E293B]">{{ __('Тағйири ном ва почта') }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Маълумоти асосии ҳисоби худро навсозӣ намоед.</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Ному насаб</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Почтаи электронӣ</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#00875A] hover:bg-[#064E3B] text-white text-xs font-bold rounded-xl shadow-xs transition">
                        {{ __('Захираи тағйирот') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Тағйири парол --}}
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-lg font-bold text-[#1E293B]">{{ __('Тағйири парол') }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Барои амнияти ҳисоб пароли мустаҳкам (ҳадди ақал 8 аломат) интихоб кунед.</p>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">Пароли ҷорӣ</label>
                    <input
                        id="current_password"
                        type="password"
                        name="current_password"
                        required
                        class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Пароли нав</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                        >
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Тасдиқи пароли нав</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            minlength="8"
                            class="w-full text-sm p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#00875A] focus:outline-none"
                        >
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Навсозии парол
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
