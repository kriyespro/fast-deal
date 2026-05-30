<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>My Account | FastDeal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="bg-slate-50 border-b border-slate-200 py-12 pt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div
                class="w-24 h-24 rounded-full bg-white border-4 border-slate-100 shadow-md flex items-center justify-center text-4xl text-slate-600 font-bold overflow-hidden">
                <?= strtoupper(substr((string) session()->get('name'), 0, 1)) ?>
            </div>
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-extrabold text-slate-900 font-outfit">
                    <?= esc(session()->get('name')) ?>
                </h1>
                <p class="text-slate-500 mt-1 flex items-center justify-center md:justify-start gap-2">
                    <i class="ph-fill ph-check-circle text-green-500"></i> Verified Customer Member
                </p>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="fav-props-data"><?= $propertiesForFavoritesJson ?? '[]' ?></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
    x-data="customerDashboard()"
    x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <p class="text-xs uppercase tracking-wider font-bold text-slate-500">Account Menu</p>
                </div>
                <nav class="p-2 space-y-1">
                    <a href="#saved" @click.prevent="setTab('saved')"
                        :class="{'bg-red/10 text-red': activeTab === 'saved', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': activeTab !== 'saved'}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-colors">
                        <i class="ph-fill ph-heart text-lg"></i> Saved Properties
                    </a>
                    <a href="#inquiries" @click.prevent="setTab('inquiries')"
                        :class="{'bg-red/10 text-red': activeTab === 'inquiries', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': activeTab !== 'inquiries'}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-colors">
                        <i class="ph ph-envelope-open text-lg"></i> My Inquiries
                    </a>
                    <a href="#profile" @click.prevent="setTab('profile')"
                        :class="{'bg-red/10 text-red': activeTab === 'profile', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': activeTab !== 'profile'}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-colors">
                        <i class="ph ph-user-circle text-lg"></i> Profile Settings
                    </a>
                    <hr class="my-2 border-slate-100">
                    <a href="<?= base_url('logout') ?>"
                        class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-red-50 hover:text-red rounded-xl font-medium text-sm transition-colors">
                        <i class="ph ph-sign-out text-lg"></i> Sign Out
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3">

            <!-- Mobile Tabs -->
            <div class="mb-6 lg:hidden overflow-x-auto">
                <div class="flex space-x-2 pb-2">
                    <button type="button" @click="setTab('saved')"
                        :class="{'bg-slate-900 text-white': activeTab === 'saved', 'bg-white text-slate-600': activeTab !== 'saved'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap shadow-sm border border-slate-200">Saved</button>
                    <button type="button" @click="setTab('inquiries')"
                        :class="{'bg-slate-900 text-white': activeTab === 'inquiries', 'bg-white text-slate-600': activeTab !== 'inquiries'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap shadow-sm border border-slate-200">Inquiries</button>
                    <button type="button" @click="setTab('profile')"
                        :class="{'bg-slate-900 text-white': activeTab === 'profile', 'bg-white text-slate-600': activeTab !== 'profile'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap shadow-sm border border-slate-200">Profile</button>
                </div>
            </div>

            <!-- Tab: Saved Properties (localStorage fav_* synced with listing detail) -->
            <div x-show="activeTab === 'saved'" x-transition
                x-cloak>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 font-outfit">Saved Properties</h2>
                    <button type="button" @click="loadSaved()"
                        class="text-sm text-red font-medium hover:underline inline-flex items-center gap-1">
                        <i class="ph ph-arrows-clockwise"></i> Refresh list
                    </button>
                </div>

                <template x-if="saved.length === 0">
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <i class="ph-fill ph-heart text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">No saved properties yet</h3>
                        <p class="text-slate-500 max-w-md mx-auto mb-6">Open a listing and tap the heart to save it here. Saved items are stored in this browser.</p>
                        <a href="<?= base_url('listings') ?>" class="inline-block bg-red px-6 py-2.5 rounded-lg text-white font-medium hover:bg-[#a0211b] transition-colors shadow-md shadow-red/20">Browse listings</a>
                    </div>
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="saved.length > 0">
                    <template x-for="prop in saved" :key="prop.id">
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 transform hover:-translate-y-1">
                            <a :href="prop.url" class="block relative h-48 overflow-hidden">
                                <img :src="prop.image || 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80'"
                                    :alt="prop.title"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                    loading="lazy">
                                <div class="absolute top-4 left-4 flex gap-2">
                                    <span class="bg-red text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm"
                                        x-text="prop.listing_type === 'rent' ? 'For Rent' : 'For Sale'"></span>
                                </div>
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-slate-900/80 backdrop-blur-md text-white text-lg font-bold px-4 py-1.5 rounded-lg shadow-sm"
                                        x-text="'₹' + Number(prop.price).toLocaleString('en-IN') + (prop.listing_type === 'rent' ? ' /mo' : '')"></span>
                                </div>
                            </a>
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-slate-900 mb-1 font-outfit">
                                    <a :href="prop.url" class="hover:text-accent transition-colors" x-text="prop.title"></a>
                                </h3>
                                <p class="text-slate-500 text-sm flex items-center gap-1.5 mb-4 line-clamp-2">
                                    <i class="ph-fill ph-map-pin text-accent shrink-0"></i>
                                    <span x-text="[prop.address, prop.city].filter(Boolean).join(', ')"></span>
                                </p>
                                <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-sm">
                                    <div class="flex gap-4 text-slate-600 font-medium">
                                        <span class="flex items-center gap-1"><i class="ph-fill ph-bed text-slate-400"></i> <span x-text="prop.bedrooms"></span> Beds</span>
                                        <span class="flex items-center gap-1"><i class="ph-fill ph-shower text-slate-400"></i> <span x-text="prop.bathrooms"></span> Baths</span>
                                    </div>
                                    <button type="button" @click="removeFavorite(prop.id)"
                                        class="text-red text-sm font-medium hover:underline">Remove</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Tab: My Inquiries -->
            <div x-show="activeTab === 'inquiries'" x-transition x-cloak>
                <h2 class="text-2xl font-bold text-slate-900 font-outfit mb-6">My Inquiries</h2>

                <?php if (!empty($leads)): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <ul class="divide-y divide-slate-100">
                            <?php foreach ($leads as $lead): ?>
                            <li class="p-6 hover:bg-slate-50 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red/10 text-red flex items-center justify-center shrink-0">
                                            <i class="ph-fill ph-envelope-simple-open text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">
                                                <?php if (!empty($lead['property_title'])): ?>
                                                    <a href="<?= base_url('listings/' . (int) ($lead['property_id'] ?? 0)) ?>" class="hover:text-red transition-colors"><?= esc($lead['property_title']) ?></a>
                                                <?php else: ?>
                                                    Inquiry details
                                                <?php endif; ?>
                                            </h4>
                                            <p class="text-xs text-slate-500"><?= date('F j, Y g:i A', strtotime($lead['created_at'])) ?></p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $lead['status'] === 'new' ? 'bg-blue-100 text-blue-800' : ($lead['status'] === 'contacted' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800') ?>">
                                        <?= ucfirst($lead['status']) ?>
                                    </span>
                                </div>
                                <div class="mt-4 pl-12">
                                    <p class="text-sm text-slate-600 bg-slate-50 p-4 rounded-xl italic">"<?= esc($lead['message']) ?>"</p>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <i class="ph ph-envelope-open text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">No Inquiries Found</h3>
                        <p class="text-slate-500 max-w-sm mx-auto">You haven't made any property inquiries yet. Start browsing our listings to find your dream home.</p>
                        <a href="<?= base_url('listings') ?>" class="mt-6 inline-block bg-red px-6 py-2.5 rounded-lg text-white font-medium hover:bg-[#a0211b] transition-colors shadow-md shadow-red/20">Browse Properties</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab: Profile Settings -->
            <div x-show="activeTab === 'profile'" x-transition x-cloak>
                <h2 class="text-2xl font-bold text-slate-900 font-outfit mb-6">Profile Settings</h2>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                            <i class="ph-fill ph-check-circle text-green-500"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="mb-6 bg-red/5 border border-red/20 text-red rounded-xl px-4 py-3 text-sm">
                            <ul class="list-disc list-inside">
                                <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('customer/profile/update') ?>" method="POST" class="space-y-6">
                        <?= csrf_field() ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="<?= old('name', $user['name'] ?? '') ?>" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors">
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="<?= old('email', $user['email'] ?? '') ?>" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors">
                            </div>

                            <div class="col-span-1 md:col-span-2 border-t border-slate-100 pt-6 mt-2">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Change Password</h3>
                                <p class="text-sm text-slate-500 mb-4">Leave blank if you don't want to change your password.</p>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">New Password</label>
                                <input type="password" name="password" placeholder="••••••••" minlength="6"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-3 rounded-lg font-bold transition-colors shadow-md">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function customerDashboard() {
    return {
        activeTab: 'saved',
        saved: [],
        map: {},

        init() {
            let list = [];
            const el = document.getElementById('fav-props-data');
            if (el && el.textContent) {
                try {
                    list = JSON.parse(el.textContent);
                } catch (e) {
                    list = [];
                }
            }
            this.map = Object.fromEntries((Array.isArray(list) ? list : []).map(p => [String(p.id), p]));

            this.syncFromHash();
            window.addEventListener('hashchange', () => this.syncFromHash());
            this.loadSaved();
        },

        syncFromHash() {
            const raw = (window.location.hash || '#saved').replace(/^#/, '');
            const allowed = ['saved', 'inquiries', 'profile'];
            this.activeTab = allowed.includes(raw) ? raw : 'saved';
        },

        setTab(tab) {
            this.activeTab = tab;
            if (history.replaceState) {
                history.replaceState(null, '', '#' + tab);
            } else {
                window.location.hash = tab;
            }
            if (tab === 'saved') {
                this.loadSaved();
            }
        },

        loadSaved() {
            const out = [];
            for (let i = 0; i < localStorage.length; i++) {
                const k = localStorage.key(i);
                if (k && k.startsWith('fav_') && localStorage.getItem(k) === 'true') {
                    const id = k.slice(4);
                    if (this.map[id]) {
                        out.push(this.map[id]);
                    }
                }
            }
            this.saved = out;
        },

        removeFavorite(id) {
            localStorage.removeItem('fav_' + id);
            this.loadSaved();
        }
    };
}
</script>
<style>
[x-cloak] { display: none !important; }
</style>
<?= $this->endSection() ?>
