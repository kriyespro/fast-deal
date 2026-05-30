<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">Agents</h1>
        <p class="text-slate-500 text-sm mt-1">Manage real estate agents and their listings.</p>
    </div>
    <button onclick="document.getElementById('inviteModal').classList.remove('hidden')"
        class="bg-red hover:bg-[#a0211b] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-lg shadow-red/20">
        <i class="ph-bold ph-plus"></i> Add Agent
    </button>
</div>

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i class="ph-fill ph-users text-2xl"></i></div>
        <div>
            <div class="text-2xl font-bold text-slate-900"><?= count($agents) ?></div>
            <div class="text-sm text-slate-500">Total Agents</div>
        </div>
    </div>
</div>

<!-- Agents Table -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
        <h3 class="font-semibold text-slate-900">All Agents</h3>
        <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full"><?= count($agents) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Agent</th>
                    <th class="px-6 py-3 font-medium">Email / Phone</th>
                    <th class="px-6 py-3 font-medium">Experience</th>
                    <th class="px-6 py-3 font-medium">Joined</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                <?php if (!empty($agents)): ?>
                    <?php foreach ($agents as $agent): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if(!empty($agent['photo'])): ?>
                                        <img src="<?= image_url($agent['photo']) ?>" class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red/20 to-slate-200 flex items-center justify-center text-slate-700 font-bold text-sm flex-shrink-0">
                                            <?= strtoupper(substr($agent['name'], 0, 2)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="font-medium text-slate-900"><?= esc($agent['name']) ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <div><?= esc($agent['email']) ?></div>
                                <div class="text-xs text-slate-400"><?= esc($agent['phone']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-slate-600"><?= esc($agent['experience_years']) ?> Years</td>
                            <td class="px-6 py-4 text-slate-500"><?= date('M d, Y', strtotime($agent['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="mailto:<?= esc($agent['email']) ?>" class="text-slate-500 hover:text-red transition-colors px-2 py-1 rounded border border-slate-200 hover:border-red/30">
                                        <i class="ph ph-envelope"></i>
                                    </a>
                                    <form action="<?= base_url('admin/agents/delete/' . $agent['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this agent?');" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-slate-500 hover:text-red transition-colors px-2 py-1 rounded border border-slate-200 hover:border-red/30">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="ph ph-users text-4xl mb-3 block text-slate-200"></i>
                            No agents found. Add your first agent above.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Invite Modal -->
<div id="inviteModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Add New Agent</h3>
            <button onclick="document.getElementById('inviteModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 transition-colors">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        <form action="<?= base_url('admin/agents/store') ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Full Name *</label>
                <input type="text" name="name" placeholder="Enter agent name" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                    <input type="email" name="email" placeholder="agent@fastdeal.com" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                    <input type="text" name="phone" placeholder="+91..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" placeholder="91..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Exp. Years</label>
                    <input type="number" name="experience_years" value="1"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Specialization</label>
                <input type="text" name="specialization" placeholder="e.g. Luxury Apartments"
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Languages</label>
                <input type="text" name="languages" placeholder="English, Hindi"
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Photo / Avatar</label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Bio</label>
                <textarea name="bio" rows="3" placeholder="Short description..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('inviteModal').classList.add('hidden')"
                    class="flex-1 border border-slate-200 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 bg-red hover:bg-[#a0211b] text-white py-2.5 rounded-lg text-sm font-medium transition-colors shadow-lg shadow-red/20">
                    Create Agent
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>