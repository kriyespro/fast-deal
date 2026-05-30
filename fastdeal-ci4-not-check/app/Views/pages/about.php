<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>About Us | FastDeal Properties
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="relative bg-primary pt-32 pb-32 overflow-hidden">
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
    </div>
    <div
        class="absolute top-0 right-0 w-[800px] h-[800px] bg-accent rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl opacity-10">
    </div>
    <div
        class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-red rounded-full translate-y-1/3 -translate-x-1/4 blur-3xl opacity-10">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="w-full lg:w-1/2">
                <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-accent text-sm font-bold tracking-widest uppercase mb-6 border border-white/20 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-accent"></span> Our Story
                </span>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">Redefining <br /><span
                        class="text-accent">Luxury Real Estate</span></h1>
                <p class="text-slate-300 text-lg md:text-xl mb-8 leading-relaxed max-w-xl">
                    For over two decades, FastDeal has been the trusted partner for discerning clients seeking
                    extraordinary properties around the globe.
                </p>
                <div class="flex gap-4">
                    <a href="#leadership"
                        class="bg-red hover:bg-[#a0211b] text-white px-8 py-3.5 rounded-full font-bold transition-all transform hover:scale-105 shadow-lg shadow-red/20">
                        Meet the Team
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                        alt="Luxury Home"
                        class="rounded-2xl w-full h-64 object-cover mt-12 shadow-2xl border-4 border-white/10">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                        alt="Modern Building"
                        class="rounded-2xl w-full h-64 object-cover shadow-2xl border-4 border-white/10">
                </div>
                <!-- Floating Stats Badge -->
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl z-20 shadow-2xl text-center min-w-[200px]">
                    <div class="text-4xl font-bold text-white mb-1">25<span class="text-accent">+</span></div>
                    <div class="text-slate-200 text-sm font-medium uppercase tracking-wider">Years Experience</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mission & Vision -->
<section class="py-24 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-16 items-center">

            <div class="w-full md:w-1/2 relative">
                <div class="absolute -inset-4 bg-slate-100 rounded-3xl -z-10 transform -rotate-3"></div>
                <img src="https://images.unsplash.com/photo-1575517111478-7f6afd0973db?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                    alt="Architecture" class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3]">

                <!-- Experience Badge -->
                <div
                    class="absolute -bottom-8 -right-8 bg-primary text-white p-8 rounded-2xl shadow-2xl shadow-primary/30 max-w-[250px] border border-primary-light hidden md:block group hover:-translate-y-2 transition-transform">
                    <i class="ph-fill ph-check-circle text-4xl text-accent mb-3"></i>
                    <div class="text-2xl font-bold mb-1 group-hover:text-accent transition-colors">₹10B+</div>
                    <div class="text-slate-300 text-sm">In Career Sales Volume Globally</div>
                </div>
            </div>

            <div class="w-full md:w-1/2">
                <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-6">Our Mission is to Elevate Your Lifestyle
                </h2>
                <div class="prose prose-lg text-slate-600 mb-8">
                    <p>We believe that luxury is not just a price point; it's an experience. FastDeal was founded on the
                        principle that buying or selling a premium property should be as seamless, refined, and
                        exquisite as the homes themselves.</p>
                    <p>Our expansive global network, deep market intelligence, and uncompromising discretion allow us to
                        connect exceptional people with exceptional properties.</p>
                </div>

                <div class="space-y-6">
                    <div
                        class="flex gap-4 p-4 rounded-xl border border-slate-100 hover:border-red/30 hover:shadow-md transition-all bg-white group hover:bg-red/5">
                        <div
                            class="w-12 h-12 rounded-full bg-red/10 flex items-center justify-center text-red shrink-0 group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-handshake text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">Unmatched Integrity</h4>
                            <p class="text-slate-600 text-sm">Honesty, transparency, and discretion are the cornerstones
                                of every transaction we manage.</p>
                        </div>
                    </div>
                    <div
                        class="flex gap-4 p-4 rounded-xl border border-slate-100 hover:border-red/30 hover:shadow-md transition-all bg-white group hover:bg-red/5">
                        <div
                            class="w-12 h-12 rounded-full bg-red/10 flex items-center justify-center text-red shrink-0 group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-globe-hemisphere-west text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">Global Reach</h4>
                            <p class="text-slate-600 text-sm">Our extensive international network connects your property
                                with qualified buyers worldwide.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Metrics Section -->
<section class="py-20 bg-primary relative overflow-hidden border-y border-white/10">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">

            <div class="text-center group">
                <div
                    class="w-16 h-16 mx-auto bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:-translate-y-2 transition-all duration-300">
                    <i class="ph-fill ph-house-line text-3xl text-accent group-hover:text-primary"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 font-outfit">10k+</div>
                <div class="text-slate-400 font-medium tracking-wide uppercase text-sm">Properties Sold</div>
            </div>

            <div class="text-center group">
                <div
                    class="w-16 h-16 mx-auto bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:-translate-y-2 transition-all duration-300">
                    <i class="ph-fill ph-users text-3xl text-accent group-hover:text-primary"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 font-outfit">150+</div>
                <div class="text-slate-400 font-medium tracking-wide uppercase text-sm">Expert Agents</div>
            </div>

            <div class="text-center group">
                <div
                    class="w-16 h-16 mx-auto bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:-translate-y-2 transition-all duration-300">
                    <i class="ph-fill ph-map-pin text-3xl text-accent group-hover:text-primary"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 font-outfit">45</div>
                <div class="text-slate-400 font-medium tracking-wide uppercase text-sm">Global Offices</div>
            </div>

            <div class="text-center group">
                <div
                    class="w-16 h-16 mx-auto bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:-translate-y-2 transition-all duration-300">
                    <i class="ph-fill ph-star text-3xl text-accent group-hover:text-primary"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold text-white mb-2 font-outfit">4.9/5</div>
                <div class="text-slate-400 font-medium tracking-wide uppercase text-sm">Client Satisfaction</div>
            </div>

        </div>
    </div>
</section>

<!-- Leadership Team -->
<section id="leadership" class="py-24 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span
                class="inline-block px-3 py-1 rounded-full bg-red/10 text-red text-sm font-bold tracking-wider mb-4 uppercase">The
                Visionaries</span>
            <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-4">Our Leadership Team</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">Meet the minds driving innovation and excellence in the
                luxury real estate market.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Team Member 1 -->
            <div
                class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all group">
                <div class="relative h-80 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Richard Sterling"
                        class="w-full h-full object-cover object-top filter grayscale group-hover:grayscale-0 transition-all duration-500 transform group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-primary/90 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-6">
                        <div
                            class="flex gap-4 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-accent flex items-center justify-center text-white backdrop-blur-sm transition-colors">
                                <i class="ph-fill ph-linkedin-logo text-xl"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-accent flex items-center justify-center text-white backdrop-blur-sm transition-colors">
                                <i class="ph-fill ph-twitter-logo text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-8 text-center bg-white relative">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1 group-hover:text-red transition-colors">Richard
                        Sterling</h3>
                    <p class="text-accent font-medium mb-4">Founder & CEO</p>
                    <p class="text-slate-500 text-sm">Pioneered the boutique luxury brokerage model over 25 years ago.
                        Visionary leader focused on global expansion.</p>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div
                class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all group">
                <div class="relative h-80 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Elena Rodriguez"
                        class="w-full h-full object-cover object-top filter grayscale group-hover:grayscale-0 transition-all duration-500 transform group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-primary/90 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-6">
                        <div
                            class="flex gap-4 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-accent flex items-center justify-center text-white backdrop-blur-sm transition-colors">
                                <i class="ph-fill ph-linkedin-logo text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-8 text-center bg-white relative">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1 group-hover:text-red transition-colors">Elena
                        Rodriguez</h3>
                    <p class="text-accent font-medium mb-4">Chief Operations Officer</p>
                    <p class="text-slate-500 text-sm">Ensures seamless operations across our 45 global offices. Expert
                        in scaling boutique business models.</p>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div
                class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all group lg:col-start-auto md:col-start-1 md:col-span-2 lg:col-span-1 md:max-w-md md:mx-auto lg:max-w-full lg:mx-0">
                <div class="relative h-80 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Marcus Chen"
                        class="w-full h-full object-cover object-top filter grayscale group-hover:grayscale-0 transition-all duration-500 transform group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-primary/90 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-6">
                        <div
                            class="flex gap-4 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-accent flex items-center justify-center text-white backdrop-blur-sm transition-colors">
                                <i class="ph-fill ph-linkedin-logo text-xl"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-accent flex items-center justify-center text-white backdrop-blur-sm transition-colors">
                                <i class="ph-fill ph-twitter-logo text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-8 text-center bg-white relative">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1 group-hover:text-red transition-colors">Marcus
                        Chen</h3>
                    <p class="text-accent font-medium mb-4">Head of Global Sales</p>
                    <p class="text-slate-500 text-sm">Driven by market data and exceptional relationship-building.
                        Oversees international market acquisitions.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Office Locations CTA -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-primary rounded-3xl overflow-hidden relative shadow-2xl">
            <!-- Background Map Image -->
            <div class="absolute inset-0 opacity-20 filter invert">
                <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                    alt="World Map" class="w-full h-full object-cover mix-blend-screen grayscale">
            </div>

            <div class="relative z-10 p-12 md:p-20 text-center">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">Global Presence, Local Expertise</h2>
                <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto mb-10">
                    With offices in major metropolitan areas around the world, our agents are ready to assist you
                    wherever you are looking to buy or sell.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="<?= base_url('contact') ?>"
                        class="bg-red hover:bg-[#a0211b] text-white px-8 py-3.5 rounded-full font-bold transition-all transform hover:scale-105 shadow-lg shadow-red/20 inline-flex items-center justify-center gap-2">
                        Find an Office Near You
                    </a>
                    <a href="<?= base_url('agents') ?>"
                        class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 px-8 py-3.5 rounded-full font-bold transition-colors inline-flex items-center justify-center gap-2">
                        Browse Agents
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>