@php
    use App\Models\Setting;
    
    $footerSettings = [
        'organization_name' => setting('footer_organization_name', 'Lovely Paws Rescue'),
        'mission_statement' => setting('footer_mission_statement', 'Dedicated to rescuing, rehabilitating, and rehoming animals in need. Every paw deserves love, care, and a forever home.'),
        'address' => setting('footer_address', '123 Rescue Lane, Pet City, PC 12345'),
        'phone' => setting('footer_phone', '(555) 123-PAWS'),
        'email' => setting('footer_email', 'info@lovelypawsrescue.org'),
        'facebook_url' => setting('footer_facebook_url', '#'),
        'twitter_url' => setting('footer_twitter_url', '#'),
        'instagram_url' => setting('footer_instagram_url', '#'),
        'privacy_policy_url' => setting('footer_privacy_policy_url', route('privacy-policy')),
        'terms_of_service_url' => setting('footer_terms_of_service_url', route('terms-of-service')),
        'show_privacy_policy' => (bool) setting('footer_show_privacy_policy', true),
        'show_terms_of_service' => (bool) setting('footer_show_terms_of_service', true),
        'copyright_text' => setting('footer_copyright_text', 'All rights reserved.'),
    ];
    
    // Use default routes if URLs are empty or just '#'
    $privacyPolicyUrl = (!empty($footerSettings['privacy_policy_url']) && $footerSettings['privacy_policy_url'] !== '#') 
        ? $footerSettings['privacy_policy_url'] 
        : route('privacy-policy');
        
    $termsOfServiceUrl = (!empty($footerSettings['terms_of_service_url']) && $footerSettings['terms_of_service_url'] !== '#') 
        ? $footerSettings['terms_of_service_url'] 
        : route('terms-of-service');
@endphp

<footer class="bg-amber-800 text-amber-100" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <!-- Logo and Mission -->
            <div class="col-span-1 sm:col-span-2">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center" aria-hidden="true">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold">{{ $footerSettings['organization_name'] }}</span>
                </div>
                <p class="text-amber-200 mb-4 max-w-md text-sm sm:text-base">
                    {{ $footerSettings['mission_statement'] }}
                </p>
                <div class="flex space-x-4" role="list" aria-label="Social media links">
                    <!-- Social Media Links -->
                    @if($footerSettings['twitter_url'] && $footerSettings['twitter_url'] !== '#')
                        <a href="{{ $footerSettings['twitter_url'] }}" 
                           class="text-amber-300 hover:text-white transition-colors duration-200 focus-visible touch-target"
                           aria-label="Follow us on Twitter"
                           role="listitem"
                           target="_blank"
                           rel="noopener noreferrer">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                    @endif
                    @if($footerSettings['facebook_url'] && $footerSettings['facebook_url'] !== '#')
                        <a href="{{ $footerSettings['facebook_url'] }}" 
                           class="text-amber-300 hover:text-white transition-colors duration-200 focus-visible touch-target"
                           aria-label="Follow us on Facebook"
                           role="listitem"
                           target="_blank"
                           rel="noopener noreferrer">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                    @endif
                    @if($footerSettings['instagram_url'] && $footerSettings['instagram_url'] !== '#')
                        <a href="{{ $footerSettings['instagram_url'] }}" 
                           class="text-amber-300 hover:text-white transition-colors duration-200 focus-visible touch-target"
                           aria-label="Follow us on Instagram"
                           role="listitem"
                           target="_blank"
                           rel="noopener noreferrer">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z.012 0z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <nav aria-labelledby="footer-nav-heading">
                <h3 id="footer-nav-heading" class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('animals.index') }}" class="text-amber-200 hover:text-white transition-colors duration-200 focus-visible text-sm sm:text-base">Adopt a Pet</a></li>
                    <li><a href="{{ route('volunteer') }}" class="text-amber-200 hover:text-white transition-colors duration-200 focus-visible text-sm sm:text-base">Volunteer</a></li>
                    <li><a href="{{ route('donate') }}" class="text-amber-200 hover:text-white transition-colors duration-200 focus-visible text-sm sm:text-base">Donate</a></li>
                    <li><a href="{{ route('stories.index') }}" class="text-amber-200 hover:text-white transition-colors duration-200 focus-visible text-sm sm:text-base">Success Stories</a></li>
                </ul>
            </nav>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                <address class="space-y-2 text-amber-200 not-italic text-sm sm:text-base">
                    <p class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $footerSettings['address'] }}</span>
                    </p>
                    <p class="flex items-center">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $footerSettings['phone']) }}" class="hover:text-white transition-colors duration-200 focus-visible">{{ $footerSettings['phone'] }}</a>
                    </p>
                    <p class="flex items-center">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <a href="mailto:{{ $footerSettings['email'] }}" class="hover:text-white transition-colors duration-200 focus-visible">{{ $footerSettings['email'] }}</a>
                    </p>
                </address>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-amber-700 mt-6 sm:mt-8 pt-6 sm:pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-amber-200 text-xs sm:text-sm text-center sm:text-left">
                © {{ date('Y') }} {{ $footerSettings['organization_name'] }}. {{ $footerSettings['copyright_text'] }}
            </p>
            <nav class="flex flex-col sm:flex-row gap-4 sm:gap-6" aria-label="Legal links">
                @if($footerSettings['show_privacy_policy'])
                    <a href="{{ $privacyPolicyUrl }}" 
                       class="text-amber-200 hover:text-white text-xs sm:text-sm transition-colors duration-200 focus-visible text-center">Privacy Policy</a>
                @endif
                @if($footerSettings['show_terms_of_service'])
                    <a href="{{ $termsOfServiceUrl }}" 
                       class="text-amber-200 hover:text-white text-xs sm:text-sm transition-colors duration-200 focus-visible text-center">Terms of Service</a>
                @endif
            </nav>
        </div>
    </div>
</footer>