<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Our Expert Agents | FastDeal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="relative bg-primary pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
    </div>
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent to-primary/90 z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Meet Our <span class="text-accent">Experts</span>
            </h1>
            <p class="text-slate-300 text-lg md:text-xl">Work with the top 1% of real estate professionals. Our agents
                are dedicated to finding your perfect property.</p>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="bg-white border-b border-slate-200 py-6 sticky top-20 z-40 lg:top-[73px] shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form class="flex flex-col md:flex-row gap-4">

            <div class="relative flex-1">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input type="text" placeholder="Search by name or language..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 pl-12 pr-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors">
            </div>

            <div class="flex flex-col sm:flex-row gap-4 md:w-auto">
                <div class="relative w-full sm:w-48">
                    <select
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 pl-4 pr-10 text-slate-900 focus:outline-none focus:border-red focus:bg-white transition-colors appearance-none">
                        <option value="">All Locations</option>
                        <option value="ny">New York</option>
                        <option value="la">Los Angeles</option>
                        <option value="miami">Miami</option>
                        <option value="london">London</option>
                    </select>
                    <i
                        class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>
                <button type="button"
                    class="bg-red hover:bg-[#a0211b] text-white px-8 py-3 rounded-lg font-medium transition-colors whitespace-nowrap shadow-md shadow-red/20">
                    Search Agents
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Agents Grid -->
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <?php if (!empty($agents)): ?>
                <?php foreach ($agents as $agent): ?>
                    <div
                        class="bg-white rounded-2xl p-6 text-center border border-slate-200 hover:border-slate-300 transition-all hover:-translate-y-2 shadow-sm hover:shadow-xl group">
                        <div class="relative w-32 h-32 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                            <?php if(!empty($agent['photo'])): ?>
                                <img src="<?= image_url($agent['photo']) ?>"
                                    alt="<?= esc($agent['name']) ?>"
                                    class="w-full h-full rounded-full object-cover border-4 border-white shadow-md relative z-10">
                            <?php else: ?>
                                <span class="text-3xl font-bold text-slate-300 relative z-10"><?= strtoupper(substr($agent['name'], 0, 2)) ?></span>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-red rounded-full scale-110 opacity-0 group-hover:opacity-10 transition-opacity z-0">
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1"><?= esc($agent['name']) ?></h3>
                        <p class="text-accent font-medium text-sm mb-4"><?= esc($agent['specialization'] ?? 'Real Estate Agent') ?></p>
                        
                        <?php if(!empty($agent['languages'])): ?>
                            <div class="flex flex-wrap justify-center gap-2 mb-6">
                                <?php foreach (explode(',', $agent['languages']) as $lang): ?>
                                    <span class="bg-slate-100 text-slate-600 text-xs px-2 py-1 rounded"><?= esc(trim($lang)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="h-10 mb-2"></div>
                        <?php endif; ?>
                        
                        <div class="flex justify-center gap-4 mb-6 pt-4 border-t border-slate-100">
                            <?php if(!empty($agent['whatsapp'])): ?>
                            <a href="https://wa.me/<?= esc($agent['whatsapp']) ?>" target="_blank"
                                class="w-10 h-10 rounded-full bg-slate-50 hover:bg-[#25D366] hover:text-white flex items-center justify-center text-slate-400 transition-colors">
                                <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                            </a>
                            <?php endif; ?>
                            <?php if(!empty($agent['email'])): ?>
                            <a href="mailto:<?= esc($agent['email']) ?>"
                                class="w-10 h-10 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
                                <i class="ph-fill ph-envelope text-xl"></i>
                            </a>
                            <?php endif; ?>
                            <?php if(!empty($agent['phone'])): ?>
                            <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $agent['phone'])) ?>"
                                class="w-10 h-10 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
                                <i class="ph-fill ph-phone text-xl"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('agents/detail/' . $agent['id']) ?>"
                            class="block w-full py-3 border border-slate-200 rounded-lg text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900 mb-2 transition-colors">View Profile</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-12 text-center">
                    <i class="ph ph-users text-4xl text-slate-300 mb-4 block"></i>
                    <p class="text-slate-500">No agents are registered to display.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection() ?>