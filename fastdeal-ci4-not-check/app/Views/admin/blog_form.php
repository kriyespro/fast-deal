<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <a href="<?= base_url('admin/blog') ?>" class="text-slate-500 hover:text-slate-900 flex items-center gap-2 text-sm mb-2 transition-colors">
            <i class="ph ph-arrow-left"></i> Back to Blog
        </a>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">Write Post</h1>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <form action="<?= base_url('admin/blog/store') ?>" method="post" class="p-6 sm:p-8 space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
                <input type="text" name="title" required
                    class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-red focus:ring-1 focus:ring-red text-slate-900 bg-slate-50 focus:bg-white transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                <select name="category" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-red focus:ring-1 focus:ring-red bg-slate-50 focus:bg-white transition-colors text-slate-600">
                    <option value="Market News">Market News</option>
                    <option value="Tips & Advice">Tips & Advice</option>
                    <option value="Company Updates">Company Updates</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Excerpt</label>
                <textarea name="excerpt" rows="2"
                    class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-red focus:ring-1 focus:ring-red bg-slate-50 focus:bg-white transition-colors" placeholder="Short summary for the index page"></textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Content *</label>
                <textarea name="content" rows="10" required
                    class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-red focus:ring-1 focus:ring-red bg-slate-50 focus:bg-white transition-colors font-mono"></textarea>
                <p class="text-xs text-slate-500 mt-2">You can use basic HTML like &lt;h2&gt;, &lt;p&gt;, &lt;b&gt;.</p>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-200 flex justify-end gap-3">
            <a href="<?= base_url('admin/blog') ?>"
                class="px-6 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-red hover:bg-[#A0211B] text-white font-medium rounded-lg shadow-lg shadow-red/20 transition-colors">
                Publish Post
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
