<x-guest-layout title="Бақайдгирӣ">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#1E293B] tracking-tight">Эҷоди ҳисоби нав</h2>
        <p class="text-xs text-slate-500 mt-1">Барои дастрасӣ ба курсҳо ва оғози омӯзиш бақайд гирифта шавад</p>
    </div>

    <!-- Хатогиҳои валидатсия -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Ном ва насаб -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Ном ва насаб</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="Зуҳриддин Ғаффоров">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Почтаи электронӣ (Email)</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="zuhriddin@biyomuz.tj">
        </div>

        <!-- Парол -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Парол (ҳадди ақал 8 аломат)</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="••••••••">
        </div>

        <!-- Такрори парол -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Такрори парол</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="••••••••">
        </div>

        <!-- Тугмаи Бақайдгирӣ -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-[#00875A] hover:bg-[#064E3B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00875A] shadow-md shadow-[#00875A]/20 transition">
                Бақайд гирифтан
            </button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 text-center">
        <p class="text-xs text-slate-600">
            Аллакай ҳисоб доред?
            <a href="{{ route('login') }}" class="font-semibold text-[#00875A] hover:text-[#064E3B]">Ворид шудан</a>
        </p>
    </div>
</x-guest-layout>
