<?php
$currentPath = uri_string();
$isHome = $currentPath === '' || $currentPath === '/';
?>
<nav <?= $isHome ? 'x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{ \'bg-slate-50/95 backdrop-blur-md shadow-lg\': scrolled, \'bg-transparent\': !scrolled }" class="fixed w-full z-50 transition-all duration-300 border-b border-slate-200 py-4"' : 'class="bg-white/90 backdrop-blur-md border-b border-slate-200 py-4 z-50 sticky top-0 md:fixed md:w-full md:top-0"' ?>>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="<?= base_url() ?>" class="group flex items-center">
                <img src="<?= base_url('assets/images/logo.svg') ?>" alt="Fastdeal Properties — Your Next Adhyaay Starts Here" class="h-14 w-auto max-w-[min(100%,280px)] object-contain" width="280" height="50">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 items-center">
                <a href="<?= base_url() ?>"
                    class="<?= $isHome ? 'text-accent' : 'text-slate-600 hover:text-accent' ?> text-sm font-medium transition-colors">Home</a>
                <a href="<?= base_url('listings') ?>"
                    class="<?= str_starts_with($currentPath, 'listings') ? 'text-accent' : 'text-slate-600 hover:text-accent' ?> text-sm font-medium transition-colors">Listings</a>
                <a href="<?= base_url('neighborhoods') ?>"
                    class="<?= str_starts_with($currentPath, 'neighborhoods') ? 'text-accent' : 'text-slate-600 hover:text-accent' ?> text-sm font-medium transition-colors">Neighborhoods</a>
                <a href="<?= base_url('agents') ?>"
                    class="<?= str_starts_with($currentPath, 'agents') ? 'text-accent' : 'text-slate-600 hover:text-accent' ?> text-sm font-medium transition-colors">Agents</a>
                <a href="<?= base_url('blog') ?>"
                    class="<?= str_starts_with($currentPath, 'blog') ? 'text-accent' : 'text-slate-600 hover:text-accent' ?> text-sm font-medium transition-colors">News</a>
                <a href="<?= base_url('about') ?>"
                    class="<?= str_starts_with($currentPath, 'about') ? 'text-accent' : 'text-slate-600 hover:text-accent' ?> text-sm font-medium transition-colors">About</a>
            </div>

            <!-- Actions -->
            <div class="hidden md:flex items-center space-x-4">
                <?php if ($isHome): ?>
                    <a href="<?= base_url('contact') ?>" class="text-slate-600 hover:text-slate-900 transition-colors">
                        <i class="ph ph-magnifying-glass text-xl"></i>
                    </a>
                <?php endif; ?>

                <?php if (session()->get('isLoggedIn')): ?>
                    <?php if (session()->get('role') === 'admin'): ?>
                        <a href="<?= base_url('admin') ?>"
                            class="text-slate-600 hover:text-accent font-medium text-sm flex items-center gap-1"><i
                                class="ph ph-squares-four"></i> Dashboard</a>
                    <?php else: ?>
                        <a href="<?= base_url('customer') ?>"
                            class="text-slate-600 hover:text-accent font-medium text-sm flex items-center gap-1"><i
                                class="ph ph-user"></i> My Account</a>
                    <?php endif; ?>
                    <a href="<?= base_url('logout') ?>"
                        class="text-red hover:text-[#a0211b] font-medium text-sm font-bold border border-red/20 px-4 py-2 rounded-full hover:bg-red/5 transition-colors">Logout</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>"
                        class="text-slate-600 hover:text-accent font-medium text-sm flex items-center gap-1 font-bold">Log
                        In</a>
                    <a href="<?= base_url('contact') ?>"
                        class="bg-red hover:bg-[#a0211b] text-white px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 transform hover:scale-105 shadow-lg shadow-red/30">
                        List Your Property
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-slate-600 hover:text-slate-900 focus:outline-none">
                    <i class="ph" :class="mobileMenuOpen ? 'ph-x text-2xl' : 'ph-list text-2xl'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden absolute top-full left-0 w-full bg-white border-b border-slate-200 shadow-2xl"
        @click.away="mobileMenuOpen = false" style="display: none;">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <a href="<?= base_url() ?>"
                class="block px-3 py-3 rounded-lg <?= $isHome ? 'bg-slate-100 text-accent' : 'text-slate-600 hover:bg-slate-100 hover:text-accent' ?> font-medium transition-colors">Home</a>
            <a href="<?= base_url('listings') ?>"
                class="block px-3 py-3 rounded-lg <?= str_starts_with($currentPath, 'listings') ? 'bg-slate-100 text-accent' : 'text-slate-600 hover:bg-slate-100 hover:text-accent' ?> font-medium transition-colors">Listings</a>
            <a href="<?= base_url('neighborhoods') ?>"
                class="block px-3 py-3 rounded-lg <?= str_starts_with($currentPath, 'neighborhoods') ? 'bg-slate-100 text-accent' : 'text-slate-600 hover:bg-slate-100 hover:text-accent' ?> font-medium transition-colors">Neighborhoods</a>
            <a href="<?= base_url('agents') ?>"
                class="block px-3 py-3 rounded-lg <?= str_starts_with($currentPath, 'agents') ? 'bg-slate-100 text-accent' : 'text-slate-600 hover:bg-slate-100 hover:text-accent' ?> font-medium transition-colors">Agents</a>
            <a href="<?= base_url('blog') ?>"
                class="block px-3 py-3 rounded-lg <?= str_starts_with($currentPath, 'blog') ? 'bg-slate-100 text-accent' : 'text-slate-600 hover:bg-slate-100 hover:text-accent' ?> font-medium transition-colors">News</a>
            <a href="<?= base_url('about') ?>"
                class="block px-3 py-3 rounded-lg <?= str_starts_with($currentPath, 'about') ? 'bg-slate-100 text-accent' : 'text-slate-600 hover:bg-slate-100 hover:text-accent' ?> font-medium transition-colors">About</a>
            <a href="<?= base_url('contact') ?>"
                class="block px-3 py-3 mt-4 text-center rounded-lg bg-red text-white font-medium">List Your Property</a>
        </div>
    </div>
</nav>