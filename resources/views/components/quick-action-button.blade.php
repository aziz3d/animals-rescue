@props([
    'href',
    'icon',
    'title',
    'description' => null,
    'variant' => 'outline'
])

<flux:button href="{{ $href }}" variant="{{ $variant }}" class="justify-start h-auto p-4 text-left">
    <div class="flex items-start">
        <flux:icon name="{{ $icon }}" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" />
        <div>
            <div class="font-medium">{{ $title }}</div>
            @if($description)
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $description }}</p>
            @endif
        </div>
    </div>
</flux:button>