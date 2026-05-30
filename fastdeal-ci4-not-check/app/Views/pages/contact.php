<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Contact Us | FastDeal Properties
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="relative bg-primary pt-32 pb-24 overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
    </div>
    <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 w-[800px] h-full bg-red rounded-full blur-[100px] opacity-20 pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <span
                class="inline-block px-3 py-1 rounded-full bg-white/10 text-white text-sm font-bold tracking-widest uppercase mb-4 border border-white/20 backdrop-blur-sm">Get
                In Touch</span>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">How Can We <span class="text-accent">Help
                    You?</span></h1>
            <p class="text-slate-300 text-lg md:text-xl">Whether you're looking to buy, sell, or simply explore your
                options, our team of experts is here to assist you.</p>
        </div>
    </div>
</div>

<div class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20">

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            <!-- Contact Info Cards (Left) -->
            <div class="w-full lg:w-1/3 space-y-6">

                <!-- Email -->
                <div
                    class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform group">
                    <div
                        class="w-14 h-14 rounded-full bg-red/10 flex items-center justify-center text-red mb-6 mx-auto lg:mx-0 group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-envelope-simple text-3xl"></i>
                    </div>
                    <div class="text-center lg:text-left">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Email Us</h3>
                        <p class="text-slate-500 text-sm mb-4">For general inquiries and support, drop us an email.</p>
                        <a href="mailto:contact@fastdeal.com"
                            class="text-lg font-bold text-slate-900 hover:text-red transition-colors">contact@fastdeal.com</a>
                    </div>
                </div>

                <!-- Phone -->
                <div
                    class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform group">
                    <div
                        class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center text-accent mb-6 mx-auto lg:mx-0 group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-phone-call text-3xl"></i>
                    </div>
                    <div class="text-center lg:text-left">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Call Us</h3>
                        <p class="text-slate-500 text-sm mb-4">Available Mon-Fri, 9am to 6pm EST.</p>
                        <div class="space-y-1">
                            <a href="tel:+18001234567"
                                class="block text-lg font-bold text-slate-900 hover:text-accent transition-colors">+1
                                (800) 123-4567</a>
                            <a href="tel:+12125550198"
                                class="block text-slate-500 hover:text-slate-900 transition-colors">+1 (212)
                                555-0198</a>
                        </div>
                    </div>
                </div>

                <!-- HQ Location -->
                <div
                    class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform group">
                    <div
                        class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-6 mx-auto lg:mx-0 group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-map-pin text-3xl"></i>
                    </div>
                    <div class="text-center lg:text-left">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Headquarters</h3>
                        <p class="text-slate-500 text-sm mb-4">Visit our main office in New York.</p>
                        <address class="not-italic text-lg font-medium text-slate-900">
                            123 Luxury Avenue, Suite 400<br>
                            Manhattan, NY 10022<br>
                            United States
                        </address>
                        <a href="#map-section"
                            class="inline-block mt-4 text-sm font-bold text-accent hover:text-slate-900 transition-colors">Get
                            Directions &rarr;</a>
                    </div>
                </div>

            </div>

            <!-- Contact Form (Right) -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200 shadow-2xl shadow-slate-200/50">

                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">Send a Message</h2>
                        <p class="text-slate-500">Fill out the form below and one of our associated brokers will get
                            back to you within 24 hours.</p>
                    </div>

                    <?php if (session()->getFlashdata('contact_success')): ?>
                    <!-- Success Message -->
                    <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-4 flex gap-3 text-green-800">
                        <i class="ph-fill ph-check-circle text-2xl text-green-500 shrink-0"></i>
                        <div>
                            <h4 class="font-bold">Message Sent Successfully!</h4>
                            <p class="text-sm"><?= esc(session()->getFlashdata('contact_success')) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error_messages')): ?>
                    <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-4 text-red-800">
                        <ul class="list-disc pl-5">
                        <?php foreach (session()->getFlashdata('error_messages') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <form action="<?= base_url('contact/submit') ?>" method="POST" class="space-y-6">
                        <?= csrf_field() ?>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Full Name <span class="text-red">*</span></label>
                                <input type="text" name="name" value="<?= old('name') ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white focus:ring-2 focus:ring-red/20 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address <span class="text-red">*</span></label>
                                <input type="email" name="email" value="<?= old('email') ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white focus:ring-2 focus:ring-red/20 transition-all">
                            </div>
                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="<?= old('phone') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white focus:ring-2 focus:ring-red/20 transition-all">
                            </div>
                        </div>

                        <!-- Interest Area -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">I am interested in... <span
                                    class="text-red">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="interest" value="buying" class="peer sr-only" checked>
                                    <div
                                        class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-sm font-medium text-slate-600 peer-checked:bg-red/5 peer-checked:border-red peer-checked:text-red transition-all group-hover:border-slate-300">
                                        Buying
                                    </div>
                                    <i
                                        class="ph-fill ph-check-circle absolute top-1 right-1 text-red opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="interest" value="selling" class="peer sr-only">
                                    <div
                                        class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-sm font-medium text-slate-600 peer-checked:bg-red/5 peer-checked:border-red peer-checked:text-red transition-all group-hover:border-slate-300">
                                        Selling
                                    </div>
                                    <i
                                        class="ph-fill ph-check-circle absolute top-1 right-1 text-red opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="interest" value="renting" class="peer sr-only">
                                    <div
                                        class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-sm font-medium text-slate-600 peer-checked:bg-red/5 peer-checked:border-red peer-checked:text-red transition-all group-hover:border-slate-300">
                                        Renting
                                    </div>
                                    <i
                                        class="ph-fill ph-check-circle absolute top-1 right-1 text-red opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="interest" value="investing" class="peer sr-only">
                                    <div
                                        class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-sm font-medium text-slate-600 peer-checked:bg-red/5 peer-checked:border-red peer-checked:text-red transition-all group-hover:border-slate-300">
                                        Investing
                                    </div>
                                    <i
                                        class="ph-fill ph-check-circle absolute top-1 right-1 text-red opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Message <span class="text-red">*</span></label>
                            <textarea name="message" rows="5" required placeholder="Tell us about your requirements, preferred locations, or budget..." class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-900 focus:outline-none focus:border-red focus:bg-white focus:ring-2 focus:ring-red/20 transition-all resize-y"><?= old('message') ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 mt-8 border-t border-slate-100">
                            <button type="submit"
                                class="w-full md:w-auto bg-red hover:bg-[#a0211b] text-white px-10 py-4 rounded-xl font-bold transition-all transform hover:-translate-y-1 shadow-lg shadow-red/30 flex items-center justify-center gap-2">
                                <span>Send Inquiry <i class="ph-bold ph-paper-plane-right ml-1"></i></span>
                            </button>
                            <p class="text-xs text-slate-400 mt-4 max-w-lg">By submitting this form, you agree to our
                                Terms of Service and Privacy Policy. Your information will be kept confidential.</p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Map Section Full Width -->
<div id="map-section" class="h-[500px] w-full bg-slate-200 relative group overflow-hidden border-y border-slate-300">
    <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors z-10 pointer-events-none">
    </div>
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.6173007559194!2d-73.9749!3d40.7589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ1JzMyLjAiTiA3M8KwNTgnMjkuNiJX!5e0!3m2!1sen!2sus!4v1"
        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
        class="filter grayscale-[50%] contrast-125">
    </iframe>
</div>

<!-- FAQ Quick Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-slate-500">Quick answers to common questions about working with FastDeal.</p>
        </div>

        <div class="space-y-4" x-data="{ expanded: null }">

            <!-- FAQ 1 -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <button @click="expanded = expanded === 1 ? null : 1"
                    class="w-full flex justify-between items-center p-6 bg-white hover:bg-slate-50 transition-colors text-left focus:outline-none">
                    <span class="font-bold text-slate-900 text-lg">What areas do you service?</span>
                    <i class="ph text-xl text-slate-400 transition-transform"
                        :class="expanded === 1 ? 'ph-minus' : 'ph-plus'"></i>
                </button>
                <div x-show="expanded === 1" x-collapse style="display: none;">
                    <div class="p-6 pt-0 text-slate-600 bg-white border-t border-slate-100">
                        We have a primary focus on major metropolitan luxury markets including New York, Los Angeles,
                        Miami, London, and Dubai. However, our extensive global network of partner brokerages allows us
                        to assist clients with property acquisitions anywhere in the world.
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <button @click="expanded = expanded === 2 ? null : 2"
                    class="w-full flex justify-between items-center p-6 bg-white hover:bg-slate-50 transition-colors text-left focus:outline-none">
                    <span class="font-bold text-slate-900 text-lg">How quickly can I sell my luxury property?</span>
                    <i class="ph text-xl text-slate-400 transition-transform"
                        :class="expanded === 2 ? 'ph-minus' : 'ph-plus'"></i>
                </button>
                <div x-show="expanded === 2" x-collapse style="display: none;">
                    <div class="p-6 pt-0 text-slate-600 bg-white border-t border-slate-100">
                        The timeline varies depending on the specific uniqueness of the property, price point, and
                        current market conditions. Our strategic global marketing plans are designed to minimize days on
                        market while maximizing return. We will provide a customized timeline estimate during our
                        initial consultation.
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <button @click="expanded = expanded === 3 ? null : 3"
                    class="w-full flex justify-between items-center p-6 bg-white hover:bg-slate-50 transition-colors text-left focus:outline-none">
                    <span class="font-bold text-slate-900 text-lg">Are your listings exclusive?</span>
                    <i class="ph text-xl text-slate-400 transition-transform"
                        :class="expanded === 3 ? 'ph-minus' : 'ph-plus'"></i>
                </button>
                <div x-show="expanded === 3" x-collapse style="display: none;">
                    <div class="p-6 pt-0 text-slate-600 bg-white border-t border-slate-100">
                        While we showcase many properties on the open market, FastDeal is renowned for our large
                        portfolio of off-market, entirely private exclusive listings (Pocket Listings). Contact an agent
                        directly to gain access to these private portfolios.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>