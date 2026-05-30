<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">System Settings</h1>
        <p class="text-slate-500 text-sm mt-1">Configure site preferences, contact info, and platform options.</p>
    </div>
</div>

<!-- Flash -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
        <i class="ph-fill ph-check-circle text-green-500 text-lg"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/settings/save') ?>" method="post" class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <?= csrf_field() ?>

    <!-- Left Column (Main Settings) -->
    <div class="xl:col-span-2 space-y-6">
        <!-- Site Identity -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                    <i class="ph ph-globe text-red"></i> Site Identity
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Site Name</label>
                    <input type="text" name="site_name" value="<?= esc($settings['site_name'] ?? '') ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tagline</label>
                    <input type="text" name="tagline" value="<?= esc($settings['tagline'] ?? '') ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Site Description (for SEO)</label>
                    <textarea name="site_description" rows="3"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors resize-none"><?= esc($settings['meta_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                    <i class="ph ph-phone text-red"></i> Contact Information
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Contact Email</label>
                    <input type="email" name="site_email" value="<?= esc($settings['site_email'] ?? '') ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Support Phone</label>
                    <input type="text" name="site_phone" value="<?= esc($settings['site_phone'] ?? '') ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">WhatsApp Mobile (numeric only)</label>
                    <input type="text" name="site_whatsapp" value="<?= esc($settings['site_whatsapp'] ?? '') ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Office Address</label>
                    <input type="text" name="site_address" value="<?= esc($settings['site_address'] ?? '') ?>"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                    <i class="ph ph-share-network text-red"></i> Social Media Links
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5 flex items-center gap-1.5"><i class="ph ph-instagram-logo"></i> Instagram</label>
                    <input type="url" name="social_instagram" value="<?= esc($settings['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5 flex items-center gap-1.5"><i class="ph ph-facebook-logo"></i> Facebook</label>
                    <input type="url" name="social_facebook" value="<?= esc($settings['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5 flex items-center gap-1.5"><i class="ph ph-twitter-logo"></i> Twitter / X</label>
                    <input type="url" name="social_twitter" value="<?= esc($settings['social_twitter'] ?? '') ?>" placeholder="https://twitter.com/..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5 flex items-center gap-1.5"><i class="ph ph-youtube-logo"></i> YouTube</label>
                    <input type="url" name="social_youtube" value="<?= esc($settings['social_youtube'] ?? '') ?>" placeholder="https://youtube.com/..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-6">
        <!-- Save Box -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Publish</h3>
            <button type="submit"
                class="w-full bg-red hover:bg-[#a0211b] text-white py-3 rounded-lg text-sm font-medium transition-colors shadow-lg shadow-red/20 flex items-center justify-center gap-2">
                <i class="ph-bold ph-floppy-disk"></i> Save All Settings
            </button>
            <p class="text-xs text-slate-400 mt-3 text-center">Changes are applied immediately.</p>
        </div>

        <!-- Features Toggles -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <i class="ph ph-toggle-right text-red"></i> Feature Toggles
            </h3>
            <div class="space-y-4">
                <?php
                $toggles = [
                    ['name' => 'enable_blog', 'label' => 'Blog Section', 'checked' => true],
                    ['name' => 'enable_neighborhoods', 'label' => 'Neighborhoods Page', 'checked' => true],
                    ['name' => 'enable_chat', 'label' => 'Live Chat Widget', 'checked' => false],
                    ['name' => 'enable_whatsapp', 'label' => 'WhatsApp Button', 'checked' => true],
                    ['name' => 'maintenance_mode', 'label' => 'Maintenance Mode', 'checked' => false],
                ];
                foreach ($toggles as $t): ?>
                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors"><?= $t['label'] ?></span>
                        <div class="relative">
                            <input type="checkbox" name="<?= $t['name'] ?>" class="sr-only peer"
                                <?= $t['checked'] ? 'checked' : '' ?>>
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-red transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white border border-red/20 rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-red mb-4 flex items-center gap-2">
                <i class="ph ph-warning"></i> Danger Zone
            </h3>
            <p class="text-xs text-slate-500 mb-4">Irreversible actions. Proceed with caution.</p>
            <button type="button" onclick="alert('This feature is disabled in demo mode.')"
                class="w-full border border-red/30 text-red hover:bg-red/5 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                <i class="ph ph-trash"></i> Clear All Listings
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>