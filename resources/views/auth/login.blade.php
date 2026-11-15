<x-guest-layout title="Воридшавӣ">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#1E293B] tracking-tight">Воридшавӣ ба система</h2>
        <p class="text-xs text-slate-500 mt-1">Барои дастрасӣ ба курсҳо ва маводи таълимӣ ворид шавед</p>
    </div>

    <!-- Паёми статус (масалан пас аз тағйири парол) -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] rounded-xl text-xs">
            {{ session('status') }}
        </div>
    @endif

    <!-- Хатогиҳои умумӣ -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Почтаи электронӣ</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="namuna@biyomuz.tj">
        </div>

        <!-- Парол -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Парол</label>
                <a href="{{ route('password.request') }}" class="text-xs text-[#00875A] hover:text-[#064E3B] font-medium">Паролро фаромӯш кардед?</a>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="••••••••">
        </div>

        <!-- Маро дар хотир дор -->
        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember"
                   class="h-4 w-4 rounded border-slate-300 text-[#00875A] focus:ring-[#00875A]">
            <label for="remember" class="ml-2 block text-xs text-slate-600">Маро дар хотир нигоҳ дор</label>
        </div>

        <!-- Тугмаи Воридшавӣ -->
        <div>
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-[#00875A] hover:bg-[#064E3B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00875A] shadow-md shadow-[#00875A]/20 transition">
                Ворид шудан
            </button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 text-center">
        <p class="text-xs text-slate-600">
            Ҳисоб надоред?
            <a href="{{ route('register') }}" class="font-semibold text-[#00875A] hover:text-[#064E3B]">Бақайдгирӣ</a>
        </p>
    </div>
</x-guest-layout>
