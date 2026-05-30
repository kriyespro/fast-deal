<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>FastDeal | Find Your Dream Home
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="relative h-screen min-h-[600px] flex items-center justify-center pt-20">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2075&q=80"
            alt="Luxury Home Background" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-slate-50/70 bg-gradient-to-t from-slate-50 via-dark/50 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
        <span
            class="inline-block py-1 px-3 rounded-full bg-red/10 text-red border border-red/30 text-sm font-medium tracking-wider mb-6 uppercase backdrop-blur-sm animate-pulse">
            Premium Real Estate
        </span>
        <h1 class="text-5xl md:text-7xl font-bold text-slate-900 leading-tight mb-6">
            Find A Home in a <br />
            <span class="text-gradient">Neighborhood You Love.</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto mb-12">
            Discover the most exclusive properties in prime locations. Experience luxury living with our curated
            collection of extraordinary homes.
        </p>

        <!-- Search Bar -->
        <form action="<?= base_url('listings') ?>" method="GET" class="max-w-4xl mx-auto glass-panel p-3 rounded-full flex flex-col md:flex-row items-center gap-3 backdrop-blur-xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] border border-slate-200"
            x-data="{ activeTab: 'sale' }">
            <input type="hidden" name="listing_type" x-model="activeTab">
            <div class="flex gap-1 w-full md:w-auto bg-slate-50/50 p-1 rounded-full border border-slate-200">
                <button type="button" @click="activeTab = 'sale'"
                    :class="activeTab === 'sale' ? 'bg-red text-white shadow-md shadow-red/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/80'"
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all w-1/2 md:w-auto">Buy</button>
                <button type="button" @click="activeTab = 'rent'"
                    :class="activeTab === 'rent' ? 'bg-red text-white shadow-md shadow-red/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/80'"
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all w-1/2 md:w-auto">Rent</button>
            </div>

            <div class="flex-1 w-full relative">
                <i class="ph ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-lg"></i>
                <input type="text" name="city" placeholder="Enter neighborhood, city, or zip code"
                    class="w-full bg-slate-100 border border-slate-200 rounded-full py-3.5 pl-11 pr-4 text-slate-900 placeholder-gray-400 focus:outline-none focus:border-red/50 focus:bg-slate-100 transition-colors">
            </div>

            <button type="submit"
                class="w-full md:w-auto bg-accent hover:bg-yellow-600 text-white px-8 py-3.5 rounded-full font-semibold transition-all flex items-center justify-center gap-2 transform hover:scale-105">
                <i class="ph ph-magnifying-glass text-lg"></i> Search
            </button>
        </form>

        <!-- Quick Stats -->
        <div class="mt-16 flex flex-wrap justify-center gap-8 md:gap-16">
            <div class="text-center">
                <div class="text-3xl font-bold text-slate-900 mb-1"><?= esc($total_properties) ?>+</div>
                <div class="text-sm text-slate-500">Premium Properties</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-slate-900 mb-1">4.9/5</div>
                <div class="text-sm text-slate-500">Client Ratings</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-slate-900 mb-1"><?= esc($total_agents) ?>+</div>
                <div class="text-sm text-slate-500">Expert Agents</div>
            </div>
        </div>
    </div>
</div>

<!-- Explore Homes Near You -->
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-4">Explore Homes<br />Near You</h2>
                <div class="flex flex-wrap gap-2 mt-6">
                    <a href="<?= base_url('listings?type=House') ?>"
                        class="px-5 py-2 rounded-full bg-white text-slate-600 hover:text-white hover:bg-red text-sm font-medium border border-slate-200 transition-colors">House</a>
                    <a href="<?= base_url('listings?type=Villa') ?>"
                        class="px-5 py-2 rounded-full bg-white text-slate-600 hover:text-white hover:bg-red text-sm font-medium border border-slate-200 transition-colors">Villa</a>
                    <a href="<?= base_url('listings?type=Apartment') ?>"
                        class="px-5 py-2 rounded-full bg-white text-slate-600 hover:text-white hover:bg-red text-sm font-medium border border-slate-200 transition-colors">Apartment</a>
                    <a href="<?= base_url('listings?type=Commercial') ?>"
                        class="px-5 py-2 rounded-full bg-white text-slate-600 hover:text-white hover:bg-red text-sm font-medium border border-slate-200 transition-colors">Commercial</a>
                </div>
            </div>
            <a href="<?= base_url('listings') ?>"
                class="mt-6 md:mt-0 text-accent hover:text-slate-900 flex items-center gap-2 font-medium transition-colors group">
                View All Homes <i class="ph ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <!-- Property Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($properties)): ?>
                <?php foreach ($properties as $prop): ?>
                    <div
                        class="group rounded-2xl overflow-hidden bg-white border border-slate-200 hover:border-slate-200 hover-lift relative shadow-xl flex flex-col">
                        <a href="<?= base_url('listings/' . $prop['id']) ?>" class="block relative h-64 overflow-hidden">
                            <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors z-10">
                            </div>
                            <?php $homeImage = !empty($prop['main_image']) ? $prop['main_image'] : ''; ?>
                            <img src="<?= $homeImage ? ((strpos($homeImage, '://') !== false) ? $homeImage : base_url($homeImage)) : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' ?>"
                                alt="<?= esc($prop['title']) ?>" loading="lazy"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute top-4 left-4 z-20 flex gap-2">
                                <span
                                    class="bg-<?= $prop['listing_type'] === 'rent' ? '[#bb2821]' : 'red' ?>/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase">FOR
                                    <?= esc($prop['listing_type']) ?></span>
                            </div>
                        </a>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="text-2xl font-bold text-accent mb-2">₹<?= number_format($prop['price'], 0) ?>
                                <?php if ($prop['listing_type'] === 'rent'): ?><span
                                        class="text-sm text-slate-400 font-normal">/mo</span><?php endif; ?>
                            </div>
                            <a href="<?= base_url('listings/' . $prop['id']) ?>"
                                class="block text-xl font-bold text-slate-900 hover:text-red transition-colors mb-2 line-clamp-1"><?= esc($prop['title']) ?></a>
                            <p class="text-slate-500 text-sm flex items-center gap-1 mb-5 line-clamp-1">
                                <i class="ph ph-map-pin text-red flex-shrink-0"></i> <?= esc($prop['address'] ?? '') ?>
                                <?= esc($prop['city'] ?? '') ?>
                            </p>
                            <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1 text-slate-600 text-sm"><i
                                            class="ph ph-bed text-slate-400 text-lg"></i> <?= esc($prop['bedrooms']) ?>
                                        Beds</span>
                                    <span class="flex items-center gap-1 text-slate-600 text-sm"><i
                                            class="ph ph-bathtub text-slate-400 text-lg"></i> <?= esc($prop['bathrooms']) ?>
                                        Baths</span>
                                </div>
                                <span class="flex items-center gap-1 text-slate-600 text-sm"><i
                                        class="ph ph-square text-slate-400 text-lg"></i>
                                    <?= number_format($prop['area_sqft']) ?> sqft</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12 text-slate-500">
                    <p>No properties available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Trust Banner -->
<section class="py-20 relative overflow-hidden bg-primary/10 border-y border-red/20">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="w-full lg:w-1/2">
                <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-6">Trusted By 100 Million+ Home Buyers</h2>
                <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                    We provide seamless, luxury real estate connections. Our expansive network ensures you find the
                    perfect match, whether you're buying your first home or investing in a multi-million dollar estate.
                </p>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start gap-3">
                        <div class="mt-1 bg-red/10 p-1 rounded-full text-red"><i class="ph-bold ph-check"></i></div>
                        <div>
                            <h4 class="text-slate-900 font-medium">Verified Property Listings</h4>
                            <p class="text-sm text-slate-500">Every property goes through a rigorous quality check.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 bg-red/10 p-1 rounded-full text-red"><i class="ph-bold ph-check"></i></div>
                        <div>
                            <h4 class="text-slate-900 font-medium">Top Tier Agents</h4>
                            <p class="text-sm text-slate-500">Work with the top 1% of agents in your selected area.</p>
                        </div>
                    </li>
                </ul>
                <a href="<?= base_url('about') ?>"
                    class="inline-flex items-center gap-2 bg-white text-dark hover:bg-gray-200 px-8 py-3 rounded-full font-semibold transition-colors">
                    Learn More <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
            <div class="w-full lg:w-1/2 relative">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-primary to-accent rounded-3xl blur-2xl opacity-20 animate-pulse">
                </div>
                <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                    alt="Trust" class="rounded-3xl relative z-10 border border-slate-200 shadow-2xl">
            </div>
        </div>
    </div>
</section>

<!-- Neighborhoods Promo -->
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-4">Prime Neighborhoods</h2>
            <p class="text-slate-500 max-w-2xl mx-auto">Explore handpicked localities offering the best lifestyle,
                schools, and amenities.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php if(!empty($neighborhoods)): ?>
                <?php foreach($neighborhoods as $i => $hood): ?>
                    <?php 
                        $classes = "h-64 lg:h-64";
                        if ($i === 0) {
                            $classes = "lg:col-span-2 lg:row-span-2 h-64 lg:h-[530px]";
                        } elseif ($i === 3) {
                            $classes = "h-64 lg:h-64 lg:col-span-2";
                        }
                    ?>
                    <a href="<?= base_url('listings?location=' . urlencode($hood['city'])) ?>" class="group relative rounded-2xl overflow-hidden <?= $classes ?>">
                        <img src="<?= htmlspecialchars($hood['image_path'] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80') ?>" alt="<?= esc($hood['name']) ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6 <?= $i === 0 ? 'p-8' : '' ?>">
                            <h3 class="text-<?= $i === 0 ? '3xl' : '2xl' ?> font-bold text-white mb-<?= $i === 0 ? '2' : '1' ?>"><?= esc($hood['name']) ?></h3>
                            <div class="text-sm font-medium text-slate-300"><?= esc($hood['city']) ?></div>
                            <?php if($i === 0): ?>
                                <p class="text-slate-300 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 translate-y-4 group-hover:translate-y-0">
                                    <?= esc($hood['description']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-slate-500 py-12">
                    <p>No prime neighborhoods configured yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-12 text-center">
            <a href="<?= base_url('neighborhoods') ?>"
                class="inline-flex items-center justify-center border border-slate-300 hover:border-white hover:bg-slate-100 px-8 py-3 rounded-full text-slate-900 font-medium transition-all">
                View All Regions
            </a>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-20 relative">
    <div class="absolute inset-0 bg-primary/20 mix-blend-multiply"></div>
    <img src="https://images.unsplash.com/photo-1613545325278-f24b0cae1224?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80"
        alt="Interior" class="absolute inset-0 w-full h-full object-cover z-[-1]">
    <div class="absolute inset-0 bg-white/90 backdrop-blur-sm z-[-1]"></div>

    <div class="max-w-4xl mx-auto px-4 text-center z-10 relative">
        <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">Ready to find your dream home?</h2>
        <p class="text-xl text-slate-600 mb-10">Join thousands of satisfied homeowners who found their perfect match
            with FastDeal.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= base_url('listings') ?>"
                class="bg-red hover:bg-[#a0211b] text-white px-8 py-4 rounded-full font-bold text-lg transition-colors shadow-lg shadow-red/30">
                Browse Properties
            </a>
            <a href="<?= base_url('contact') ?>"
                class="bg-white hover:bg-white text-slate-900 hover:text-dark border border-slate-300 px-8 py-4 rounded-full font-bold text-lg transition-colors">
                Contact an Agent
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>