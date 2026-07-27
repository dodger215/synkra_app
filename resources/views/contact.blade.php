<x-layouts.landing title="Contact Us - {{ config('app.name', 'flowexa') }}">
    <main class="py-32 px-4 bg-background">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h1 class="text-5xl font-extrabold mb-4 text-headings">Contact <span class="text-brand-primary">Us</span></h1>
                <p class="text-text-secondary max-w-2xl mx-auto">We're here to answer any questions you may have about our platform.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <div data-aos="fade-right">
                    <h2 class="text-2xl font-bold mb-8 text-headings">Reach Out Directly</h2>
                    <div class="space-y-8">
                        <div class="flex items-start space-x-6">
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0 shadow-sm border border-primary/20">
                                <i class="fa-solid fa-location-dot text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-headings">Our Headquarters</h4>
                                <p class="text-text-secondary mt-1">123 Tech Avenue, Suite 400<br>San Francisco, CA 94105</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-6">
                            <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary shrink-0 shadow-sm border border-secondary/20">
                                <i class="fa-solid fa-envelope-open-text text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-headings">Email Support</h4>
                                <p class="text-text-secondary mt-1">General: info@flowexa.io<br>Technical: support@flowexa.io</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-6">
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0 shadow-sm border border-primary/20">
                                <i class="fa-solid fa-phone-volume text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-headings">Call Us</h4>
                                <p class="text-text-secondary mt-1">Mon-Fri from 9am to 6pm PST<br>+1 (555) 000-0000</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modern-form-card" data-aos="fade-left">
                    <form action="#" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold mb-3 text-headings">First Name</label>
                                <input type="text" class="modern-input" placeholder="Jane">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-3 text-headings">Last Name</label>
                                <input type="text" class="modern-input" placeholder="Smith">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-3 text-headings">Email Address</label>
                            <input type="email" class="modern-input" placeholder="jane@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-3 text-headings">Message</label>
                            <textarea class="modern-input h-40 resize-none" placeholder="Tell us more about your needs..."></textarea>
                        </div>
                        <button type="submit" class="modern-button w-full">
                            <span>Send Message</span>
                            <span class="button-arrow">→</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-layouts.landing>
