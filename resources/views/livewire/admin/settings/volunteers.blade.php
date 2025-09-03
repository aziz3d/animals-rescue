<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    // Application Settings
    public $volunteers_auto_approve = false;
    public $volunteers_min_age = '';
    public $volunteers_background_check = false;
    public $volunteers_email_notifications = false;
    public $volunteers_application_expiry = '';
    
    // Page Content Settings
    public $volunteers_hero_title = '';
    public $volunteers_hero_description = '';
    public $volunteers_opportunities_title = '';
    
    // Opportunity Cards (4 cards)
    public $opportunity_1_title = '';
    public $opportunity_1_description = '';
    public $opportunity_1_tasks = '';
    public $opportunity_2_title = '';
    public $opportunity_2_description = '';
    public $opportunity_2_tasks = '';
    public $opportunity_3_title = '';
    public $opportunity_3_description = '';
    public $opportunity_3_tasks = '';
    public $opportunity_4_title = '';
    public $opportunity_4_description = '';
    public $opportunity_4_tasks = '';
    
    // Ready to Make a Difference Section
    public $volunteers_cta_title = '';
    public $volunteers_cta_description = '';
    public $volunteers_cta_button_text = '';
    
    // What Happens Next Section
    public $volunteers_next_title = '';
    public $volunteers_next_step1_title = '';
    public $volunteers_next_step1_description = '';
    public $volunteers_next_step2_title = '';
    public $volunteers_next_step2_description = '';
    public $volunteers_next_step3_title = '';
    public $volunteers_next_step3_description = '';

    public function mount()
    {
        // Application Settings
        $this->volunteers_auto_approve = (bool) Setting::get('volunteers_auto_approve', '0');
        $this->volunteers_min_age = Setting::get('volunteers_min_age', '18');
        $this->volunteers_background_check = (bool) Setting::get('volunteers_background_check', '1');
        $this->volunteers_email_notifications = (bool) Setting::get('volunteers_email_notifications', '1');
        $this->volunteers_application_expiry = Setting::get('volunteers_application_expiry', '30');
        
        // Page Content Settings
        $this->volunteers_hero_title = Setting::get('volunteers_hero_title', 'Volunteer With Us');
        $this->volunteers_hero_description = Setting::get('volunteers_hero_description', 'Join our dedicated team of volunteers and make a direct impact on the lives of rescued animals. Your time and skills can help save lives and create happy endings.');
        $this->volunteers_opportunities_title = Setting::get('volunteers_opportunities_title', 'Volunteer Opportunities');
        
        // Opportunity Cards
        $this->opportunity_1_title = Setting::get('opportunity_1_title', 'Animal Care');
        $this->opportunity_1_description = Setting::get('opportunity_1_description', 'Help with daily care including feeding, cleaning, grooming, and providing companionship to our rescued animals.');
        $this->opportunity_1_tasks = Setting::get('opportunity_1_tasks', "• Feeding and watering animals\n• Cleaning kennels and living spaces\n• Basic grooming and exercise\n• Socialization and enrichment activities");
        
        $this->opportunity_2_title = Setting::get('opportunity_2_title', 'Adoption Support');
        $this->opportunity_2_description = Setting::get('opportunity_2_description', 'Assist potential adopters by answering questions, facilitating meet-and-greets, and helping with the adoption process.');
        $this->opportunity_2_tasks = Setting::get('opportunity_2_tasks', "• Meet with potential adopters\n• Conduct adoption interviews\n• Organize adoption events\n• Follow up with new families");
        
        $this->opportunity_3_title = Setting::get('opportunity_3_title', 'Administrative');
        $this->opportunity_3_description = Setting::get('opportunity_3_description', 'Support our operations with office work, data entry, fundraising assistance, and event coordination.');
        $this->opportunity_3_tasks = Setting::get('opportunity_3_tasks', "• Data entry and record keeping\n• Fundraising event support\n• Social media management\n• Grant writing and research");
        
        $this->opportunity_4_title = Setting::get('opportunity_4_title', 'Professional Skills');
        $this->opportunity_4_description = Setting::get('opportunity_4_description', 'Use your professional expertise in areas like veterinary care, photography, web design, or legal services.');
        $this->opportunity_4_tasks = Setting::get('opportunity_4_tasks', "• Veterinary and medical care\n• Photography for adoptions\n• Web design and IT support\n• Legal and financial advice");
        
        // Ready to Make a Difference Section
        $this->volunteers_cta_title = Setting::get('volunteers_cta_title', 'Ready to Make a Difference?');
        $this->volunteers_cta_description = Setting::get('volunteers_cta_description', 'Complete our volunteer application to get started. The process takes just a few minutes, and we\'ll guide you through each step.');
        $this->volunteers_cta_button_text = Setting::get('volunteers_cta_button_text', 'Apply to Volunteer');
        
        // What Happens Next Section
        $this->volunteers_next_title = Setting::get('volunteers_next_title', 'What Happens Next?');
        $this->volunteers_next_step1_title = Setting::get('volunteers_next_step1_title', 'Application Review');
        $this->volunteers_next_step1_description = Setting::get('volunteers_next_step1_description', 'We\'ll review your application and contact you within a week');
        $this->volunteers_next_step2_title = Setting::get('volunteers_next_step2_title', 'Orientation');
        $this->volunteers_next_step2_description = Setting::get('volunteers_next_step2_description', 'Attend a volunteer orientation session to learn about our procedures');
        $this->volunteers_next_step3_title = Setting::get('volunteers_next_step3_title', 'Start Helping');
        $this->volunteers_next_step3_description = Setting::get('volunteers_next_step3_description', 'Begin your volunteer work and start making a difference!');
    }

    public function save()
    {
        $this->validate([
            'volunteers_min_age' => 'required|integer|min:16|max:100',
            'volunteers_application_expiry' => 'required|integer|min:1|max:365',
            'volunteers_hero_title' => 'required|string|max:255',
            'volunteers_hero_description' => 'required|string|max:1000',
            'volunteers_opportunities_title' => 'required|string|max:255',
            'opportunity_1_title' => 'required|string|max:255',
            'opportunity_1_description' => 'required|string|max:500',
            'opportunity_2_title' => 'required|string|max:255',
            'opportunity_2_description' => 'required|string|max:500',
            'opportunity_3_title' => 'required|string|max:255',
            'opportunity_3_description' => 'required|string|max:500',
            'opportunity_4_title' => 'required|string|max:255',
            'opportunity_4_description' => 'required|string|max:500',
            'volunteers_cta_title' => 'required|string|max:255',
            'volunteers_cta_description' => 'required|string|max:500',
            'volunteers_cta_button_text' => 'required|string|max:100',
            'volunteers_next_title' => 'required|string|max:255',
            'volunteers_next_step1_title' => 'required|string|max:255',
            'volunteers_next_step1_description' => 'required|string|max:255',
            'volunteers_next_step2_title' => 'required|string|max:255',
            'volunteers_next_step2_description' => 'required|string|max:255',
            'volunteers_next_step3_title' => 'required|string|max:255',
            'volunteers_next_step3_description' => 'required|string|max:255',
        ]);

        // Application Settings
        Setting::set('volunteers_auto_approve', $this->volunteers_auto_approve ? '1' : '0', 'boolean', 'volunteers');
        Setting::set('volunteers_min_age', $this->volunteers_min_age, 'number', 'volunteers');
        Setting::set('volunteers_background_check', $this->volunteers_background_check ? '1' : '0', 'boolean', 'volunteers');
        Setting::set('volunteers_email_notifications', $this->volunteers_email_notifications ? '1' : '0', 'boolean', 'volunteers');
        Setting::set('volunteers_application_expiry', $this->volunteers_application_expiry, 'number', 'volunteers');
        
        // Page Content Settings
        Setting::set('volunteers_hero_title', $this->volunteers_hero_title, 'text', 'volunteers');
        Setting::set('volunteers_hero_description', $this->volunteers_hero_description, 'textarea', 'volunteers');
        Setting::set('volunteers_opportunities_title', $this->volunteers_opportunities_title, 'text', 'volunteers');
        
        // Opportunity Cards
        Setting::set('opportunity_1_title', $this->opportunity_1_title, 'text', 'volunteers');
        Setting::set('opportunity_1_description', $this->opportunity_1_description, 'textarea', 'volunteers');
        Setting::set('opportunity_1_tasks', $this->opportunity_1_tasks, 'textarea', 'volunteers');
        Setting::set('opportunity_2_title', $this->opportunity_2_title, 'text', 'volunteers');
        Setting::set('opportunity_2_description', $this->opportunity_2_description, 'textarea', 'volunteers');
        Setting::set('opportunity_2_tasks', $this->opportunity_2_tasks, 'textarea', 'volunteers');
        Setting::set('opportunity_3_title', $this->opportunity_3_title, 'text', 'volunteers');
        Setting::set('opportunity_3_description', $this->opportunity_3_description, 'textarea', 'volunteers');
        Setting::set('opportunity_3_tasks', $this->opportunity_3_tasks, 'textarea', 'volunteers');
        Setting::set('opportunity_4_title', $this->opportunity_4_title, 'text', 'volunteers');
        Setting::set('opportunity_4_description', $this->opportunity_4_description, 'textarea', 'volunteers');
        Setting::set('opportunity_4_tasks', $this->opportunity_4_tasks, 'textarea', 'volunteers');
        
        // Ready to Make a Difference Section
        Setting::set('volunteers_cta_title', $this->volunteers_cta_title, 'text', 'volunteers');
        Setting::set('volunteers_cta_description', $this->volunteers_cta_description, 'textarea', 'volunteers');
        Setting::set('volunteers_cta_button_text', $this->volunteers_cta_button_text, 'text', 'volunteers');
        
        // What Happens Next Section
        Setting::set('volunteers_next_title', $this->volunteers_next_title, 'text', 'volunteers');
        Setting::set('volunteers_next_step1_title', $this->volunteers_next_step1_title, 'text', 'volunteers');
        Setting::set('volunteers_next_step1_description', $this->volunteers_next_step1_description, 'text', 'volunteers');
        Setting::set('volunteers_next_step2_title', $this->volunteers_next_step2_title, 'text', 'volunteers');
        Setting::set('volunteers_next_step2_description', $this->volunteers_next_step2_description, 'text', 'volunteers');
        Setting::set('volunteers_next_step3_title', $this->volunteers_next_step3_title, 'text', 'volunteers');
        Setting::set('volunteers_next_step3_description', $this->volunteers_next_step3_description, 'text', 'volunteers');

        Setting::clearCache();
        
        $this->dispatch('saved');
        session()->flash('message', 'Volunteers settings saved successfully!');
    }
    
    public function toJSON()
    {
        return json_encode([
            'component' => 'admin.settings.volunteers',
            'id' => $this->getId()
        ]);
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Volunteers Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure volunteer page content and application management</p>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    
    <form wire:submit="save" class="space-y-8">
        <!-- Page Content Settings -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Page Content Settings</h2>
            
            <!-- Hero Section -->
            <div class="space-y-6 mb-8">
                <h3 class="text-md font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-zinc-700 pb-2">Hero Section</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Title</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Main title displayed at the top of the volunteer page</p>
                    <input type="text" wire:model="volunteers_hero_title" placeholder="Volunteer With Us" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('volunteers_hero_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hero Description</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Description text below the main title</p>
                    <textarea wire:model="volunteers_hero_description" rows="3" placeholder="Join our dedicated team of volunteers..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('volunteers_hero_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Volunteer Opportunities Section -->
            <div class="space-y-6 mb-8">
                <h3 class="text-md font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-zinc-700 pb-2">Volunteer Opportunities Section</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Opportunities Section Title</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Title for the volunteer opportunities section</p>
                    <input type="text" wire:model="volunteers_opportunities_title" placeholder="Volunteer Opportunities" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('volunteers_opportunities_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Opportunity Cards -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Volunteer Opportunity Cards (4 Cards)</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Opportunity 1 -->
                <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                    <h4 class="font-medium text-gray-900 dark:text-white">Opportunity Card 1</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" wire:model="opportunity_1_title" placeholder="Animal Care" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('opportunity_1_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea wire:model="opportunity_1_description" rows="3" placeholder="Help with daily care..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('opportunity_1_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tasks (one per line)</label>
                        <textarea wire:model="opportunity_1_tasks" rows="4" placeholder="• Feeding and watering animals&#10;• Cleaning kennels..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <!-- Opportunity 2 -->
                <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                    <h4 class="font-medium text-gray-900 dark:text-white">Opportunity Card 2</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" wire:model="opportunity_2_title" placeholder="Adoption Support" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('opportunity_2_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea wire:model="opportunity_2_description" rows="3" placeholder="Assist potential adopters..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('opportunity_2_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tasks (one per line)</label>
                        <textarea wire:model="opportunity_2_tasks" rows="4" placeholder="• Meet with potential adopters&#10;• Conduct interviews..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <!-- Opportunity 3 -->
                <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                    <h4 class="font-medium text-gray-900 dark:text-white">Opportunity Card 3</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" wire:model="opportunity_3_title" placeholder="Administrative" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('opportunity_3_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea wire:model="opportunity_3_description" rows="3" placeholder="Support our operations..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('opportunity_3_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tasks (one per line)</label>
                        <textarea wire:model="opportunity_3_tasks" rows="4" placeholder="• Data entry and record keeping&#10;• Fundraising support..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <!-- Opportunity 4 -->
                <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                    <h4 class="font-medium text-gray-900 dark:text-white">Opportunity Card 4</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" wire:model="opportunity_4_title" placeholder="Professional Skills" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('opportunity_4_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea wire:model="opportunity_4_description" rows="3" placeholder="Use your professional expertise..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('opportunity_4_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tasks (one per line)</label>
                        <textarea wire:model="opportunity_4_tasks" rows="4" placeholder="• Veterinary and medical care&#10;• Photography for adoptions..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Ready to Make a Difference Section</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CTA Title</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Title for the call-to-action section</p>
                    <input type="text" wire:model="volunteers_cta_title" placeholder="Ready to Make a Difference?" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('volunteers_cta_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CTA Description</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Description text for the call-to-action section</p>
                    <textarea wire:model="volunteers_cta_description" rows="3" placeholder="Complete our volunteer application..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('volunteers_cta_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Button Text</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Text displayed on the application button</p>
                    <input type="text" wire:model="volunteers_cta_button_text" placeholder="Apply to Volunteer" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('volunteers_cta_button_text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- What Happens Next Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">What Happens Next Section</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section Title</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Title for the "What Happens Next" section</p>
                    <input type="text" wire:model="volunteers_next_title" placeholder="What Happens Next?" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('volunteers_next_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Step 1</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Step 1 Title</label>
                            <input type="text" wire:model="volunteers_next_step1_title" placeholder="Application Review" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('volunteers_next_step1_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Step 1 Description</label>
                            <textarea wire:model="volunteers_next_step1_description" rows="2" placeholder="We'll review your application..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('volunteers_next_step1_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Step 2</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Step 2 Title</label>
                            <input type="text" wire:model="volunteers_next_step2_title" placeholder="Orientation" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('volunteers_next_step2_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Step 2 Description</label>
                            <textarea wire:model="volunteers_next_step2_description" rows="2" placeholder="Attend a volunteer orientation..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('volunteers_next_step2_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Step 3</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Step 3 Title</label>
                            <input type="text" wire:model="volunteers_next_step3_title" placeholder="Start Helping" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('volunteers_next_step3_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Step 3 Description</label>
                            <textarea wire:model="volunteers_next_step3_description" rows="2" placeholder="Begin your volunteer work..." class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('volunteers_next_step3_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Configuration -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Application Configuration</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minimum Age Requirement</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Minimum age required for volunteer applications</p>
                    <input type="number" wire:model="volunteers_min_age" placeholder="18" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('volunteers_min_age')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Expiry (days)</label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Days after which pending applications expire</p>
                    <input type="number" wire:model="volunteers_application_expiry" placeholder="30" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('volunteers_application_expiry')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Auto-approve Applications</label>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Automatically approve volunteer applications</p>
                    </div>
                    <input type="checkbox" wire:model="volunteers_auto_approve" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Background Check Required</label>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Require background checks for volunteers</p>
                    </div>
                    <input type="checkbox" wire:model="volunteers_background_check" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Notifications</label>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Send email notifications for new applications</p>
                    </div>
                    <input type="checkbox" wire:model="volunteers_email_notifications" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-lg transition-colors duration-200">
                Save All Settings
            </button>
        </div>
    </form>
</div>