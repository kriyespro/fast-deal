<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">Lead Inquiries</h1>
        <p class="text-slate-500 text-sm mt-1">View and manage all customer inquiries and contact form submissions.</p>
    </div>
    <div class="flex gap-2">
        <span
            class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1.5 rounded-full">
            <i class="ph-fill ph-dot-outline-fill text-yellow-500"></i>
            <?= count(array_filter($leads, fn($l) => $l['status'] === 'new')) ?> New
        </span>
        <span
            class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 text-xs font-medium px-3 py-1.5 rounded-full">
            <?= count($leads) ?> Total
        </span>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div
        class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
        <i class="ph-fill ph-check-circle text-green-500 text-lg"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Leads Table -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
        <div class="flex gap-2">
            <button class="text-xs font-medium px-3 py-1.5 rounded-full bg-red text-white">All</button>
            <button
                class="text-xs font-medium px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">New</button>
            <button
                class="text-xs font-medium px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">Contacted</button>
            <button
                class="text-xs font-medium px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">Closed</button>
        </div>
    </div>

    <?php if (!empty($leads)): ?>
        <div class="divide-y divide-slate-100">
            <?php foreach ($leads as $lead): ?>
                <div class="p-6 hover:bg-slate-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 rounded-full bg-gradient-to-br from-red/10 to-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm">
                                <?= strtoupper(substr($lead['name'], 0, 2)) ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-slate-900"><?= esc($lead['name']) ?></span>
                                    <?php
                                    $statusClasses = [
                                        'new' => 'bg-yellow-100 text-yellow-800',
                                        'contacted' => 'bg-green-100 text-green-800',
                                        'closed' => 'bg-slate-100 text-slate-600',
                                    ];
                                    $sc = $statusClasses[$lead['status']] ?? 'bg-slate-100 text-slate-600';
                                    ?>
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded-full <?= $sc ?>"><?= ucfirst($lead['status']) ?></span>
                                </div>
                                <div class="text-sm text-slate-500 flex flex-wrap gap-3 mb-3">
                                    <a href="mailto:<?= esc($lead['email']) ?>"
                                        class="flex items-center gap-1 hover:text-red transition-colors">
                                        <i class="ph ph-envelope"></i> <?= esc($lead['email']) ?>
                                    </a>
                                    <?php if ($lead['phone']): ?>
                                        <a href="tel:<?= esc($lead['phone']) ?>"
                                            class="flex items-center gap-1 hover:text-red transition-colors">
                                            <i class="ph ph-phone"></i> <?= esc($lead['phone']) ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($lead['property_id']): ?>
                                        <a href="<?= base_url('listings/' . $lead['property_id']) ?>" target="_blank"
                                            class="flex items-center gap-1 hover:text-red transition-colors">
                                            <i class="ph ph-house"></i> Property #<?= $lead['property_id'] ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3 border border-slate-100 italic">
                                    "<?= esc($lead['message']) ?>"</p>
                            </div>
                        </div>
                        <div class="flex gap-2 items-start shrink-0">
                            <a href="mailto:<?= esc($lead['email']) ?>"
                                class="text-xs border border-slate-200 hover:border-red hover:text-red px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                <i class="ph ph-paper-plane-tilt"></i> Reply
                            </a>
                            <?php if ($lead['status'] === 'new'): ?>
                                <a href="<?= base_url('admin/leads/mark/' . $lead['id'] . '/contacted') ?>"
                                    class="text-xs bg-green-50 border border-green-200 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                    <i class="ph ph-check"></i> Mark Contacted
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('admin/leads/delete/' . $lead['id']) ?>"
                                onclick="return confirm('Delete this lead?')"
                                class="text-xs bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                <i class="ph ph-trash"></i>
                            </a>
                        </div>
                    </div>
                    <div class="text-xs text-slate-400 mt-3 ml-14">
                        <i class="ph ph-clock"></i> <?= date('M d, Y \a\t H:i', strtotime($lead['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="py-16 text-center text-slate-400">
            <i class="ph ph-tray text-5xl mb-3 block text-slate-200"></i>
            <p class="font-medium text-slate-500">No inquiries yet</p>
            <p class="text-sm mt-1">When visitors submit contact forms, their messages will appear here.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>