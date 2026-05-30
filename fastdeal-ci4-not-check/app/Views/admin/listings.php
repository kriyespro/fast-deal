<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">Property Listings</h1>
        <p class="text-slate-500 text-sm mt-1">Manage all your active and pending property listings.</p>
    </div>
    <a href="<?= base_url('admin/listings/create') ?>"
        class="bg-red hover:bg-[#a0211b] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-lg shadow-red/20">
        <i class="ph-bold ph-plus"></i> Add New Property
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="p-4 font-medium border-b border-slate-200">Property</th>
                    <th class="p-4 font-medium border-b border-slate-200">Price</th>
                    <th class="p-4 font-medium border-b border-slate-200">Status</th>
                    <th class="p-4 font-medium border-b border-slate-200">Added</th>
                    <th class="p-4 font-medium border-b border-slate-200 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if (!empty($properties)): ?>
                    <?php foreach ($properties as $prop): ?>
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <?php if ($prop['main_image']): ?>
                                        <div class="w-12 h-12 rounded bg-slate-200 overflow-hidden flex-shrink-0">
                                            <img src="<?= image_url($prop['main_image']) ?>" alt="Property Image"
                                                class="w-full h-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded bg-slate-200 flex items-center justify-center text-slate-400">
                                            <i class="ph-fill ph-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium text-slate-900"><?= esc($prop['title']) ?></div>
                                        <div class="text-xs text-slate-500"><?= esc($prop['city']) ?> •
                                            <?= esc($prop['property_type']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-slate-700 font-medium font-outfit">₹<?= number_format($prop['price'], 2) ?></td>
                            <td class="p-4">
                                <?php if ($prop['status'] === 'available'): ?>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 uppercase">Available</span>
                                <?php elseif ($prop['status'] === 'sold' || $prop['status'] === 'rented'): ?>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800 uppercase"><?= esc($prop['status']) ?></span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 uppercase"><?= esc($prop['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-slate-500"><?= date('M d, Y', strtotime($prop['created_at'])) ?></td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= base_url('admin/listings/edit/' . $prop['id']) ?>"
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Edit">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </a>
                                    <form action="<?= base_url('admin/listings/delete/' . $prop['id']) ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit"
                                            class="p-2 text-slate-400 hover:text-red hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center"
                                            title="Delete">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            <i class="ph ph-buildings text-4xl mb-2 text-slate-300"></i>
                            <p>No properties found. <a href="<?= base_url('admin/listings/create') ?>"
                                    class="text-red hover:underline">Add your first property</a>.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>