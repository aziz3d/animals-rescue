@props([
    'title',
    'value',
    'subtitle' => null,
    'icon',
    'color' => 'blue',
    'href' => null,
    'badge' => null
])

<div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <div class="p-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900 rounded-lg">
            <flux:icon name="{{ $icon }}" class="w-6 h-6 text-{{ $color }}-600 dark:text-{{ $color }}-400" />
        </div>
        @if($href)
            <flux:button href="{{ $href }}" variant="ghost" size="sm">
                <flux:icon.arrow-right class="w-4 h-4" />
            </flux:button>
        @endif
    </div>
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        <p class="text-2xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $value }}</p>
        @if($subtitle)
            <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                {{ $subtitle }}
            </div>
        @endif
        @if($badge)
            <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                {{ $badge }}
            </div>
        @endif
    </div>
</div>