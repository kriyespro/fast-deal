<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Real Estate News & Insights | FastDeal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="relative bg-primary pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
    </div>
    <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-l from-transparent to-primary/90 z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">News & <span class="text-accent">Insights</span>
            </h1>
            <p class="text-slate-300 text-lg md:text-xl">Stay updated with the latest trends in the real estate market,
                investment tips, and neighborhood guides.</p>
        </div>
    </div>
</div>

<div class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Main Content Area -->
            <div class="w-full lg:w-2/3">

                <?php if(!empty($posts)): ?>
                    <?php 
                        // The featured post is the first one in the paginated array
                        $featured = $posts[0]; 
                        // The rest are the grid posts
                        $gridPosts = array_slice($posts, 1);
                    ?>
                    
                    <!-- Featured Post -->
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2"><i
                                class="ph-fill ph-star text-accent"></i> Featured Story</h2>
                        <a href="<?= base_url('blog/' . $featured['slug']) ?>"
                            class="group block bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-xl hover:shadow-2xl transition-shadow relative">
                            <div class="relative h-80 md:h-[400px] overflow-hidden">
                                <img src="<?= !empty($featured['featured_image']) ? ((strpos($featured['featured_image'], '://') !== false) ? $featured['featured_image'] : base_url($featured['featured_image'])) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80' ?>"
                                    alt="<?= esc($featured['title']) ?>"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                                <div class="absolute top-6 left-6">
                                    <span class="bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-4 py-2 rounded-full shadow-sm">
                                        <?= esc($featured['category'] ?? 'General') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="p-8 md:p-10 relative bg-white">
                                <div class="flex items-center gap-4 text-sm text-slate-500 mb-4 font-medium">
                                    <span class="flex items-center gap-1"><i class="ph ph-calendar-blank text-red"></i> <?= date('M d, Y', strtotime($featured['published_at'])) ?></span>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-bold text-slate-900 group-hover:text-red transition-colors mb-4 line-clamp-2">
                                    <?= esc($featured['title']) ?></h3>
                                <p class="text-slate-600 mb-6 line-clamp-3 text-lg"><?= esc($featured['excerpt']) ?></p>

                                <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-auto">
                                    <span class="text-accent font-medium group-hover:translate-x-1 transition-transform flex items-center gap-1">Read More <i class="ph-bold ph-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Latest Posts Grid -->
                    <?php if(!empty($gridPosts)): ?>
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Latest Articles</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <?php foreach($gridPosts as $post): ?>
                            <!-- Post -->
                            <a href="<?= base_url('blog/' . $post['slug']) ?>"
                                class="group block bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-shadow flex flex-col h-full">
                                <div class="relative h-56 overflow-hidden shrink-0">
                                    <img src="<?= !empty($post['featured_image']) ? ((strpos($post['featured_image'], '://') !== false) ? $post['featured_image'] : base_url($post['featured_image'])) : 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80' ?>"
                                        alt="<?= esc($post['title']) ?>"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm"><?= esc($post['category'] ?? 'General') ?></span>
                                    </div>
                                </div>
                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex items-center gap-4 text-xs text-slate-500 font-medium mb-3">
                                        <span class="flex items-center gap-1"><i class="ph ph-calendar-blank text-red"></i> <?= date('M d, Y', strtotime($post['published_at'])) ?></span>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-red transition-colors mb-3 line-clamp-2">
                                        <?= esc($post['title']) ?></h3>
                                    <p class="text-slate-600 mb-6 line-clamp-2 text-sm flex-grow"><?= esc($post['excerpt']) ?></p>
                                    <div class="pt-4 border-t border-slate-100 flex items-center">
                                        <span class="text-accent text-sm font-medium group-hover:translate-x-1 transition-transform mt-auto">Read Article &rarr;</span>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <?php if (isset($pager)): ?>
                    <div class="flex justify-center mt-12">
                        <?= $pager->links() ?>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm">
                        <i class="ph ph-article text-4xl text-slate-300 mb-4 block"></i>
                        <h2 class="text-xl font-bold text-slate-900 mb-2">No Articles Found</h2>
                        <p class="text-slate-500">Check back later for news and insights.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-1/3 space-y-8">

                <!-- Search -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Search Articles</h3>
                    <div class="relative">
                        <input type="text" placeholder="Search..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 pl-4 pr-12 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors">
                        <button
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red transition-colors">
                            <i class="ph ph-magnifying-glass text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Categories</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="#"
                                class="flex justify-between items-center text-slate-600 hover:text-red font-medium transition-colors group">
                                <span>Market Trends</span>
                                <span
                                    class="bg-slate-100 group-hover:bg-red/10 text-slate-500 group-hover:text-red px-2 py-0.5 rounded text-xs transition-colors">24</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex justify-between items-center text-slate-600 hover:text-red font-medium transition-colors group">
                                <span>Buying Advice</span>
                                <span
                                    class="bg-slate-100 group-hover:bg-red/10 text-slate-500 group-hover:text-red px-2 py-0.5 rounded text-xs transition-colors">18</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex justify-between items-center text-slate-600 hover:text-red font-medium transition-colors group">
                                <span>Selling Tips</span>
                                <span
                                    class="bg-slate-100 group-hover:bg-red/10 text-slate-500 group-hover:text-red px-2 py-0.5 rounded text-xs transition-colors">15</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex justify-between items-center text-slate-600 hover:text-red font-medium transition-colors group">
                                <span>Neighborhood Guides</span>
                                <span
                                    class="bg-slate-100 group-hover:bg-red/10 text-slate-500 group-hover:text-red px-2 py-0.5 rounded text-xs transition-colors">32</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex justify-between items-center text-slate-600 hover:text-red font-medium transition-colors group">
                                <span>Property Investment</span>
                                <span
                                    class="bg-slate-100 group-hover:bg-red/10 text-slate-500 group-hover:text-red px-2 py-0.5 rounded text-xs transition-colors">10</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Popular Posts -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-2">Popular Articles
                    </h3>
                    <div class="space-y-6">
                        <a href="#" class="flex gap-4 group">
                            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Thumb" class="w-16 h-16 rounded-lg object-cover shadow-sm">
                            <div>
                                <h4
                                    class="text-sm font-bold text-slate-900 group-hover:text-red transition-colors line-clamp-2 leading-snug mb-1.5">
                                    What to Look for When Buying a New Construction Home</h4>
                                <span class="text-xs text-slate-500 font-medium">Aug 15, 2023</span>
                            </div>
                        </a>
                        <a href="#" class="flex gap-4 group">
                            <img src="https://images.unsplash.com/photo-1512915922686-57c11dde9b6b?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Thumb" class="w-16 h-16 rounded-lg object-cover shadow-sm">
                            <div>
                                <h4
                                    class="text-sm font-bold text-slate-900 group-hover:text-red transition-colors line-clamp-2 leading-snug mb-1.5">
                                    Top Architecture Trends Defining Modern Luxury</h4>
                                <span class="text-xs text-slate-500 font-medium">Jul 22, 2023</span>
                            </div>
                        </a>
                        <a href="#" class="flex gap-4 group">
                            <img src="https://images.unsplash.com/photo-1628611225249-6c17e08285ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Thumb" class="w-16 h-16 rounded-lg object-cover shadow-sm">
                            <div>
                                <h4
                                    class="text-sm font-bold text-slate-900 group-hover:text-red transition-colors line-clamp-2 leading-snug mb-1.5">
                                    A First-Time Buyer's Guide to Property Taxes</h4>
                                <span class="text-xs text-slate-500 font-medium">Jul 08, 2023</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Newsletter Widget -->
                <div
                    class="bg-primary/5 rounded-2xl p-6 border border-primary/10 shadow-sm text-center relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 text-primary/5">
                        <i class="ph-fill ph-envelope-simple text-9xl"></i>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Subscribe to Newsletter</h3>
                        <p class="text-sm text-slate-600 mb-6">Get the latest news and property updates delivered
                            directly to your inbox.</p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your Email Address"
                                class="w-full bg-white border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red transition-colors text-sm">
                            <button type="button"
                                class="w-full bg-red hover:bg-[#a0211b] text-white py-3 rounded-lg font-bold shadow-md shadow-red/20 transition-transform transform hover:-translate-y-0.5">Subscribe
                                Now</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>