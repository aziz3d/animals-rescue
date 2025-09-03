@php
use App\Models\Animal;
use App\Models\Story;
use App\Models\Volunteer;
use App\Models\Contact;

$stats = [
    'animals' => [
        'total' => Animal::count(),
        'available' => Animal::where('adoption_status', 'available')->count(),
        'pending' => Animal::where('adoption_status', 'pending')->count(),
        'adopted' => Animal::where('adoption_status', 'adopted')->count(),
    ],
    'stories' => [
        'total' => Story::count(),
        'published' => Story::whereNotNull('published_at')->count(),
        'drafts' => Story::whereNull('published_at')->count(),
    ],
    'volunteers' => [
        'total' => Volunteer::count(),
        'pending' => Volunteer::where('status', 'pending')->count(),
        'active' => Volunteer::where('status', 'active')->count(),
    ],
    'contacts' => [
        'total' => Contact::count(),
        'new' => Contact::where('status', 'new')->count(),
        'unread' => Contact::whereIn('status', ['new', 'read'])->count(),
    ],
];
@endphp

<x-layouts.app :title="__('Dashboard')">
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome to Lovely Paws Rescue management center</p>
        </div>

        <!-- Quick Stats -->
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

            <!-- Messages Stats -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <flux:icon.envelope class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                    </div>
                    <flux:button href="{{ route('admin.contacts.index') }}" variant="ghost" size="sm">
                        <flux:icon.arrow-right class="w-4 h-4" />
                    </flux:button>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Messages</h3>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['contacts']['total'] }}</p>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        {{ $stats['contacts']['new'] }} new • {{ $stats['contacts']['unread'] }} unread
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Management Actions -->
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
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h2>
                <div class="space-y-3">
                    @php
                    $recentAnimals = Animal::latest()->take(2)->get();
                    $recentContacts = Contact::latest()->take(2)->get();
                    @endphp
                    
                    @foreach($recentAnimals as $animal)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                            <div class="flex items-center">
                                <flux:icon.heart class="w-4 h-4 text-blue-500 mr-3" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $animal->name }} added</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $animal->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <flux:button href="{{ route('admin.animals.show', $animal) }}" variant="ghost" size="sm">
                                <flux:icon.arrow-right class="w-3 h-3" />
                            </flux:button>
                        </div>
                    @endforeach

                    @foreach($recentContacts as $contact)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                            <div class="flex items-center">
                                <flux:icon.envelope class="w-4 h-4 text-orange-500 mr-3" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Message from {{ $contact->name }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $contact->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <flux:button href="{{ route('admin.contacts.show', $contact) }}" variant="ghost" size="sm">
                                <flux:icon.arrow-right class="w-3 h-3" />
                            </flux:button>
                        </div>
                    @endforeach

                    @if($recentAnimals->isEmpty() && $recentContacts->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Navigation -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Administration</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <flux:button href="{{ route('admin.animals.index') }}" variant="outline" class="justify-start h-auto p-4">
                    <div class="text-left">
                        <div class="flex items-center mb-2">
                            <flux:icon.heart class="w-5 h-5 text-blue-500 mr-2" />
                            <span class="font-medium">Animals</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage animal profiles and adoption status</p>
                    </div>
                </flux:button>

                <flux:button href="{{ route('admin.stories.index') }}" variant="outline" class="justify-start h-auto p-4">
                    <div class="text-left">
                        <div class="flex items-center mb-2">
                            <flux:icon.document-text class="w-5 h-5 text-green-500 mr-2" />
                            <span class="font-medium">Stories</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Create and manage rescue stories</p>
                    </div>
                </flux:button>

                <flux:button href="{{ route('admin.volunteers.index') }}" variant="outline" class="justify-start h-auto p-4">
                    <div class="text-left">
                        <div class="flex items-center mb-2">
                            <flux:icon.user-group class="w-5 h-5 text-purple-500 mr-2" />
                            <span class="font-medium">Volunteers</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Review and manage volunteer applications</p>
                    </div>
                </flux:button>

                <flux:button href="{{ route('admin.contacts.index') }}" variant="outline" class="justify-start h-auto p-4">
                    <div class="text-left">
                        <div class="flex items-center mb-2">
                            <flux:icon.envelope class="w-5 h-5 text-orange-500 mr-2" />
                            <span class="font-medium">Messages</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Respond to visitor inquiries</p>
                    </div>
                </flux:button>
            </div>
        </div>
    </div>
</x-layouts.app>
