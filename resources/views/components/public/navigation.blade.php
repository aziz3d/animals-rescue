@php
    $navigation = [
        ['name' => 'Home', 'route' => 'home'],
        ['name' => 'Animals', 'route' => 'animals.index'],
        ['name' => 'Stories', 'route' => 'stories.index'],
        ['name' => 'Donate', 'route' => 'donate'],
        ['name' => 'Volunteer', 'route' => 'volunteer'],
        ['name' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<nav class="bg-white shadow-lg border-b-2 border-amber-200" x-data="{ mobileMenuOpen: false }" role="navigation" aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 focus-visible" aria-label="{{ setting('site_name', 'Lovely Paws Rescue') }} - Home">
                    @if(setting_asset('site_logo'))
                        <img src="{{ setting_asset('site_logo') }}" alt="{{ setting('site_name', 'Lovely Paws Rescue') }}" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center" aria-hidden="true">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                    <span class="text-xl font-bold text-amber-800">{{ setting('site_name', 'Lovely Paws Rescue') }}</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8" role="menubar">
                @foreach($navigation as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="text-amber-700 hover:text-amber-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 focus-visible {{ request()->routeIs($item['route']) ? 'bg-amber-100 text-amber-900' : '' }}"
                       role="menuitem"
                       @if(request()->routeIs($item['route'])) aria-current="page" @endif>
                        {{ $item['name'] }}
                    </a>
                @endforeach
                
                <!-- Admin Link (if authenticated) -->
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 focus-visible"
                       role="menuitem">
                        Dashboard
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="text-amber-700 hover:text-amber-900 focus-visible p-2 touch-target"
                        :aria-expanded="mobileMenuOpen"
                        aria-controls="mobile-menu"
                        aria-label="Toggle mobile menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="md:hidden bg-white border-t border-amber-200"
         id="mobile-menu"
         role="menu"
         aria-labelledby="mobile-menu-button">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @foreach($navigation as $item)
                <a href="{{ route($item['route']) }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-amber-700 hover:text-amber-900 hover:bg-amber-50 transition-colors duration-200 focus-visible touch-target {{ request()->routeIs($item['route']) ? 'bg-amber-100 text-amber-900' : '' }}"
                   role="menuitem"
                   @if(request()->routeIs($item['route'])) aria-current="page" @endif
                   @click="mobileMenuOpen = false">
                    {{ $item['name'] }}
                </a>
            @endforeach
            
            <!-- Admin Link (if authenticated) -->
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium bg-amber-600 text-white hover:bg-amber-700 transition-colors duration-200 focus-visible touch-target"
                   role="menuitem"
                   @click="mobileMenuOpen = false">
                    Dashboard
                </a>
            @endauth
        </div>
    </div>
</nav>