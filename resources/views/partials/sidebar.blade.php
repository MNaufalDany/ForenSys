@php
    $activePage = $activePage ?? 'dashboard';
    $sidebarVariant = $sidebarVariant ?? 'default';

    $role = session('user.role') ?? 'guest';

    $portalText = $sidebarVariant === 'dashboard'
        ? 'Medical Examiner Portal'
        : 'Autopsy Case System';

    $menus = [];

    if ($role === 'admin') {
        $menus = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard', 'url' => '/dashboard'],
            ['key' => 'cases', 'label' => 'Case Management', 'icon' => 'folder_shared', 'url' => '/cases'],
            ['key' => 'registration', 'label' => 'Registration', 'icon' => 'app_registration', 'url' => '/registration'],
            ['key' => 'examination', 'label' => 'Examination', 'icon' => 'stethoscope', 'url' => '/examination'],
            ['key' => 'reports', 'label' => 'Reports', 'icon' => 'description', 'url' => '/reports'],
            ['key' => 'users', 'label' => 'Users', 'icon' => 'group', 'url' => '/users'],
        ];
    } elseif ($role === 'register') {
        $menus = [
            ['key' => 'registration', 'label' => 'Registration', 'icon' => 'app_registration', 'url' => '/registration'],
            ['key' => 'reports', 'label' => 'Reports', 'icon' => 'description', 'url' => '/reports'],
        ];
    } elseif ($role === 'dokter') {
        $menus = [
            ['key' => 'cases', 'label' => 'Case Management', 'icon' => 'folder_shared', 'url' => '/cases'],
            ['key' => 'examination', 'label' => 'Examination', 'icon' => 'stethoscope', 'url' => '/examination'],
            ['key' => 'reports', 'label' => 'Reports', 'icon' => 'description', 'url' => '/reports'],
        ];
    }

    $menus[] = [
        'key' => 'profile',
        'label' => 'Profile',
        'icon' => 'account_circle',
        'url' => '/profile',
    ];
@endphp

<aside class="hidden lg:flex w-60 h-screen fixed left-0 top-0 bg-[#faf8ff] border-r border-[#d2d4df] flex-col overflow-y-auto">

    <!-- Brand -->
    <div class="px-5 py-6 shrink-0">
        <h1 class="text-3xl font-extrabold text-[#00236f]">
            ForenSys
        </h1>

        <p class="text-xs font-semibold tracking-wide text-[#444651]">
            {{ $portalText }}
        </p>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-3 space-y-1">
        @foreach ($menus as $menu)
            <a
                href="{{ $menu['url'] }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                    {{ $activePage === $menu['key']
                        ? ($sidebarVariant === 'dashboard'
                            ? 'bg-[#d0e1fb] text-[#00236f] font-semibold border-r-4 border-[#00236f]'
                            : 'bg-[#d0e1fb]/60 text-[#00236f] font-bold border-r-4 border-[#00236f]')
                        : 'text-[#26364d] hover:bg-[#d0e1fb]' }}"
            >
                <span class="material-symbols-outlined">
                    {{ $menu['icon'] }}
                </span>

                <span class="text-sm">
                    {{ $menu['label'] }}
                </span>
            </a>
        @endforeach
    </nav>

    <!-- Profile Card -->
    <div class="shrink-0 border-t border-[#d2d4df] p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#00236f] text-white flex items-center justify-center text-sm font-bold shrink-0">
                {{ strtoupper(substr(session('user.name') ?? 'U', 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="text-sm font-bold text-[#1a1b21] truncate">
                    {{ session('user.name') ?? 'ForenSys User' }}
                </p>

                <p class="text-[10px] uppercase text-[#666b78] tracking-wide truncate">
                    {{ session('user.role') ?? 'Guest' }}
                </p>
            </div>
        </div>
    </div>

</aside>
