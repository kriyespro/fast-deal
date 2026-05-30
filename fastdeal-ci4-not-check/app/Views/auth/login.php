<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login | FastDeal Properties
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Decor -->
    <div
        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20 pointer-events-none">
    </div>
    <div
        class="absolute top-0 right-0 w-[500px] h-[500px] bg-red rounded-full blur-[100px] opacity-10 pointer-events-none transform translate-x-1/2 -translate-y-1/4">
    </div>
    <div
        class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-accent rounded-full blur-[100px] opacity-10 pointer-events-none transform -translate-x-1/2 translate-y-1/4">
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 pt-20">
        <h2 class="mt-6 text-center text-4xl font-extrabold text-slate-900 border-none font-outfit tracking-tighter">
            Welcome Back
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Or <a href="#" class="font-medium text-red hover:text-[#a0211b] transition-colors">create an account</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white py-10 px-6 shadow-2xl shadow-slate-200 border border-slate-100 sm:rounded-3xl sm:px-10">

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-fill ph-warning-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                <?= session()->getFlashdata('error') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="<?= base_url('loginAttempt') ?>" method="POST">
                <?= csrf_field() ?>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700">Email address</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-envelope-simple text-slate-400 text-lg"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red/20 focus:border-red sm:text-sm bg-slate-50 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-lock-key text-slate-400 text-lg"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red/20 focus:border-red sm:text-sm bg-slate-50 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 text-red focus:ring-red border-gray-300 rounded cursor-pointer">
                        <label for="remember-me" class="ml-2 block text-sm text-slate-600 cursor-pointer">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-accent hover:text-slate-900 transition-colors">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-red/30 text-sm font-bold text-white bg-red hover:bg-[#a0211b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red transition-all transform hover:-translate-y-0.5">
                        <i class="ph-bold ph-sign-in text-lg"></i> Sign in
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-slate-500 font-medium">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div>
                        <a href="#"
                            class="w-full inline-flex justify-center py-2.5 px-4 border border-slate-300 rounded-lg shadow-sm bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 transition-colors">
                            <span class="sr-only">Sign in with Google</span>
                            <i class="ph-fill ph-google-logo text-xl text-slate-700"></i>
                        </a>
                    </div>
                    <div>
                        <a href="#"
                            class="w-full inline-flex justify-center py-2.5 px-4 border border-slate-300 rounded-lg shadow-sm bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 transition-colors">
                            <span class="sr-only">Sign in with Twitter</span>
                            <i class="ph-fill ph-twitter-logo text-xl text-[#1DA1F2]"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>