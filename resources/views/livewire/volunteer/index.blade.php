<?php

use App\Models\Setting;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.public')] class extends Component
{
    public $heroTitle;
    public $heroDescription;
    public $opportunitiesTitle;
    public $opportunities = [];
    public $ctaTitle;
    public $ctaDescription;
    public $ctaButtonText;
    public $nextTitle;
    public $nextSteps = [];

    public function mount()
    {
        // Load hero section
        $this->heroTitle = Setting::get('volunteers_hero_title', 'Volunteer With Us');
        $this->heroDescription = Setting::get('volunteers_hero_description', 'Join our dedicated team of volunteers and make a direct impact on the lives of rescued animals. Your time and skills can help save lives and create happy endings.');
        
        // Load opportunities section
        $this->opportunitiesTitle = Setting::get('volunteers_opportunities_title', 'Volunteer Opportunities');
        
        // Load opportunity cards
        $this->opportunities = [
            [
                'title' => Setting::get('opportunity_1_title', 'Animal Care'),
                'description' => Setting::get('opportunity_1_description', 'Help with daily care including feeding, cleaning, grooming, and providing companionship to our rescued animals.'),
                'tasks' => Setting::get('opportunity_1_tasks', "• Feeding and watering animals\n• Cleaning kennels and living spaces\n• Basic grooming and exercise\n• Socialization and enrichment activities"),
                'icon' => 'eye'
            ],
            [
                'title' => Setting::get('opportunity_2_title', 'Adoption Support'),
                'description' => Setting::get('opportunity_2_description', 'Assist potential adopters by answering questions, facilitating meet-and-greets, and helping with the adoption process.'),
                'tasks' => Setting::get('opportunity_2_tasks', "• Meet with potential adopters\n• Conduct adoption interviews\n• Organize adoption events\n• Follow up with new families"),
                'icon' => 'phone'
            ],
            [
                'title' => Setting::get('opportunity_3_title', 'Administrative'),
                'description' => Setting::get('opportunity_3_description', 'Support our operations with office work, data entry, fundraising assistance, and event coordination.'),
                'tasks' => Setting::get('opportunity_3_tasks', "• Data entry and record keeping\n• Fundraising event support\n• Social media management\n• Grant writing and research"),
                'icon' => 'clipboard'
            ],
            [
                'title' => Setting::get('opportunity_4_title', 'Professional Skills'),
                'description' => Setting::get('opportunity_4_description', 'Use your professional expertise in areas like veterinary care, photography, web design, or legal services.'),
                'tasks' => Setting::get('opportunity_4_tasks', "• Veterinary and medical care\n• Photography for adoptions\n• Web design and IT support\n• Legal and financial advice"),
                'icon' => 'briefcase'
            ]
        ];
        
        // Load CTA section
        $this->ctaTitle = Setting::get('volunteers_cta_title', 'Ready to Make a Difference?');
        $this->ctaDescription = Setting::get('volunteers_cta_description', 'Complete our volunteer application to get started. The process takes just a few minutes, and we\'ll guide you through each step.');
        $this->ctaButtonText = Setting::get('volunteers_cta_button_text', 'Apply to Volunteer');
        
        // Load next steps section
        $this->nextTitle = Setting::get('volunteers_next_title', 'What Happens Next?');
        $this->nextSteps = [
            [
                'number' => '1',
                'title' => Setting::get('volunteers_next_step1_title', 'Application Review'),
                'description' => Setting::get('volunteers_next_step1_description', 'We\'ll review your application and contact you within a week')
            ],
            [
                'number' => '2',
                'title' => Setting::get('volunteers_next_step2_title', 'Orientation'),
                'description' => Setting::get('volunteers_next_step2_description', 'Attend a volunteer orientation session to learn about our procedures')
            ],
            [
                'number' => '3',
                'title' => Setting::get('volunteers_next_step3_title', 'Start Helping'),
                'description' => Setting::get('volunteers_next_step3_description', 'Begin your volunteer work and start making a difference!')
            ]
        ];
    }

    private function getIcon($iconType)
    {
        $icons = [
            'eye' => '<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>',
            'phone' => '<path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>',
            'clipboard' => '<path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/><path fill-rule="evenodd" d="M3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>',
            'briefcase' => '<path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>'
        ];
        
        return $icons[$iconType] ?? $icons['eye'];
    }
}; ?>

<div class="bg-amber-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-amber-800 mb-4">
                {{ $heroTitle }}
            </h1>
            <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                {{ $heroDescription }}
            </p>
        </div>

        <!-- Volunteer Opportunities -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-amber-800 mb-6">{{ $opportunitiesTitle }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($opportunities as $opportunity)
                <div class="border border-amber-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-amber-100 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                {!! $this->getIcon($opportunity['icon']) !!}
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-amber-800">{{ $opportunity['title'] }}</h3>
                    </div>
                    <p class="text-gray-700 mb-4">
                        {{ $opportunity['description'] }}
                    </p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        @foreach(explode("\n", $opportunity['tasks']) as $task)
                            @if(trim($task))
                                <li>{{ trim($task) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-amber-800 mb-4">{{ $ctaTitle }}</h2>
                <p class="text-gray-700 mb-6">
                    {{ $ctaDescription }}
                </p>
                <a href="{{ route('volunteer.apply') }}" 
                   class="inline-block bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                    {{ $ctaButtonText }}
                </a>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="mt-12 bg-amber-100 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-amber-800 mb-6 text-center">{{ $nextTitle }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($nextSteps as $step)
                <div class="text-center">
                    <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-amber-600">{{ $step['number'] }}</span>
                    </div>
                    <h3 class="font-semibold text-amber-800 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-gray-700 text-sm">{{ $step['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>