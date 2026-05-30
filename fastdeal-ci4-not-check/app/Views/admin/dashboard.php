<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">Dashboard Overview</h1>
        <p class="text-slate-500 text-sm mt-1">Welcome back, <span
                class="font-semibold text-slate-900"><?= esc(session()->get('name')) ?></span>. Here's what's happening
            today.</p>
    </div>
    <a href="<?= base_url('admin/listings/create') ?>"
        class="bg-red hover:bg-[#a0211b] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-lg shadow-red/20">
        <i class="ph-bold ph-plus"></i> Add Listing
    </a>
</div>

<!-- Live Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-slate-500">Total Listings</h3>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="ph-fill ph-house text-xl"></i></div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900"><?= $totalListings ?></span>
            <span class="text-xs font-medium text-green-600 flex items-center bg-green-50 px-2 py-1 rounded-full">
                <i class="ph-bold ph-check mr-1"></i> <?= $activeListings ?> active
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-slate-500">Total Leads</h3>
            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><i class="ph-fill ph-envelope text-xl"></i></div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900"><?= $totalLeads ?></span>
            <?php if ($newLeads > 0): ?>
                <span class="text-xs font-medium text-yellow-700 flex items-center bg-yellow-50 px-2 py-1 rounded-full">
                    <i class="ph-bold ph-dot-outline-fill mr-1 text-yellow-500"></i> <?= $newLeads ?> new
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-slate-500">Platform Users</h3>
            <div class="p-2 bg-green-50 text-green-600 rounded-lg"><i class="ph-fill ph-users text-xl"></i></div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900"><?= $totalUsers ?></span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-slate-500">Properties Value</h3>
            <div class="p-2 bg-orange-50 text-orange-600 rounded-lg"><i class="ph-fill ph-currency-inr text-xl"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <?php
            $totalValue = array_sum(array_column($recentListings, 'price'));
            ?>
            <span class="text-3xl font-bold text-slate-900">Live</span>
            <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-full">All INR</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <!-- Recent Leads Table -->
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900">Recent Inquiries</h3>
            <a href="<?= base_url('admin/leads') ?>" class="text-sm text-red hover:text-[#a0211b] font-medium">View
                all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-medium border-b border-slate-200">Customer</th>
                        <th class="p-4 font-medium border-b border-slate-200">Message</th>
                        <th class="p-4 font-medium border-b border-slate-200">Status</th>
                        <th class="p-4 font-medium border-b border-slate-200 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if (!empty($recentLeads)): ?>
                        <?php foreach ($recentLeads as $lead): ?>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs">
                                            <?= strtoupper(substr($lead['name'], 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900"><?= esc($lead['name']) ?></div>
                                            <div class="text-xs text-slate-500"><?= esc($lead['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-600 max-w-xs truncate"><?= esc($lead['message']) ?></td>
                                <td class="p-4">
                                    <?php $sc = ['new' => 'bg-yellow-100 text-yellow-800', 'contacted' => 'bg-green-100 text-green-800', 'closed' => 'bg-slate-100 text-slate-600']; ?>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $sc[$lead['status']] ?? 'bg-slate-100 text-slate-600' ?>">
                                        <?= ucfirst($lead['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-right text-slate-500 text-xs">
                                    <?= date('M d', strtotime($lead['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">
                                <i class="ph ph-tray text-3xl mb-2 block text-slate-200"></i>
                                No inquiries yet. When visitors contact you, they'll appear here.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-6">
        <!-- Recent Listings -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-semibold text-slate-900">Recent Listings</h3>
                <a href="<?= base_url('admin/listings') ?>" class="text-xs text-red hover:text-[#a0211b]">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                <?php if (!empty($recentListings)): ?>
                    <?php foreach ($recentListings as $prop): ?>
                        <div class="p-4 flex items-center gap-3 hover:bg-slate-50 transition-colors">
                            <div class="w-12 h-12 flex-shrink-0 rounded-lg bg-slate-100 overflow-hidden">
                                <?php if ($prop['main_image']): ?>
                                    <img src="<?= image_url($prop['main_image']) ?>" alt="" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i class="ph ph-house text-xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-slate-900 text-sm truncate"><?= esc($prop['title']) ?></div>
                                <div class="text-xs text-slate-500">₹<?= number_format($prop['price'], 0) ?> •
                                    <?= esc($prop['city']) ?></div>
                            </div>
                            <span
                                class="text-xs px-2 py-0.5 rounded-full <?= $prop['listing_type'] === 'rent' ? 'bg-blue-50 text-blue-700' : 'bg-red/10 text-red' ?>">
                                <?= strtoupper($prop['listing_type']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-8 text-center text-slate-400">
                        <i class="ph ph-house text-3xl mb-2 block text-slate-200"></i>
                        No listings yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-wider">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="<?= base_url('admin/listings/create') ?>"
                    class="py-2.5 px-3 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-red hover:text-white hover:border-red transition-all flex items-center justify-center gap-2">
                    <i class="ph ph-plus"></i> New Listing
                </a>
                <a href="<?= base_url('admin/agents') ?>"
                    class="py-2.5 px-3 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-2">
                    <i class="ph ph-users"></i> Agents
                </a>
                <a href="<?= base_url('admin/leads') ?>"
                    class="py-2.5 px-3 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-2">
                    <i class="ph ph-envelope"></i> Leads
                </a>
                <a href="<?= base_url('admin/settings') ?>"
                    class="py-2.5 px-3 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-2">
                    <i class="ph ph-gear"></i> Settings
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>