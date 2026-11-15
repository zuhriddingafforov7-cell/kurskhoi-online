<x-guest-layout title="Барқарорсозии парол">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-[#1E293B] tracking-tight">Барқарорсозии парол</h2>
        <p class="text-xs text-slate-500 mt-1">Почтаи электронии худро ворид кунед, то пайванд барои ивази парол фиристода шавад.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] rounded-xl text-xs">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Почтаи электронӣ</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#00875A]/20 focus:border-[#00875A] transition"
                   placeholder="namuna@biyomuz.tj">
        </div>

        <div>
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-[#00875A] hover:bg-[#064E3B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00875A] shadow-md shadow-[#00875A]/20 transition">
                Ирсоли пайванди барқарорсозӣ
            </button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 text-center">
        <a href="{{ route('login') }}" class="text-xs font-semibold text-[#00875A] hover:text-[#064E3B]">
            ← Бозгашт ба воридшавӣ
        </a>
    </div>
</x-guest-layout>
