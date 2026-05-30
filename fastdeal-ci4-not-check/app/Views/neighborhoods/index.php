<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Explore Neighborhoods | FastDeal
<?= $this->endSection() ?>

<?= $this->section('extra_css') ?>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="relative bg-primary pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
    </div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-red rounded-full blur-3xl opacity-20"></div>
    <div class="absolute top-20 right-20 w-64 h-64 bg-accent rounded-full blur-3xl opacity-20"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold text-white">Explore <span class="text-accent">Neighborhoods</span></h1>
            <p class="text-slate-300 text-lg">Discover the best localities with strong amenities, connectivity, and value for your next move.</p>
            <div class="flex items-center justify-center gap-3 pt-3 text-sm">
                <span class="px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20">
                    <?= esc((string) ($totalNeighborhoods ?? 0)) ?> Neighborhoods
                </span>
                <span class="px-4 py-1.5 rounded-full bg-white/10 text-white border border-white/20">
                    Surat Focus
                </span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white border-b border-slate-200 py-4 sticky top-20 z-40 lg:top-[73px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-3 justify-between items-center">
            <div class="relative w-full md:w-96" x-data="{ q: '' }">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input x-model="q" type="text" placeholder="Search neighborhood name..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-full py-2.5 pl-12 pr-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <a href="<?= base_url('listings?city=Surat') ?>" class="whitespace-nowrap px-5 py-2 rounded-full bg-red text-white text-sm font-medium transition-colors">
                    Browse Surat Listings
                </a>
            </div>
        </div>
    </div>
</div>

<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($neighborhoods)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($neighborhoods as $hood): ?>
                    <?php
                    $city = (string) ($hood['city'] ?? '');
                    $count = (int) (($cityCounts[strtolower($city)] ?? 0));
                    $img = (string) ($hood['image_path'] ?? '');
                    $imgUrl = $img !== '' ? ((strpos($img, '://') !== false) ? $img : base_url($img)) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1000&q=80';
                    ?>
                    <a href="<?= base_url('listings?city=' . urlencode($city)) ?>" class="group rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1">
                        <div class="relative h-60 overflow-hidden">
                            <img src="<?= esc($imgUrl) ?>" alt="<?= esc((string) ($hood['name'] ?? 'Neighborhood')) ?>"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 text-slate-900 text-xs font-semibold px-3 py-1 rounded-full">
                                    <?= $count ?> Properties
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-white leading-tight"><?= esc((string) ($hood['name'] ?? 'Neighborhood')) ?></h3>
                                    <p class="text-slate-200 text-sm"><?= esc($city) ?></p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-white/90 text-slate-900 flex items-center justify-center">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-slate-600 text-sm line-clamp-2">
                                <?= esc((string) ($hood['description'] ?? 'Premium neighborhood with great access and lifestyle benefits.')) ?>
                            </p>
                            <div class="mt-4 flex items-center justify-between text-sm">
                                <span class="text-slate-500">Avg. demand: High</span>
                                <span class="text-red font-semibold">View Listings</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                <i class="ph ph-map-pin text-5xl text-slate-300 mb-3"></i>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No neighborhoods added yet</h3>
                <p class="text-slate-500 mb-6">Add neighborhoods from admin panel to showcase local areas here.</p>
                <a href="<?= base_url('admin/neighborhoods') ?>" class="inline-flex px-6 py-2.5 rounded-full bg-red text-white font-medium">
                    Manage Neighborhoods
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>