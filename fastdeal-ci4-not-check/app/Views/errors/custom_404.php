<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Page Not Found | FastDeal Properties<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-[70vh] flex items-center justify-center bg-slate-50 py-20 mt-20">
    <div class="text-center max-w-2xl px-4 relative">
        <div class="absolute inset-0 -z-10 bg-red/5 blur-[100px] rounded-full"></div>
        <h1 class="text-9xl font-bold text-slate-200 mb-6 font-outfit">404</h1>
        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Property Not Found</h2>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">The page or property you are looking for might have been sold, removed, or is temporarily unavailable.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= base_url() ?>" class="bg-red hover:bg-[#a0211b] text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-red/20 hover:-translate-y-1 inline-flex items-center justify-center gap-2">
                <i class="ph-bold ph-house text-lg"></i> Back to Home
            </a>
            <a href="<?= base_url('listings') ?>" class="bg-white border border-slate-200 hover:border-accent hover:shadow-lg shadow-accent/10 text-slate-700 hover:text-accent px-8 py-3 rounded-xl font-bold transition-all inline-flex items-center justify-center gap-2">
                <i class="ph-bold ph-magnifying-glass text-lg"></i> Browse Listings
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
