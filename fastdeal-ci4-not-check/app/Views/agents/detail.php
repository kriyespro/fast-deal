<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($agent['name']) ?> - Agent | FastDeal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Agent Header -->
<div class="pt-24 pb-8 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= base_url() ?>" class="hover:text-red transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="ph ph-caret-right mx-1"></i>
                        <a href="<?= base_url('agents') ?>" class="hover:text-red transition-colors">Agents</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="ph ph-caret-right mx-1"></i>
                        <span class="text-slate-900 font-medium"><?= esc($agent['name']) ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row gap-8 items-start">
            <!-- Profile Image -->
            <div class="w-full md:w-1/3 lg:w-1/4">
                <div class="relative rounded-2xl overflow-hidden shadow-xl aspect-square bg-slate-100 flex items-center justify-center">
                    <?php if(!empty($agent['photo'])): ?>
                        <img src="<?= image_url($agent['photo']) ?>" alt="<?= esc($agent['name']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span class="text-6xl font-bold text-slate-300"><?= strtoupper(substr($agent['name'], 0, 2)) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="w-full md:w-2/3 lg:w-3/4 flex flex-col justify-between h-full">
                <div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4">
                        <div>
                            <h1 class="text-3xl md:text-5xl font-bold text-slate-900 mb-2"><?= esc($agent['name']) ?></h1>
                            <p class="text-xl text-accent font-medium mb-4"><?= esc($agent['specialization'] ?? 'Real Estate Agent') ?></p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red hover:border-red hover:bg-red/5 transition-colors"
                                title="Save Contact">
                                <i class="ph-fill ph-heart text-xl"></i>
                            </button>
                            <button
                                class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary hover:bg-primary/5 transition-colors"
                                title="Share">
                                <i class="ph-fill ph-share-network text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8 mt-6">
                        <?php if(!empty($agent['phone'])): ?>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-red">
                                <i class="ph-fill ph-phone-call text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider font-bold">Office / Mobile</div>
                                <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $agent['phone'])) ?>" class="text-slate-900 font-medium hover:text-red"><?= esc($agent['phone']) ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($agent['whatsapp'])): ?>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#25D366]">
                                <i class="ph-fill ph-whatsapp-logo text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider font-bold">WhatsApp</div>
                                <a href="https://wa.me/<?= esc($agent['whatsapp']) ?>" class="text-slate-900 font-medium hover:text-[#25D366]">+<?= esc($agent['whatsapp']) ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-red">
                                <i class="ph-fill ph-envelope-simple text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider font-bold">Email</div>
                                <a href="mailto:<?= esc($agent['email']) ?>" class="text-slate-900 font-medium hover:text-red line-clamp-1"><?= esc($agent['email']) ?></a>
                            </div>
                        </div>
                        
                        <?php if(!empty($agent['languages'])): ?>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-red">
                                <i class="ph-fill ph-translate text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider font-bold">Languages</div>
                                <span class="text-slate-900 font-medium line-clamp-1"><?= esc($agent['languages']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-red">
                                <i class="ph-fill ph-briefcase text-lg"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider font-bold">Experience</div>
                                <span class="text-slate-900 font-medium"><?= esc($agent['experience_years']) ?> Years</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 border-t border-slate-100 pt-6">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Content (Left) -->
            <div class="w-full lg:w-2/3 space-y-12">

                <!-- About -->
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">About <?= esc(explode(' ', trim($agent['name']))[0]) ?></h2>
                    <div class="prose prose-slate max-w-none text-slate-600">
                        <?= !empty($agent['bio']) ? nl2br(esc($agent['bio'])) : '<p>No biography available yet.</p>' ?>
                    </div>
                </div>

                <!-- Active Listings -->
                <div>
                    <div class="flex justify-between items-end mb-6">
                        <h2 class="text-2xl font-bold text-slate-900">Active Listings</h2>
                        <a href="<?= base_url('listings') ?>"
                            class="text-accent hover:text-slate-900 text-sm font-medium transition-colors">View All
                            (<?= count($properties) ?>)</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if (!empty($properties)): ?>
                            <?php foreach ($properties as $prop): ?>
                            <!-- Listing Card -->
                            <div class="group rounded-2xl overflow-hidden bg-white border border-slate-200 hover:border-slate-200 hover-lift relative shadow-md">
                                <a href="<?= base_url('listings/detail/' . $prop['id']) ?>" class="block relative h-56 overflow-hidden">
                                    <img src="<?= base_url($prop['image_url'] ?? 'assets/images/placeholder.jpg') ?>"
                                        alt="<?= esc($prop['title']) ?>"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute top-4 left-4 z-20 flex gap-2">
                                        <span class="bg-red/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full">FOR <?= strtoupper(esc($prop['type'])) ?></span>
                                    </div>
                                </a>
                                <div class="p-5">
                                    <div class="text-xl font-bold text-accent mb-1">₹<?= number_format($prop['price']) ?></div>
                                    <a href="<?= base_url('listings/detail/' . $prop['id']) ?>"
                                        class="block text-lg font-bold text-slate-900 hover:text-red transition-colors mb-2 line-clamp-1"><?= esc($prop['title']) ?></a>
                                    <p class="text-slate-500 text-sm flex items-center gap-1 mb-4 line-clamp-1">
                                        <i class="ph ph-map-pin text-red"></i> <?= esc($prop['address']) ?>, <?= esc($prop['city']) ?>
                                    </p>
                                    <div class="flex items-center gap-4 pt-3 border-t border-slate-100">
                                        <span class="flex items-center gap-1 text-slate-600 text-xs font-medium"><i
                                                class="ph ph-bed text-slate-400 text-sm"></i> <?= esc($prop['bedrooms']) ?></span>
                                        <span class="flex items-center gap-1 text-slate-600 text-xs font-medium"><i
                                                class="ph ph-bathtub text-slate-400 text-sm"></i> <?= esc($prop['bathrooms']) ?></span>
                                        <span class="flex items-center gap-1 text-slate-600 text-xs font-medium"><i
                                                class="ph ph-square text-slate-400 text-sm"></i> <?= esc($prop['sqft']) ?> sqft</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full py-8 text-center text-slate-500 bg-white rounded-2xl border border-slate-200">
                                This agent currently has no active listings.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right) -->
            <div class="w-full lg:w-1/3">
                <div class="sticky top-28">
                    <!-- Contact Form -->
                    <div
                        class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-red/5 rounded-bl-full -z-0"></div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2 relative z-10">Contact <?= esc(explode(' ', trim($agent['name']))[0]) ?></h3>
                        <p class="text-sm text-slate-500 mb-6 relative z-10">Fill out the form below to send a direct message.</p>

                        <?php if (session()->getFlashdata('lead_success')): ?>
                        <div class="mb-4 bg-green-50 text-green-800 border-green-200 rounded-lg p-3 text-sm flex gap-2">
                            <i class="ph-fill ph-check-circle text-lg text-green-500 shrink-0"></i>
                            <p><?= esc(session()->getFlashdata('lead_success')) ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('lead_errors')): ?>
                        <div class="mb-4 bg-red-50 text-red-800 border-red-200 rounded-lg p-3 text-sm">
                            <ul class="list-disc pl-4 relative z-10">
                            <?php foreach (session()->getFlashdata('lead_errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <form action="<?= base_url('leads/submit') ?>" method="POST" class="space-y-4 relative z-10">
                            <?= csrf_field() ?>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Your Name</label>
                                <input type="text" name="name" value="<?= old('name') ?>" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Email Address</label>
                                <input type="email" name="email" value="<?= old('email') ?>" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Phone Number</label>
                                <input type="tel" name="phone" value="<?= old('phone') ?>"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Message</label>
                                <textarea name="message" rows="4" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors text-sm resize-none"><?= old('message') ?: "Hi " . esc(explode(' ', trim($agent['name']))[0]) . ", I'm interested in working with you to find a property..." ?></textarea>
                            </div>
                            <button type="submit"
                                class="w-full bg-red hover:bg-[#a0211b] text-white py-4 rounded-lg font-bold transition-transform transform hover:scale-105 shadow-lg shadow-red/20 mt-2">
                                Send Message
                            </button>
                        </form>
                    </div>

                    <!-- Office Location -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mt-8">
                        <h4 class="font-bold text-slate-900 mb-4">Office Location</h4>
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            alt="Map"
                            class="w-full h-32 object-cover rounded-lg mb-4 opacity-50 border border-slate-200">
                        <p class="text-sm text-slate-600 mb-1 font-bold">FastDeal HQ - New York</p>
                        <p class="text-sm text-slate-500">123 Business Avenue, Suite 400<br>New York, NY 10001</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>