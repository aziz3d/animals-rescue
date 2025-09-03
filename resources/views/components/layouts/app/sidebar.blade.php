<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @livewireStyles
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <div class="flex items-center space-x-3">
                    @php
                        $logoPath = setting('site_logo');
                    @endphp
                    @if($logoPath)
                        @php
                            $logoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath);
                            // Fallback to asset URL if Storage URL fails
                            if (!filter_var($logoUrl, FILTER_VALIDATE_URL)) {
                                $logoUrl = asset('uploads/' . $logoPath);
                            }
                        @endphp
                        <img src="{{ $logoUrl }}" alt="{{ setting('site_name', 'Lovely Paws') }}" class="w-8 h-8 object-contain rounded-lg"
                             onerror="this.src='{{ asset('uploads/' . $logoPath) }}'; this.onerror=null;">
                    @else
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center">
                            <flux:icon.heart class="w-5 h-5 text-white" />
                        </div>
                    @endif
                    <div>
                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ setting('site_name', 'Lovely Paws') }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Admin</div>
                    </div>
                </div>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Management')" class="grid">
                    <flux:navlist.item icon="heart" :href="route('admin.animals.index')" :current="request()->routeIs('admin.animals.*')" wire:navigate>{{ __('Animals') }}</flux:navlist.item>
                    <flux:navlist.item icon="document-text" :href="route('admin.stories.index')" :current="request()->routeIs('admin.stories.*')" wire:navigate>{{ __('Stories') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('admin.volunteers.index')" :current="request()->routeIs('admin.volunteers.*')" wire:navigate>{{ __('Volunteers') }}</flux:navlist.item>
                    <flux:navlist.item icon="envelope" :href="route('admin.contacts.index')" :current="request()->routeIs('admin.contacts.*')" wire:navigate>{{ __('Contact Messages') }}</flux:navlist.item>
                    <flux:navlist.item icon="chat-bubble-left-right" :href="route('admin.comments.index')" :current="request()->routeIs('admin.comments.*')" wire:navigate>{{ __('Comments') }}</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Settings')" class="grid">
                    <flux:navlist.item icon="cog" :href="route('admin.settings.general')" :current="request()->routeIs('admin.settings.general')" wire:navigate>{{ __('General Settings') }}</flux:navlist.item>

                    <flux:navlist.item icon="heart" :href="route('admin.settings.animals')" :current="request()->routeIs('admin.settings.animals')" wire:navigate>{{ __('Animals Settings') }}</flux:navlist.item>
                    <flux:navlist.item icon="book-open" :href="route('admin.settings.stories')" :current="request()->routeIs('admin.settings.stories')" wire:navigate>{{ __('Stories Settings') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('admin.settings.volunteers')" :current="request()->routeIs('admin.settings.volunteers')" wire:navigate>{{ __('Volunteers Settings') }}</flux:navlist.item>
                    <flux:navlist.item icon="gift" :href="route('admin.settings.donate')" :current="request()->routeIs('admin.settings.donate')" wire:navigate>{{ __('Donate Settings') }}</flux:navlist.item>
                    <flux:navlist.item icon="credit-card" :href="route('admin.settings.payment')" :current="request()->routeIs('admin.settings.payment')" wire:navigate>{{ __('Payment Settings') }}</flux:navlist.item>
                    <flux:navlist.item icon="phone" :href="route('admin.settings.contact')" :current="request()->routeIs('admin.settings.contact')" wire:navigate>{{ __('Contact Page Settings') }}</flux:navlist.item>
                    <flux:navlist.item icon="document" :href="route('admin.settings.privacy-policy')" :current="request()->routeIs('admin.settings.privacy-policy')" wire:navigate>{{ __('Privacy Policy') }}</flux:navlist.item>
                    <flux:navlist.item icon="document-text" :href="route('admin.settings.terms-of-service')" :current="request()->routeIs('admin.settings.terms-of-service')" wire:navigate>{{ __('Terms of Service') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            @auth
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            @endauth
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @auth
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            @endauth
        </flux:header>

        {{ $slot }}

        @fluxScripts
        @livewireScripts
    </body>
</html>
