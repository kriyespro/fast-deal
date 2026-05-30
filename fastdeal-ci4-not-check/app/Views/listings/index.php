<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Properties for Sale & Rent | FastDeal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="relative bg-primary pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
    </div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-accent rounded-full mb-10 blur-3xl opacity-20"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Discover Your <span class="text-accent">Next
                    Home</span></h1>
            <p class="text-slate-300 text-lg">Browse our exclusive collection of premium properties available for sale
                and rent.</p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ mobileFiltersOpen: false }">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden flex justify-between items-center mb-4">
            <span class="text-slate-600 font-medium"><?= number_format($total) ?> Properties Found</span>
            <button @click="mobileFiltersOpen = !mobileFiltersOpen"
                class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-lg text-slate-800 font-medium shadow-sm">
                <i class="ph ph-faders text-lg"></i> Filters
            </button>
        </div>

        <!-- Sidebar Filters -->
        <div class="w-full lg:w-1/4" :class="mobileFiltersOpen ? 'block' : 'hidden lg:block'">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sticky top-28 shadow-sm">
                <div class="flex justify-between items-center mb-6 lg:mb-8">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-accent pb-1 inline-block">Filter Search
                    </h3>
                    <button @click="mobileFiltersOpen = false" class="lg:hidden text-slate-400 hover:text-red">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>

                <form action="<?= base_url('listings') ?>" method="GET" class="space-y-6">
                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Location</label>
                        <div class="relative">
                            <i class="ph ph-map-pin absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="city" value="<?= esc($filters['city'] ?? '') ?>" placeholder="City or ZIP"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-10 pr-3 text-slate-900 focus:outline-none focus:border-red focus:ring-1 focus:ring-red transition-colors text-sm">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="listing_type" value="sale" <?= (!empty($filters['listing_type']) && $filters['listing_type'] == 'sale') ? 'checked' : '' ?>
                                    class="text-red focus:ring-red accent-[#bb2821]">
                                <span class="text-sm text-slate-600">For Sale</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="listing_type" value="rent" <?= (!empty($filters['listing_type']) && $filters['listing_type'] == 'rent') ? 'checked' : '' ?>
                                    class="text-red focus:ring-red accent-[#bb2821]">
                                <span class="text-sm text-slate-600">For Rent</span>
                            </label>
                        </div>
                    </div>

                    <!-- Property Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Property Type</label>
                        <select name="type"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-3 text-slate-900 focus:outline-none focus:border-red text-sm appearance-none cursor-pointer">
                            <option value="">All Types</option>
                            <option value="House" <?= (isset($filters['type']) && $filters['type'] == 'House') ? 'selected' : '' ?>>House</option>
                            <option value="Apartment" <?= (isset($filters['type']) && $filters['type'] == 'Apartment') ? 'selected' : '' ?>>Apartment</option>
                            <option value="Villa" <?= (isset($filters['type']) && $filters['type'] == 'Villa') ? 'selected' : '' ?>>Villa</option>
                            <option value="Condo" <?= (isset($filters['type']) && $filters['type'] == 'Condo') ? 'selected' : '' ?>>Condo</option>
                            <option value="Commercial" <?= (isset($filters['type']) && $filters['type'] == 'Commercial') ? 'selected' : '' ?>>Commercial</option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Price Range</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" value="<?= esc($filters['min_price'] ?? '') ?>" placeholder="Min"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-slate-900 focus:outline-none focus:border-red text-sm">
                            <span class="text-slate-400">-</span>
                            <input type="number" name="max_price" value="<?= esc($filters['max_price'] ?? '') ?>" placeholder="Max"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-slate-900 focus:outline-none focus:border-red text-sm">
                        </div>
                    </div>

                    <!-- Beds & Baths -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Beds</label>
                            <select name="beds"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-3 text-slate-900 focus:outline-none focus:border-red text-sm appearance-none cursor-pointer">
                                <option value="">Any</option>
                                <option value="1" <?= (isset($filters['beds']) && $filters['beds'] == '1') ? 'selected' : '' ?>>1+</option>
                                <option value="2" <?= (isset($filters['beds']) && $filters['beds'] == '2') ? 'selected' : '' ?>>2+</option>
                                <option value="3" <?= (isset($filters['beds']) && $filters['beds'] == '3') ? 'selected' : '' ?>>3+</option>
                                <option value="4" <?= (isset($filters['beds']) && $filters['beds'] == '4') ? 'selected' : '' ?>>4+</option>
                                <option value="5" <?= (isset($filters['beds']) && $filters['beds'] == '5') ? 'selected' : '' ?>>5+</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Baths</label>
                            <select name="baths"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-3 text-slate-900 focus:outline-none focus:border-red text-sm appearance-none cursor-pointer">
                                <option value="">Any</option>
                                <option value="1" <?= (isset($filters['baths']) && $filters['baths'] == '1') ? 'selected' : '' ?>>1+</option>
                                <option value="2" <?= (isset($filters['baths']) && $filters['baths'] == '2') ? 'selected' : '' ?>>2+</option>
                                <option value="3" <?= (isset($filters['baths']) && $filters['baths'] == '3') ? 'selected' : '' ?>>3+</option>
                                <option value="4" <?= (isset($filters['baths']) && $filters['baths'] == '4') ? 'selected' : '' ?>>4+</option>
                            </select>
                        </div>
                    </div>

                    <!-- Features -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">Features</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="rounded text-red focus:ring-red accent-[#bb2821] w-4 h-4">
                                <span class="text-sm text-slate-600">Swimming Pool</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="rounded text-red focus:ring-red accent-[#bb2821] w-4 h-4">
                                <span class="text-sm text-slate-600">Garage</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="rounded text-red focus:ring-red accent-[#bb2821] w-4 h-4">
                                <span class="text-sm text-slate-600">Air Conditioning</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="rounded text-red focus:ring-red accent-[#bb2821] w-4 h-4">
                                <span class="text-sm text-slate-600">Ocean View</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="rounded text-red focus:ring-red accent-[#bb2821] w-4 h-4">
                                <span class="text-sm text-slate-600">Gym</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="rounded text-red focus:ring-red accent-[#bb2821] w-4 h-4">
                                <span class="text-sm text-slate-600">Smart Home</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="w-full bg-red hover:bg-[#a0211b] text-white py-3 rounded-lg font-medium transition-colors shadow-md shadow-red/20 mb-3">
                            Apply Filters
                        </button>
                        <button type="button" onclick="window.location.href='<?= base_url('listings') ?>'"
                            class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 py-3 rounded-lg font-medium transition-colors">
                            Reset All
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Property Results Grid -->
        <div class="w-full lg:w-3/4" x-data="{ viewMode: 'grid' }">

            <!-- Result Header -->
            <div
                class="hidden lg:flex justify-between items-center mb-6 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-slate-600 font-medium"><span class="text-slate-900 font-bold"><?= number_format($total) ?></span> Properties
                    Found</span>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500">Sort by:</span>
                        <select
                            class="bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-3 text-slate-900 text-sm focus:outline-none focus:border-red">
                            <option>Newest</option>
                            <option>Price (Low to High)</option>
                            <option>Price (High to Low)</option>
                            <option>Most Popular</option>
                        </select>
                    </div>

                    <div class="hidden md:flex bg-slate-100 rounded-lg p-1 border border-slate-200">
                        <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-400 hover:text-slate-900'" class="px-3 py-1 rounded-md transition-all"><i
                                class="ph-fill ph-grid-four text-lg"></i></button>
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-400 hover:text-slate-900'" class="px-3 py-1 rounded-md transition-all"><i
                                class="ph ph-list text-lg"></i></button>
                    </div>
                </div>
            </div>

            <div :class="viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-6' : 'grid grid-cols-1 gap-6'">
                <?php if (!empty($properties)): ?>
                    <?php foreach ($properties as $prop): ?>
                        <div :class="viewMode === 'grid' ? 'flex flex-col' : 'flex flex-col md:flex-row'"
                            class="group rounded-2xl overflow-hidden bg-white border border-slate-200 hover:border-slate-200 hover-lift relative shadow-md">
                            <a href="<?= base_url('listings/' . $prop['id']) ?>" 
                               :class="viewMode === 'grid' ? 'h-64' : 'w-full md:w-2/5 shrink-0 h-64 md:h-auto min-h-[250px]'"
                               class="block relative overflow-hidden">
                                <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors z-10">
                                </div>
                                <img src="<?= (!empty($prop['main_image']) && str_starts_with($prop['main_image'], 'http')) ? $prop['main_image'] : ($prop['main_image'] ? base_url($prop['main_image']) : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') ?>"
                                    alt="<?= esc($prop['title']) ?>" loading="lazy"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute top-4 left-4 z-20 flex gap-2">
                                    <span
                                        class="bg-<?= $prop['listing_type'] === 'rent' ? '[#bb2821]' : 'red' ?>/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase">FOR
                                        <?= esc($prop['listing_type']) ?></span>
                                </div>
                            </a>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-2xl font-bold text-accent">₹<?= number_format($prop['price'], 0) ?>
                                        <?php if ($prop['listing_type'] === 'rent'): ?><span
                                                class="text-sm text-slate-400 font-normal">/mo</span><?php endif; ?>
                                    </div>
                                    <button class="text-slate-300 hover:text-red transition-colors"><i
                                            class="ph-fill ph-heart text-2xl"></i></button>
                                </div>
                                <a href="<?= base_url('listings/' . $prop['id']) ?>"
                                    class="block text-xl font-bold text-slate-900 hover:text-red transition-colors mb-2 line-clamp-1"><?= esc($prop['title']) ?></a>
                                <p class="text-slate-500 text-sm flex items-center gap-1 mb-5 line-clamp-1">
                                    <i class="ph ph-map-pin text-red flex-shrink-0"></i> <?= esc($prop['address'] ?? '') ?>
                                    <?= esc($prop['city'] ?? '') ?>
                                </p>
                                <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 flex-wrap gap-4">
                                    <div class="flex items-center gap-4">
                                        <span class="flex items-center gap-1 text-slate-600 text-sm font-medium"><i
                                                class="ph ph-bed text-slate-400 text-lg"></i>
                                            <?= esc($prop['bedrooms']) ?></span>
                                        <span class="flex items-center gap-1 text-slate-600 text-sm font-medium"><i
                                                class="ph ph-bathtub text-slate-400 text-lg"></i>
                                            <?= esc($prop['bathrooms']) ?></span>
                                        <span class="flex items-center gap-1 text-slate-600 text-sm font-medium"><i
                                                class="ph ph-square text-slate-400 text-lg"></i>
                                            <?= number_format($prop['area_sqft']) ?> sqft</span>
                                    </div>
                                    <?php if(!empty($prop['agent_name'])): ?>
                                    <div class="flex items-center gap-2">
                                        <?php 
                                            // Ensure we don't pass absolute URLs (like ui-avatars.com) to base_url()
                                            $agentPhoto = !empty($prop['agent_photo']) ? $prop['agent_photo'] : 'https://ui-avatars.com/api/?name='.urlencode($prop['agent_name'] ?? 'Agent').'&color=7F9CF5&background=EBF4FF';
                                            $agentPhotoUrl = (strpos($agentPhoto, '://') !== false) ? $agentPhoto : base_url($agentPhoto);
                                        ?>
                                        <img src="<?= $agentPhotoUrl ?>" alt="<?= esc($prop['agent_name']) ?>" class="w-8 h-8 rounded-full border border-slate-200 object-cover">
                                        <span class="text-xs font-bold text-slate-700"><?= esc(explode(' ', trim($prop['agent_name'] ?? ''))[0]) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-12 text-center text-slate-500 bg-white border border-slate-200 rounded-xl">
                        <i class="ph ph-buildings text-4xl mb-2 text-slate-300"></i>
                        <p>No properties found matching your criteria.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                <?= $pager->links() ?>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('extra_css') ?>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
<?= $this->endSection() ?>