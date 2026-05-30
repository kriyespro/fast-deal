<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($post['title']) ?> | FastDeal
<?= $this->endSection() ?>

<?= $this->section('meta_desc') ?><?= esc($post['excerpt']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Breadcrumbs -->
<div class="bg-white border-b border-slate-200 pt-24 pb-4">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-sm text-slate-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= base_url() ?>" class="hover:text-red transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="ph ph-caret-right mx-1"></i>
                        <a href="<?= base_url('blog') ?>" class="hover:text-red transition-colors">News</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="ph ph-caret-right mx-1"></i>
                        <span class="text-slate-900 font-medium">Article</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Article Content -->
<div class="bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <header class="mb-10 text-center">
            <div class="flex items-center justify-center gap-2 mb-6">
                <span class="bg-red/10 text-red text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    <?= esc($post['category'] ?? 'General') ?>
                </span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 mb-6 leading-tight"><?= esc($post['title']) ?></h1>

            <div
                class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-500 border-b border-slate-100 pb-8 pt-4">
                <span class="flex items-center gap-1 font-medium"><i class="ph ph-calendar-blank text-slate-400"></i>
                    <?= date('F j, Y', strtotime($post['published_at'])) ?></span>
                <span class="w-1 h-1 rounded-full bg-slate-300"></span>

                <div class="flex gap-2 ml-auto">
                    <button
                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#1DA1F2] hover:text-white transition-colors">
                        <i class="ph-fill ph-twitter-logo"></i>
                    </button>
                    <button
                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#1877F2] hover:text-white transition-colors">
                        <i class="ph-fill ph-facebook-logo"></i>
                    </button>
                    <button
                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#0A66C2] hover:text-white transition-colors">
                        <i class="ph-fill ph-linkedin-logo"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        <div class="mb-12 rounded-3xl overflow-hidden shadow-2xl shadow-slate-200 aspect-[21/9]">
            <img src="<?= !empty($post['featured_image']) ? ((strpos($post['featured_image'], '://') !== false) ? $post['featured_image'] : base_url($post['featured_image'])) : 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80' ?>"
                alt="<?= esc($post['title']) ?>" class="w-full h-full object-cover">
        </div>

        <!-- Typography Content -->
        <article class="prose prose-lg prose-slate max-w-none prose-headings:font-outfit prose-headings:font-bold prose-p:leading-relaxed prose-a:text-red hover:prose-a:text-[#a0211b] prose-img:rounded-2xl">
            <?= $post['content'] ?>
        </article>

        <!-- Tags & Share -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 py-8 border-y border-slate-200 mt-12">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-slate-900 font-bold mr-2">Tags:</span>
                <a href="#"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1 rounded-full text-sm transition-colors">Luxury</a>
                <a href="#"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1 rounded-full text-sm transition-colors">Trends
                    2024</a>
                <a href="#"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1 rounded-full text-sm transition-colors">Smart
                    Home</a>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-slate-900 font-bold">Share:</span>
                <button
                    class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#1DA1F2] hover:text-white transition-colors">
                    <i class="ph-fill ph-twitter-logo"></i>
                </button>
                <button
                    class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#1877F2] hover:text-white transition-colors">
                    <i class="ph-fill ph-facebook-logo"></i>
                </button>
                <button
                    class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:border-slate-300 transition-colors">
                    <i class="ph ph-link"></i>
                </button>
            </div>
        </div>

        <!-- Author Bio Removed -->

    </div>
</div>

<!-- Related Articles -->
<?php if (!empty($recent_posts)): ?>
<div class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-8 border-b border-slate-200 pb-4">Recent Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($recent_posts as $recent): ?>
            <!-- Post -->
            <a href="<?= base_url('blog/' . $recent['slug']) ?>"
                class="group block bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-shadow flex flex-col h-full">
                <div class="relative h-48 overflow-hidden shrink-0">
                    <img src="<?= !empty($recent['featured_image']) ? ((strpos($recent['featured_image'], '://') !== false) ? $recent['featured_image'] : base_url($recent['featured_image'])) : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80' ?>"
                        alt="<?= esc($recent['title']) ?>"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-center gap-4 text-xs text-slate-500 font-medium mb-3">
                        <span><?= date('M d, Y', strtotime($recent['published_at'])) ?></span>
                    </div>
                    <h3
                        class="text-lg font-bold text-slate-900 group-hover:text-red transition-colors mb-3 line-clamp-2">
                        <?= esc($recent['title']) ?></h3>
                    <p class="text-slate-600 mb-6 line-clamp-2 text-sm flex-grow"><?= esc($recent['excerpt']) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>