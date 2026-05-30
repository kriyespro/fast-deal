<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">Blog Posts</h1>
        <p class="text-slate-500 text-sm mt-1">Manage articles and news for the blog section.</p>
    </div>
    <a href="<?= base_url('admin/blog/create') ?>"
        class="bg-red hover:bg-[#a0211b] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-lg shadow-red/20">
        <i class="ph-bold ph-plus"></i> Write Post
    </a>
</div>

<!-- Blog Table -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
        <h3 class="font-semibold text-slate-900">All Posts</h3>
        <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full"><?= count($posts) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Title</th>
                    <th class="px-6 py-3 font-medium">Category</th>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900"><?= esc($post['title']) ?></div>
                                <div class="text-xs text-slate-500">/blog/<?= esc($post['slug']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <span class="bg-slate-100 rounded-full px-2 py-1 text-xs"><?= esc($post['category']) ?></span>
                            </td>
                            <td class="px-6 py-4 text-slate-500"><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <form action="<?= base_url('admin/blog/delete/' . $post['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline">
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
                            <i class="ph ph-article text-4xl mb-3 block text-slate-200"></i>
                            No blog posts found. Write your first post above.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
