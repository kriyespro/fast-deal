<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">Neighborhoods</h1>
        <p class="text-slate-500 text-sm mt-1">Manage cities and neighborhoods featured on the homepage.</p>
    </div>
    <button onclick="document.getElementById('inviteModal').classList.remove('hidden')"
        class="bg-red hover:bg-[#a0211b] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-lg shadow-red/20">
        <i class="ph-bold ph-plus"></i> Add Neighborhood
    </button>
</div>

<!-- Neighborhoods Table -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
        <h3 class="font-semibold text-slate-900">All Neighborhoods</h3>
        <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full"><?= count($neighborhoods) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium w-16">Image</th>
                    <th class="px-6 py-3 font-medium">Name</th>
                    <th class="px-6 py-3 font-medium">City</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                <?php if (!empty($neighborhoods)): ?>
                    <?php foreach ($neighborhoods as $hood): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <?php if(!empty($hood['image_path'])): ?>
                                    <img src="<?= htmlspecialchars($hood['image_path']) ?>" class="w-12 h-12 rounded object-cover">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i class="ph ph-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900"><?= esc($hood['name']) ?></div>
                                <div class="text-xs text-slate-500 line-clamp-1 max-w-xs"><?= esc($hood['description']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-slate-600"><?= esc($hood['city']) ?></td>
                            <td class="px-6 py-4 text-right">
                                <form action="<?= base_url('admin/neighborhoods/delete/' . $hood['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this neighborhood?');" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-slate-500 hover:text-red transition-colors px-2 py-1 rounded border border-slate-200 hover:border-red/30">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <i class="ph ph-map-pin text-4xl mb-3 block text-slate-200"></i>
                            No neighborhoods found. Add your first city above.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="inviteModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Add Neighborhood</h3>
            <button onclick="document.getElementById('inviteModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 transition-colors">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        <form action="<?= base_url('admin/neighborhoods/store') ?>" method="post" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                <input type="text" name="name" placeholder="e.g. Bandra West or Surat" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">City</label>
                <input type="text" name="city" placeholder="e.g. Mumbai" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Image URL</label>
                <input type="url" name="image_path" placeholder="https://source.unsplash.com/..." required
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Short description..." required
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red transition-colors"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('inviteModal').classList.add('hidden')"
                    class="flex-1 border border-slate-200 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 bg-red hover:bg-[#a0211b] text-white py-2.5 rounded-lg text-sm font-medium transition-colors shadow-lg shadow-red/20">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
