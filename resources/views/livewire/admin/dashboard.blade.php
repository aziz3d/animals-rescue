<?php

use App\Services\AnalyticsService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public array $stats = [];
    public array $recentActivity = [];

    public function mount()
    {
        $analyticsService = new AnalyticsService();
        $this->stats = $analyticsService->getDashboardStats();
        $this->recentActivity = $analyticsService->getRecentActivity(8);
    }

    public function refreshStats()
    {
        $analyticsService = new AnalyticsService();
        $this->stats = $analyticsService->getDashboardStats();
        $this->recentActivity = $analyticsService->getRecentActivity(8);
        
        $this->dispatch('stats-refreshed');
    }
}; ?>

<div class="p-6">
    <!-- Header with Refresh Button -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome to Lovely Paws Rescue management center</p>
        </div>
        <flux:button wire:click="refreshStats" variant="outline" size="sm">
            <flux:icon.arrow-path class="w-4 h-4 mr-2" />
            Refresh Stats
        </flux:button>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Animals Stats -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <flux:icon.heart class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <flux:button href="{{ route('admin.animals.index') }}" variant="ghost" size="sm">
                    <flux:icon.arrow-right class="w-4 h-4" />
                </flux:button>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Animals</h3>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['animals']['total'] }}</p>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ $stats['animals']['available'] }} available • {{ $stats['animals']['adopted'] }} adopted
                </div>
                <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                    {{ $stats['animals']['adoption_rate'] }}% adoption rate
                </div>
            </div>
        </div>

        <!-- Stories Stats -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <flux:icon.document-text class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <flux:button href="{{ route('admin.stories.index') }}" variant="ghost" size="sm">
                    <flux:icon.arrow-right class="w-4 h-4" />
                </flux:button>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Stories</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['stories']['total'] }}</p>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ $stats['stories']['published'] }} published • {{ $stats['stories']['drafts'] }} drafts
                </div>
            </div>
        </div>

        <!-- Volunteers Stats -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <flux:icon.user-group class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                </div>
                <flux:button href="{{ route('admin.volunteers.index') }}" variant="ghost" size="sm">
                    <flux:icon.arrow-right class="w-4 h-4" />
                </flux:button>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Volunteers</h3>
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['volunteers']['total'] }}</p>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ $stats['volunteers']['active'] }} active • {{ $stats['volunteers']['pending'] }} pending
                </div>
            </div>
        </div>

        <!-- Page Visits Stats -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                    <flux:icon.eye class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Page Visits</h3>
                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['page_visits']['this_month'] }}</p>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ $stats['page_visits']['today'] }} today • {{ $stats['page_visits']['this_week'] }} this week
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics and Quick Actions Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Adoption Analytics -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Adoption Analytics</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['adoption_rates']['last_30_days'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Last 30 Days</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['adoption_rates']['last_90_days'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Last 90 Days</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['adoption_rates']['this_year'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">This Year</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Monthly Average: <span class="font-semibold">{{ $stats['adoption_rates']['monthly_average'] }}</span> adoptions
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <flux:button href="{{ route('admin.animals.create') }}" variant="outline" class="justify-start">
                    <flux:icon.plus class="w-4 h-4 mr-2" />
                    Add Animal
                </flux:button>
                <flux:button href="{{ route('admin.stories.create') }}" variant="outline" class="justify-start">
                    <flux:icon.document-plus class="w-4 h-4 mr-2" />
                    New Story
                </flux:button>
                <flux:button href="{{ route('admin.animals.index') }}" variant="outline" class="justify-start">
                    <flux:icon.heart class="w-4 h-4 mr-2" />
                    Manage Animals
                </flux:button>
                <flux:button href="{{ route('admin.volunteers.index') }}" variant="outline" class="justify-start">
                    <flux:icon.user-group class="w-4 h-4 mr-2" />
                    Review Volunteers
                </flux:button>
                <flux:button href="{{ route('admin.contacts.index') }}" variant="outline" class="justify-start">
                    <flux:icon.envelope class="w-4 h-4 mr-2" />
                    View Messages
                </flux:button>
                <flux:button href="{{ route('admin.stories.index') }}" variant="outline" class="justify-start">
                    <flux:icon.document-text class="w-4 h-4 mr-2" />
                    Manage Stories
                </flux:button>

            </div>
        </div>
    </div>

    <!-- Monthly Trends and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Trends -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">6-Month Trends</h2>
            <div class="space-y-4">
                @foreach(['animals_added' => 'Animals Added', 'adoptions' => 'Adoptions', 'volunteers' => 'Volunteers', 'donations' => 'Donations'] as $key => $label)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Total: {{ array_sum($stats['monthly_trends']['data'][$key]) }}
                            </span>
                        </div>
                        <div class="flex space-x-1 h-8">
                            @foreach($stats['monthly_trends']['data'][$key] as $index => $value)
                                <div class="flex-1 bg-gray-200 dark:bg-zinc-700 rounded relative group">
                                    @if($value > 0)
                                        <div class="bg-blue-500 rounded h-full transition-all duration-300" 
                                             style="height: {{ min(100, ($value / max(1, max($stats['monthly_trends']['data'][$key]))) * 100) }}%">
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-xs font-medium text-white bg-black bg-opacity-75 px-1 rounded">{{ $value }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                            @foreach($stats['monthly_trends']['months'] as $month)
                                <span>{{ substr($month, 0, 3) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h2>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($recentActivity as $activity)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                        <div class="flex items-center">
                            @php
                                $bgClass = match($activity['color']) {
                                    'blue' => 'bg-blue-100 dark:bg-blue-900',
                                    'orange' => 'bg-orange-100 dark:bg-orange-900',
                                    'purple' => 'bg-purple-100 dark:bg-purple-900',
                                    'green' => 'bg-green-100 dark:bg-green-900',
                                    default => 'bg-gray-100 dark:bg-gray-900'
                                };
                                $iconColorClass = match($activity['color']) {
                                    'blue' => 'text-blue-500',
                                    'orange' => 'text-orange-500',
                                    'purple' => 'text-purple-500',
                                    'green' => 'text-green-500',
                                    default => 'text-gray-500'
                                };
                            @endphp
                            <div class="p-2 {{ $bgClass }} rounded-lg mr-3">
                                @if($activity['icon'] === 'heart')
                                    <flux:icon.heart class="w-4 h-4 {{ $iconColorClass }}" />
                                @elseif($activity['icon'] === 'envelope')
                                    <flux:icon.envelope class="w-4 h-4 {{ $iconColorClass }}" />
                                @elseif($activity['icon'] === 'user-group')
                                    <flux:icon.user-group class="w-4 h-4 {{ $iconColorClass }}" />
                                @else
                                    <flux:icon.document-text class="w-4 h-4 {{ $iconColorClass }}" />
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['title'] }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $activity['subtitle'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                            </div>
                        </div>
                        <flux:button href="{{ $activity['url'] }}" variant="ghost" size="sm">
                            <flux:icon.arrow-right class="w-3 h-3" />
                        </flux:button>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <flux:icon.inbox class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-4" />
                        <p class="text-sm text-gray-600 dark:text-gray-400">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Donations and Popular Pages -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Donation Statistics -->
        @if(isset($stats['donations']))
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Donation Statistics</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($stats['donations']['total_amount'], 0) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Raised</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['donations']['completed'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Donations</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Average Donation:</span>
                    <span class="font-semibold">${{ number_format($stats['donations']['average_donation'], 2) }}</span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span class="text-gray-600 dark:text-gray-400">Recurring Donors:</span>
                    <span class="font-semibold">{{ $stats['donations']['recurring_donors'] }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Popular Pages -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Popular Pages (30 days)</h2>
            <div class="space-y-3">
                @forelse($stats['page_visits']['popular_pages'] as $page => $visits)
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
                            {{ str_replace('_', ' ', $page) }}
                        </span>
                        <div class="flex items-center">
                            <div class="w-20 bg-gray-200 dark:bg-zinc-700 rounded-full h-2 mr-3">
                                <div class="bg-blue-500 h-2 rounded-full" 
                                     style="width: {{ ($visits / max(1, max($stats['page_visits']['popular_pages']))) * 100 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400 w-8 text-right">{{ $visits }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center py-4">No visit data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>