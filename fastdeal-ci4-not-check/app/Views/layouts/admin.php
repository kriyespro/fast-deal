<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard | FastDeal</title>
    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a2035',
                        accent: '#c8a14b',
                        secondary: '#f0ede6',
                        red: '#b42620'
                    },
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                        inter: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-inter text-slate-600 antialiased bg-slate-50 flex flex-col min-h-screen">

    <!-- Admin Header -->
    <header class="bg-white border-b border-slate-200 z-50 sticky top-0 w-full shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?= base_url() ?>"
                    class="flex items-center gap-2 shrink-0">
                    <img src="<?= base_url('assets/images/logo.svg') ?>" alt="Fastdeal Properties — Your Next Adhyaay Starts Here" class="h-8 w-auto max-w-[200px] object-contain" width="200" height="36">
                </a>
                <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2 py-1 rounded">Owner Portal</span>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications -->
                <button class="relative text-slate-500 hover:text-slate-700">
                    <i class="ph ph-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red"></span>
                    </span>
                </button>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-2 focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('name')) ?>&background=1a2035&color=fff"
                            alt="Avatar" class="h-8 w-8 rounded-full border border-slate-200">
                        <span class="text-sm font-medium text-slate-700 hidden sm:block">
                            <?= session()->get('name') ?>
                        </span>
                        <i class="ph ph-caret-down text-slate-400 text-sm hidden sm:block"></i>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50"
                        style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs text-slate-500">Signed in as</p>
                            <p class="text-sm font-medium text-slate-900 truncate">
                                <?= session()->get('email') ?>
                            </p>
                        </div>
                        <a href="<?= base_url('/') ?>"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-red transition-colors w-full text-left">View
                            Website</a>
                        <a href="<?= base_url('logout') ?>"
                            class="block px-4 py-2 text-sm text-red hover:bg-red-50 transition-colors w-full text-left">Sign
                            out</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200 hidden md:block">
            <nav class="p-4 space-y-1">
                <?php $uri = service('uri');
                $path = $uri->getPath(); ?>
                <a href="<?= base_url('admin') ?>"
                    class="flex items-center gap-3 px-3 py-2.5 <?= $path === 'admin' || $path === 'index.php/admin' ? 'bg-slate-50 text-red' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?> rounded-lg font-medium text-sm transition-colors">
                    <i class="ph-fill ph-squares-four text-lg"></i> Overview
                </a>
                <a href="<?= base_url('admin/listings') ?>"
                    class="flex items-center gap-3 px-3 py-2.5 <?= strpos($path, 'admin/listings') !== false ? 'bg-slate-50 text-red' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?> rounded-lg font-medium text-sm transition-colors">
                    <i
                        class="ph ph-buildings text-lg <?= strpos($path, 'admin/listings') !== false ? 'text-red' : 'text-slate-400' ?>"></i>
                    Listings
                </a>
                <a href="<?= base_url('admin/agents') ?>"
                    class="flex items-center gap-3 px-3 py-2.5 <?= strpos($path, 'admin/agents') !== false ? 'bg-slate-50 text-red' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?> rounded-lg font-medium text-sm transition-colors">
                    <i
                        class="ph ph-users text-lg <?= strpos($path, 'admin/agents') !== false ? 'text-red' : 'text-slate-400' ?>"></i>
                    Agents
                </a>
                <a href="<?= base_url('admin/leads') ?>"
                    class="flex items-center gap-3 px-3 py-2.5 <?= strpos($path, 'admin/leads') !== false ? 'bg-slate-50 text-red' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?> rounded-lg font-medium text-sm transition-colors">
                    <i
                        class="ph ph-envelope text-lg <?= strpos($path, 'admin/leads') !== false ? 'text-red' : 'text-slate-400' ?>"></i>
                    Lead Messages
                </a>
                <a href="<?= base_url('admin/neighborhoods') ?>"
                    class="flex items-center gap-3 px-3 py-2.5 <?= strpos($path, 'admin/neighborhoods') !== false ? 'bg-slate-50 text-red' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?> rounded-lg font-medium text-sm transition-colors">
                    <i
                        class="ph ph-map-pin text-lg <?= strpos($path, 'admin/neighborhoods') !== false ? 'text-red' : 'text-slate-400' ?>"></i>
                    Neighborhoods
                </a>
                <a href="<?= base_url('admin/blog') ?>"
                    class="flex items-center gap-3 px-3 py-2.5 <?= strpos($path, 'admin/blog') !== false ? 'bg-slate-50 text-red' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?> rounded-lg font-medium text-sm transition-colors">
                    <i
                        class="ph ph-article text-lg <?= strpos($path, 'admin/blog') !== false ? 'text-red' : 'text-slate-400' ?>"></i>
                    Blog Posts
                </a>
                <a href="<?= base_url('admin/settings') ?>"
                    class="flex items-center gap-3 px-3 py-2.5 <?= strpos($path, 'admin/settings') !== false ? 'bg-slate-50 text-red' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?> rounded-lg font-medium text-sm transition-colors">
                    <i
                        class="ph ph-gear text-lg <?= strpos($path, 'admin/settings') !== false ? 'text-red' : 'text-slate-400' ?>"></i>
                    Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-8">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</body>

</html>