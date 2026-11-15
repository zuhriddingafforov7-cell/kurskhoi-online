<x-guest-layout title="Ивази парол">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#1E293B] tracking-tight">Муқаррар кардани пароли нав</h2>
        <p class="text-xs text-slate-500 mt-1">Пароли нави худро ворид кунед</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <!-- Токен -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Почтаи электронӣ</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="namuna@biyomuz.tj">
        </div>

        <!-- Пароли нав -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Пароли нав</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="••••••••">
        </div>

        <!-- Такрори пароли нав -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Такрори пароли нав</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="••••••••">
        </div>

        <div>
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-[#00875A] hover:bg-[#064E3B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00875A] shadow-md shadow-[#00875A]/20 transition">
                Тасдиқ ва нав кардани парол
            </button>
        </div>
    </form>
</x-guest-layout>
