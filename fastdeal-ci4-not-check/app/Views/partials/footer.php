<footer class="bg-primary text-slate-300 mt-auto border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">
            <!-- Brand & About -->
            <div class="space-y-4">
                <a href="<?= base_url() ?>" class="inline-block group">
                    <img src="<?= base_url('assets/images/logo.svg') ?>" alt="Fastdeal Properties — Your Next Adhyaay Starts Here" class="h-16 w-auto max-w-[min(100%,320px)] object-contain" width="320" height="58">
                </a>
                <p class="text-sm text-slate-400 leading-relaxed mt-4">
                    Elevating the real estate experience. We curate the finest properties for those who demand excellence, exclusivity, and uncompromising quality in their ultimate residence.
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <?php if(!empty($settings['social_facebook'])): ?><a href="<?= esc($settings['social_facebook']) ?>" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-accent hover:text-white hover:-translate-y-1 transition-all"><i class="ph-fill ph-facebook-logo"></i></a><?php endif; ?>
                    <?php if(!empty($settings['social_twitter'])): ?><a href="<?= esc($settings['social_twitter']) ?>" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-accent hover:text-white hover:-translate-y-1 transition-all"><i class="ph-fill ph-twitter-logo"></i></a><?php endif; ?>
                    <?php if(!empty($settings['social_instagram'])): ?><a href="<?= esc($settings['social_instagram']) ?>" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-accent hover:text-white hover:-translate-y-1 transition-all"><i class="ph-fill ph-instagram-logo"></i></a><?php endif; ?>
                    <?php if(!empty($settings['social_youtube'])): ?><a href="<?= esc($settings['social_youtube']) ?>" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-accent hover:text-white hover:-translate-y-1 transition-all"><i class="ph-fill ph-youtube-logo"></i></a><?php endif; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="<?= base_url('listings') ?>" class="text-slate-400 hover:text-accent transition-colors flex items-center gap-2 group"><i class="ph ph-caret-right text-accent opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Properties</a></li>
                    <li><a href="<?= base_url('neighborhoods') ?>" class="text-slate-400 hover:text-accent transition-colors flex items-center gap-2 group"><i class="ph ph-caret-right text-accent opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Neighborhoods</a></li>
                    <li><a href="<?= base_url('agents') ?>" class="text-slate-400 hover:text-accent transition-colors flex items-center gap-2 group"><i class="ph ph-caret-right text-accent opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Our Agents</a></li>
                    <li><a href="<?= base_url('about') ?>" class="text-slate-400 hover:text-accent transition-colors flex items-center gap-2 group"><i class="ph ph-caret-right text-accent opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> About Us</a></li>
                    <li><a href="<?= base_url('blog') ?>" class="text-slate-400 hover:text-accent transition-colors flex items-center gap-2 group"><i class="ph ph-caret-right text-accent opacity-0 group-hover:opacity-100 transition-opacity -ml-4 group-hover:ml-0"></i> Journal</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Get In Touch</h4>
                <ul class="space-y-4">
                    <li class="flex flex-col gap-1">
                        <div class="flex items-center gap-2 text-white">
                            <i class="ph ph-phone text-accent text-xl"></i>
                            <span class="font-medium">Call Us Now</span>
                        </div>
                        <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $settings['site_phone'] ?? '')) ?>" class="text-slate-400 hover:text-white transition-colors pl-7"><?= esc($settings['site_phone'] ?? '073830 18982') ?></a>
                    </li>
                    <li class="flex flex-col gap-1">
                        <div class="flex items-center gap-2 text-white">
                            <i class="ph ph-whatsapp-logo text-accent text-xl"></i>
                            <span class="font-medium">WhatsApp Us</span>
                        </div>
                        <a href="https://wa.me/<?= esc($settings['site_whatsapp'] ?? '917383018982') ?>" class="text-slate-400 hover:text-white transition-colors pl-7">+<?= esc($settings['site_whatsapp'] ?? '91 73830 18982') ?></a>
                    </li>
                    <li class="flex flex-col gap-1">
                        <div class="flex items-start gap-2 text-white">
                            <i class="ph ph-map-pin text-accent text-xl mt-1"></i>
                            <span class="font-medium py-1">Visit Our Office</span>
                        </div>
                        <span class="text-slate-400 pl-7 text-sm leading-relaxed whitespace-pre-line">
                            <?= esc($settings['site_address'] ?? 'Shop no.6 - 7, Shripad Ethics
B/S Raj World, Palanpur Canal Rd
Opp. Santvan Kreon Street, Palanpur
Surat, Gujarat 395009, India') ?>
                        </span>
                    </li>
                    <li class="flex items-center gap-3 pt-2">
                        <i class="ph ph-clock text-accent text-xl"></i>
                        <span class="text-slate-400 text-sm">Mon - Sat: 9:00 AM - 7:00 PM</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-semibold text-lg mb-6">Newsletter</h4>
                <p class="text-slate-400 text-sm mb-4">Subscribe to our newsletter for the latest market updates and exclusive listings.</p>
                <div x-data="{ subscribed: false, email: '' }">
                    <form class="relative" x-show="!subscribed" @submit.prevent="subscribed = true; email = ''">
                        <?= csrf_field() ?>
                        <input type="email" x-model="email" name="email" placeholder="Email Address" required class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-lg py-3 px-4 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all placeholder:text-slate-500">
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-accent hover:bg-yellow-500 text-white p-2 rounded-md transition-colors">
                            <i class="ph ph-paper-plane-right"></i>
                        </button>
                    </form>
                    <div x-show="subscribed" x-transition class="bg-green-500/20 border border-green-500/50 text-green-400 rounded-lg py-3 px-4 text-sm flex items-center gap-2" style="display: none;">
                        <i class="ph-fill ph-check-circle text-lg"></i> Thank you! You have been subscribed.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-slate-500 text-sm">
                &copy; <?= date('Y') ?> FastDeal Real Estate. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-slate-500 hover:text-accent transition-colors">Privacy Policy</a>
                <a href="#" class="text-slate-500 hover:text-accent transition-colors">Terms of Service</a>
                <a href="#" class="text-slate-500 hover:text-accent transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>