<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($property['title']) ?> | FastDeal<?= $this->endSection() ?>

<?= $this->section('meta_desc') ?><?= esc(word_limiter(strip_tags($property['description']), 25)) ?><?= $this->endSection() ?>

<?= $this->section('extra_css') ?>
@media print {
    nav, footer, .sticky, button[title="Print"], button[title="Share"], button[title="Save to Favorites"], form {
        display: none !important;
    }
    body, .bg-slate-50 { background-color: white !important; }
    .shadow-sm, .shadow-md, .shadow-xl { box-shadow: none !important; border-color: #e2e8f0 !important; }
    img { max-height: 400px; object-fit: cover; }
    .pt-24 { padding-top: 1rem !important; }
}
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Build the real images array from the database
$galleryImages = [];
if (!empty($property['main_image'])) {
    $galleryImages[] = (strpos($property['main_image'], '://') !== false) ? $property['main_image'] : base_url($property['main_image']);
}
if (!empty($property['gallery_images'])) {
    $extra = json_decode($property['gallery_images'], true) ?? [];
    foreach ($extra as $img) {
        if (!empty($img)) {
            $galleryImages[] = (strpos($img, '://') !== false) ? $img : base_url($img);
        }
    }
}
// Fallback if no images uploaded yet
if (empty($galleryImages)) {
    $galleryImages = [
        'https://images.unsplash.com/photo-1613977257363-707ba9348227?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    ];
}
$imagesJson = json_encode($galleryImages, JSON_UNESCAPED_SLASHES);
$totalImages = count($galleryImages);
?>

<!-- Property Header & Gallery -->
<div class="pt-24 pb-12 bg-white" x-data="gallery(<?= htmlspecialchars($imagesJson, ENT_QUOTES, 'UTF-8') ?>)">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span
                        class="bg-<?= $property['listing_type'] === 'rent' ? '[#bb2821]' : 'red' ?>/10 text-<?= $property['listing_type'] === 'rent' ? '[#bb2821]' : 'red' ?> px-3 py-1 rounded-full text-sm font-bold tracking-wide uppercase">FOR
                        <?= esc($property['listing_type']) ?></span>
                    <span
                        class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium uppercase"><?= esc($property['status']) ?></span>
                    <span class="text-slate-400 text-sm"><i class="ph ph-clock"></i>
                        <?= date('M d, Y', strtotime($property['created_at'])) ?></span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold text-slate-900 mb-2"><?= esc($property['title']) ?></h1>
                <p class="text-slate-500 text-lg flex items-center gap-2">
                    <i class="ph-fill ph-map-pin text-red"></i> <?= esc($property['address']) ?>,
                    <?= esc($property['city']) ?>
                </p>
            </div>
            <div class="text-left md:text-right">
                <div class="text-4xl font-bold text-accent mb-2">₹<?= number_format($property['price'], 0) ?>
                    <?php if ($property['listing_type'] === 'rent'): ?><span
                            class="text-lg text-slate-400 font-normal">/mo</span><?php endif; ?>
                </div>
                <p class="text-slate-500 text-sm">Property ID: #FD-<?= esc($property['id']) ?></p>
                <div class="mt-4 flex gap-3 md:justify-end"
                     x-data="propertyActions(<?= $property['id'] ?>, '<?= esc($property['title'], 'js') ?>')">
                    <button @click="toggleFav"
                        :class="isFavorite ? 'text-red border-red bg-red/5' : 'text-slate-400 hover:text-red hover:border-red hover:bg-red/5 border-slate-200'"
                        class="w-10 h-10 rounded-full border flex items-center justify-center transition-colors"
                        title="Save to Favorites">
                        <i class="ph-fill ph-heart text-xl"></i>
                    </button>
                    <button @click="shareListing"
                        class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-500 hover:bg-blue-500/5 transition-colors"
                        title="Share">
                        <i class="ph-fill ph-share-network text-xl"></i>
                    </button>
                    <button onclick="window.print()"
                        class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:border-slate-900 hover:bg-slate-100 transition-colors"
                        title="Print">
                        <i class="ph-fill ph-printer text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Image Gallery Grid ───────────────────────────────────────────── -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative rounded-3xl overflow-hidden">

        <?php if ($totalImages === 1): ?>
            <!-- Single image: full-width hero -->
            <div class="relative group cursor-pointer rounded-2xl overflow-hidden h-[420px] md:h-[580px]"
                @click="lightboxOpen = true; activeImage = 0">
                <img src="<?= $galleryImages[0] ?>" alt="<?= esc($property['title']) ?>"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <button
                    class="absolute bottom-4 right-4 bg-white/90 backdrop-blur text-slate-900 px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center gap-2">
                    <i class="ph ph-arrows-out"></i> View Full Photo
                </button>
            </div>

        <?php elseif ($totalImages === 2): ?>
            <!-- Two images: 50/50 split -->
            <div class="grid grid-cols-2 gap-2 h-[400px] md:h-[560px] rounded-2xl overflow-hidden">
                <?php foreach ($galleryImages as $i => $img): ?>
                    <div class="relative group cursor-pointer" @click="lightboxOpen = true; activeImage = <?= $i ?>">
                        <img src="<?= $img ?>" alt="Photo <?= $i + 1 ?>"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <?php if ($i === 0): ?><span
                                class="absolute top-3 left-3 bg-red text-white text-xs px-2.5 py-1 rounded-full font-bold shadow">Cover</span><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($totalImages === 3): ?>
            <!-- Three images: 1 big left + 2 stacked right -->
            <div class="grid grid-cols-2 gap-2 h-[400px] md:h-[560px] rounded-2xl overflow-hidden">
                <div class="relative group cursor-pointer row-span-2" @click="lightboxOpen = true; activeImage = 0">
                    <img src="<?= $galleryImages[0] ?>" alt="Cover"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span
                        class="absolute top-3 left-3 bg-red text-white text-xs px-2.5 py-1 rounded-full font-bold shadow">Cover</span>
                </div>
                <?php foreach (array_slice($galleryImages, 1, 2) as $i => $img): ?>
                    <div class="relative group cursor-pointer" @click="lightboxOpen = true; activeImage = <?= $i + 1 ?>">
                        <img src="<?= $img ?>" alt="Photo <?= $i + 2 ?>"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <!-- 4+ images: mosaic grid -->
            <div
                class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-2 h-[400px] md:h-[560px] rounded-2xl overflow-hidden">
                <!-- Main large image -->
                <div class="md:col-span-2 md:row-span-2 relative group cursor-pointer"
                    @click="lightboxOpen = true; activeImage = 0">
                    <img src="<?= $galleryImages[0] ?>" alt="Cover"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span
                        class="absolute top-3 left-3 bg-red text-white text-xs px-2.5 py-1 rounded-full font-bold shadow">Cover</span>
                </div>

                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <?php if (isset($galleryImages[$i])): ?>
                        <div class="hidden md:block relative group cursor-pointer"
                            @click="lightboxOpen = true; activeImage = <?= $i ?>">
                            <img src="<?= $galleryImages[$i] ?>" alt="Photo <?= $i + 1 ?>"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <?php if ($i === 3 && $totalImages > 4): ?>
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg flex items-center gap-2">
                                        <i class="ph ph-images"></i> +<?= $totalImages - 4 ?> more
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!-- Empty cell placeholder when fewer than 4 extra images -->
                        <div class="hidden md:block bg-slate-100"></div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <!-- Mobile "View All" button -->
        <button @click="lightboxOpen = true; activeImage = 0"
            class="md:hidden absolute bottom-4 right-4 bg-white/90 backdrop-blur text-slate-900 px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center gap-2">
            <i class="ph ph-images"></i> <?= $totalImages ?> Photo<?= $totalImages > 1 ? 's' : '' ?>
        </button>
    </div>

    <!-- Photo count badge (desktop) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 flex justify-end">
        <button @click="lightboxOpen = true; activeImage = 0"
            class="text-sm text-slate-500 hover:text-red transition-colors flex items-center gap-1.5 font-medium">
            <i class="ph ph-images"></i> View all <?= $totalImages ?> photo<?= $totalImages > 1 ? 's' : '' ?>
        </button>
    </div>

    <!-- ── Full-screen Lightbox ──────────────────────────────────────────── -->
    <div x-show="lightboxOpen" class="fixed inset-0 z-[100] bg-black/95 flex flex-col" style="display: none;">
        <div class="flex justify-between items-center p-4 text-white p-6">
            <span class="text-lg" x-text="`${activeImage + 1} / ${images.length}`"></span>
            <button @click="lightboxOpen = false" class="text-white/70 hover:text-white transition-colors">
                <i class="ph ph-x text-3xl"></i>
            </button>
        </div>
        <div class="flex-1 relative flex items-center justify-center p-4">
            <button @click="activeImage = activeImage === 0 ? images.length - 1 : activeImage - 1"
                class="absolute left-4 md:left-12 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors backdrop-blur">
                <i class="ph-bold ph-caret-left text-2xl"></i>
            </button>
            <img :src="images[activeImage]" alt="Gallery Image"
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            <button @click="activeImage = activeImage === images.length - 1 ? 0 : activeImage + 1"
                class="absolute right-4 md:right-12 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors backdrop-blur">
                <i class="ph-bold ph-caret-right text-2xl"></i>
            </button>
        </div>
        <!-- Thumbnails strip -->
        <div class="h-24 px-4 pb-4 flex justify-center gap-2 overflow-x-auto">
            <template x-for="(img, index) in images" :key="index">
                <div class="relative h-full w-24 flex-shrink-0 cursor-pointer rounded overflow-hidden"
                    :class="activeImage === index ? 'ring-2 ring-red ring-offset-1 ring-offset-black' : 'opacity-50 hover:opacity-100'"
                    @click="activeImage = index">
                    <img :src="img" class="w-full h-full object-cover transition-all">
                </div>
            </template>
        </div>
    </div>
</div>


<!-- Details Section -->
<div class="bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Main Content (Left) -->
            <div class="w-full lg:w-2/3 space-y-12">

                <!-- Key Details -->
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6">Property Overview</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-red/10 flex items-center justify-center text-red">
                                <i class="ph ph-bed text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-slate-900"><?= esc($property['bedrooms']) ?></div>
                                <div class="text-sm text-slate-500">Bedrooms</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-red/10 flex items-center justify-center text-red">
                                <i class="ph ph-bathtub text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-slate-900"><?= esc($property['bathrooms']) ?></div>
                                <div class="text-sm text-slate-500">Bathrooms</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-red/10 flex items-center justify-center text-red">
                                <i class="ph ph-square text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-slate-900">
                                    <?= number_format($property['area_sqft']) ?>
                                </div>
                                <div class="text-sm text-slate-500">Square Ft</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-red/10 flex items-center justify-center text-red">
                                <i class="ph ph-house text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900 truncate">
                                    <?= esc($property['property_type']) ?>
                                </div>
                                <div class="text-sm text-slate-500">Type</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Description</h3>
                    <div class="prose prose-slate max-w-none text-slate-600">
                        <?= nl2br(esc($property['description'])) ?>
                    </div>
                </div>

                <!-- Details List -->
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6">Property Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Property ID</span>
                            <span class="font-medium text-slate-900"><?= esc($property['slug']) ?></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Property Type</span>
                            <span class="font-medium text-slate-900"><?= esc($property['property_type']) ?></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Property Status</span>
                            <span class="font-medium text-slate-900 uppercase"><?= esc($property['status']) ?></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">City</span>
                            <span class="font-medium text-slate-900"><?= esc($property['city']) ?></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Listed On</span>
                            <span
                                class="font-medium text-slate-900"><?= date('M d, Y', strtotime($property['created_at'])) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Features & Amenities -->
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-6">Features & Amenities</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4">
                        <?php
                        $features = json_decode($property['features'], true);
                        if (!empty($features)):
                            foreach ($features as $feature):
                                ?>
                                <div class="flex items-center gap-3">
                                    <i class="ph-bold ph-check text-red"></i>
                                    <span class="text-slate-600 text-sm"><?= esc($feature) ?></span>
                                </div>
                                <?php
                            endforeach;
                        else:
                            ?>
                            <div class="col-span-full text-slate-500 text-sm italic">No specific features listed.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Map Location -->
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-6">Location</h3>
                    <div class="h-[400px] bg-slate-200 rounded-2xl overflow-hidden border border-slate-200">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0" 
                            src="https://www.google.com/maps?q=<?= urlencode($property['address'] . ', ' . $property['city']) ?>&output=embed" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

            </div>

            <!-- Sidebar (Right) -->
            <div class="w-full lg:w-1/3">
                <div class="sticky top-28 space-y-8">

                    <!-- Agent Card -->
                    <div
                        class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-red/5 rounded-bl-full -z-0"></div>
                        <h4 class="text-lg font-bold text-slate-900 mb-6 relative z-10">Contact Agent</h4>

                        <?php if (isset($agent) && !empty($agent)): ?>
                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <?php 
                                    $photo = !empty($agent['photo']) ? $agent['photo'] : 'https://ui-avatars.com/api/?name='.urlencode($agent['name']).'&color=7F9CF5&background=EBF4FF';
                                    $photoUrl = (strpos($photo, '://') !== false) ? $photo : base_url($photo);
                                ?>
                                <img src="<?= $photoUrl ?>"
                                    alt="<?= esc($agent['name']) ?>" class="w-16 h-16 rounded-full object-cover shadow-md border-2 border-white">
                                <div>
                                    <div class="font-bold text-slate-900"><?= esc($agent['name']) ?></div>
                                    <div class="text-sm text-accent"><?= esc($agent['specialization'] ?? 'Real Estate Agent') ?></div>
                                    <a href="<?= base_url('agents/detail/' . $agent['id']) ?>"
                                        class="text-xs text-slate-400 hover:text-red transition-colors">View Profile</a>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6 relative z-10">
                                <?php if(!empty($agent['phone'])): ?>
                                <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $agent['phone'])) ?>"
                                    class="flex items-center gap-3 text-slate-600 hover:text-red transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-red">
                                        <i class="ph-fill ph-phone text-lg"></i>
                                    </div>
                                    <span class="font-medium"><?= esc($agent['phone']) ?></span>
                                </a>
                                <?php endif; ?>
                                <?php if(!empty($agent['email'])): ?>
                                <a href="mailto:<?= esc($agent['email']) ?>"
                                    class="flex items-center gap-3 text-slate-600 hover:text-red transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-red">
                                        <i class="ph-fill ph-envelope-simple text-lg"></i>
                                    </div>
                                    <span class="font-medium text-sm break-all"><?= esc($agent['email']) ?></span>
                                </a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="mb-6 relative z-10 text-slate-500 text-sm">
                                Reach out to our general inquiries line to learn more about this property.
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('lead_success')): ?>
                            <div
                                class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                                <i class="ph-fill ph-check-circle text-green-500"></i>
                                <?= session()->getFlashdata('lead_success') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('lead_errors')): ?>
                            <div class="mb-4 bg-red/5 border border-red/20 text-red rounded-xl px-4 py-3 text-sm">
                                <?php foreach ((array) session()->getFlashdata('lead_errors') as $err): ?>
                                    <p><?= esc($err) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url('leads/submit') ?>" method="POST" class="space-y-4 relative z-10">
                            <?= csrf_field() ?>
                            <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                            <div>
                                <input type="text" name="name" placeholder="Your Name" required
                                    value="<?= esc(old('name', session()->get('isLoggedIn') ? session()->get('name') : '')) ?>"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                            </div>
                            <div>
                                <input type="email" name="email" placeholder="Email Address" required
                                    value="<?= esc(old('email', session()->get('isLoggedIn') ? session()->get('email') : '')) ?>"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                            </div>
                            <div>
                                <input type="tel" name="phone" placeholder="Phone Number"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                            </div>
                            <div>
                                <textarea name="message" rows="3" placeholder="I'm interested in this property..."
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm resize-none">I'm interested in <?= esc($property['title']) ?>. Please send more details.</textarea>
                            </div>
                            <button type="submit"
                                class="w-full bg-red hover:bg-[#a0211b] text-white py-3 rounded-lg font-bold transition-transform transform hover:scale-105 shadow-lg shadow-red/20">
                                Send Message
                            </button>
                        </form>
                    </div>

                    <!-- Mortgage Calculator -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                        <h4 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2"><i
                                class="ph-fill ph-calculator text-red"></i> Mortgage Calculator</h4>

                        <div class="bg-slate-50 p-4 rounded-xl mb-6 text-center">
                            <span class="text-sm text-slate-500 block mb-1">Estimated Monthly Payment</span>
                            <span class="text-3xl font-bold text-slate-900" x-data="{
                                get payment() {
                                    // P = L[c(1 + c)^n]/[(1 + c)^n - 1]
                                    let P = $store.mortgage.price;
                                    let downAmt = P * ($store.mortgage.down / 100);
                                    let L = P - downAmt;
                                    let c = ($store.mortgage.rate / 100) / 12;
                                    let n = $store.mortgage.years * 12;
                                    if(c === 0) return L / n;
                                    let pmt = L * (c * Math.pow(1 + c, n)) / (Math.pow(1 + c, n) - 1);
                                    return Math.round(pmt);
                                }
                            }" x-text="'₹' + payment.toLocaleString()"></span>
                        </div>

                        <form class="space-y-4" x-data @submit.prevent>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <label class="text-sm text-slate-600">Total Price</label>
                                    <span class="text-sm font-bold text-slate-900"
                                        x-text="'₹' + $store.mortgage.price.toLocaleString()"></span>
                                </div>
                                <input type="range" x-model.number="$store.mortgage.price" min="500000" max="100000000"
                                    step="50000"
                                    class="w-full h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#bb2821]">
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <label class="text-sm text-slate-600">Down Payment</label>
                                    <span class="text-sm font-bold text-slate-900"
                                        x-text="$store.mortgage.down + '%'"></span>
                                </div>
                                <input type="range" x-model.number="$store.mortgage.down" min="0" max="100" step="5"
                                    class="w-full h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#bb2821]">
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="text-sm text-slate-600 block mb-1">Interest Rate (%)</label>
                                    <input type="number" x-model.number="$store.mortgage.rate" step="0.1"
                                        class="w-full bg-white border border-slate-200 rounded-lg py-2 px-3 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                                </div>
                                <div>
                                    <label class="text-sm text-slate-600 block mb-1">Loan Term (Yrs)</label>
                                    <select x-model.number="$store.mortgage.years"
                                        class="w-full bg-white border border-slate-200 rounded-lg py-2 px-3 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                                        <option value="15">15 Years</option>
                                        <option value="20">20 Years</option>
                                        <option value="30">30 Years</option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.store('mortgage', {
                                    price: <?= (float)$property['price'] ?>,
                                    down: 20,
                                    rate: 8.5,
                                    years: 20
                                });
                            });
                        </script>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Similar Properties -->
<section class="py-16 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Similar Properties</h2>
                <p class="text-slate-500">Other homes you might like in this area.</p>
            </div>
            <a href="<?= base_url('listings') ?>"
                class="hidden md:flex text-accent hover:text-slate-900 items-center gap-2 font-medium transition-colors group">
                View All <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if(!empty($similarProperties)): ?>
            <?php foreach($similarProperties as $simProp): ?>
            <!-- Card -->
            <div
                class="group rounded-2xl overflow-hidden bg-white border border-slate-200 hover:border-slate-200 hover-lift relative shadow-xl">
                <a href="<?= base_url('listings/' . ($simProp['slug'] ?? $simProp['id'])) ?>" class="block relative h-64 overflow-hidden">
                    <?php if(!empty($simProp['main_image'])): ?>
                    <img src="<?= (strpos($simProp['main_image'], '://') !== false) ? $simProp['main_image'] : base_url($simProp['main_image']) ?>"
                        alt="<?= esc($simProp['title']) ?>"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    <?php else: ?>
                    <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                        <i class="ph-fill ph-image text-4xl"></i>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-4 left-4 z-20 flex gap-2">
                        <span
                            class="bg-red/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase"><?= esc($simProp['listing_type']) ?></span>
                    </div>
                </a>
                <div class="p-6">
                    <div class="text-2xl font-bold text-accent mb-2">₹<?= number_format($simProp['price'], 2) ?></div>
                    <a href="<?= base_url('listings/' . ($simProp['slug'] ?? $simProp['id'])) ?>"
                        class="block text-xl font-bold text-slate-900 hover:text-red transition-colors mb-2 line-clamp-1"><?= esc($simProp['title']) ?></a>
                    <p class="text-slate-500 text-sm flex items-center gap-1 mb-5 line-clamp-1">
                        <i class="ph ph-map-pin text-red"></i> <?= esc($simProp['address'] ?? '') ?>, <?= esc($simProp['city']) ?>
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1 text-slate-600 text-sm"><i
                                    class="ph ph-bed text-slate-400 text-lg"></i> <?= esc($simProp['bedrooms']) ?> Beds</span>
                            <span class="flex items-center gap-1 text-slate-600 text-sm"><i
                                    class="ph ph-bathtub text-slate-400 text-lg"></i> <?= esc($simProp['bathrooms']) ?> Baths</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-8 text-slate-500">No similar properties found.</div>
            <?php endif; ?>
        </div>

        <div class="mt-8 text-center md:hidden">
            <a href="<?= base_url('listings') ?>" class="inline-flex text-accent items-center gap-2 font-medium">
                View All Similar <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('alpine:init', () => {
        // Gallery Component
        Alpine.data('gallery', (initialImages) => ({
            images: initialImages,
            activeImage: 0,
            lightboxOpen: false,
            
            init() {
                // Component initialized
            }
        }));

        // Property Actions (Favorite, Share)
        Alpine.data('propertyActions', (propertyId, propertyTitle) => ({
            isFavorite: localStorage.getItem('fav_' + propertyId) === 'true',
            
            toggleFav() {
                this.isFavorite = !this.isFavorite;
                localStorage.setItem('fav_' + propertyId, this.isFavorite);
            },
            
            shareListing() {
                if (navigator.share) {
                    navigator.share({
                        title: propertyTitle,
                        url: window.location.href
                    }).catch(console.error);
                } else {
                    navigator.clipboard.writeText(window.location.href);
                    alert("Link copied to clipboard!");
                }
            }
        }));
    });
</script>
<?= $this->endSection() ?>