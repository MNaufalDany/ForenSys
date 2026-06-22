@php
    $navbarVariant = $navbarVariant ?? 'default';
@endphp

<header class="sticky top-0 z-30 bg-[#faf8ff]/90 backdrop-blur border-b border-[#d2d4df] px-4 sm:px-6 lg:px-8">
    @if ($navbarVariant === 'dashboard')
        <div class="h-16 flex items-center justify-between gap-4">
            <div class="relative flex-1 max-w-xl">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#757682]">search</span>
                <input
                    type="text"
                    placeholder="Search case numbers, patient names, or files..."
                    class="w-full h-10 pl-10 pr-4 rounded-full border border-[#c5c5d3] bg-white/70 text-sm outline-none focus:ring-2 focus:ring-[#00236f]"
                >
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <button class="relative p-2 rounded-full hover:bg-[#e9e7ef]">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-600 rounded-full"></span>
                </button>

                <button class="hidden sm:inline-flex text-red-700 text-sm font-semibold px-3 py-2 rounded-lg hover:bg-red-50">
                    Emergency
                </button>

                <a href="{{ route('logout') }}" class="bg-[#00236f] text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-[#001a52]">
    Sign Out
</a>
            </div>
        </div>
    @else
        <div class="h-16 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs text-[#757682]">Welcome</p>
                <h3 class="text-sm font-bold text-[#00236f]">ForenSys User</h3>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <button class="p-2 rounded-full hover:bg-[#e9e7ef]">
                    <span class="material-symbols-outlined text-[#444651]">notifications</span>
                </button>

                <button class="hidden sm:block p-2 rounded-full hover:bg-[#e9e7ef]">
                    <span class="material-symbols-outlined text-[#444651]">help</span>
                </button>

                <a href="/login" class="text-[#00236f] text-sm font-bold hover:underline">
                    Sign Out
                </a>
            </div>
        </div>
    @endif
</header>
