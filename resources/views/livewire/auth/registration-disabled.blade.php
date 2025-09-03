<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header 
        :title="__('Registration Disabled')" 
        :description="__('We are not currently accepting new registrations. Please check back later.')" 
    />

    <div class="text-center">
        <flux:button variant="primary" :href="route('login')" wire:navigate>
            {{ __('Return to Login') }}
        </flux:button>
    </div>
</div>