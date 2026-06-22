<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ForenSys | @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-[#faf8ff] text-[#1a1b21]">
    <div class="min-h-screen flex">
        @include('partials.sidebar', [
            'activePage' => $activePage ?? 'dashboard',
            'sidebarVariant' => $sidebarVariant ?? 'default'
        ])

        <main class="w-full lg:ml-60">
            @include('partials.navbar', [
                'navbarVariant' => $navbarVariant ?? 'default'
            ])

            <section class="px-4 sm:px-6 lg:px-8 py-6 {{ ($contentWidth ?? 'default') === 'dashboard' ? 'max-w-[1180px]' : 'max-w-[1120px]' }} mx-auto">
                @yield('content')
            </section>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
