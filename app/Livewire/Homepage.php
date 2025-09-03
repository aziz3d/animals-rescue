<?php

namespace App\Livewire;

use App\Models\Animal;
use App\Models\Story;
use App\Models\Setting;
use Livewire\Component;

class Homepage extends Component
{
    public function render()
    {
        // Get the number of featured animals to display from settings (default to 6)
        $homepageAnimalsCount = (int) Setting::get('animals_homepage_count', 6);
        
        // Get featured animals (limit based on settings)
        $featuredAnimals = Animal::featured()
            ->available()
            ->limit($homepageAnimalsCount)
            ->get();

        // Get the number of stories to display on homepage (default to 6)
        $homepageStoriesCount = (int) Setting::get('stories_homepage_count', 6);
        
        // Get latest published stories (limit based on settings)
        // Prioritize featured stories first
        $latestStories = Story::published()
            ->orderByDesc('featured')
            ->latest('published_at')
            ->limit($homepageStoriesCount)
            ->get();

        // Get statistics from database
        $stats = [
            'total_rescued' => Animal::count(),
            'successful_adoptions' => Animal::where('adoption_status', 'adopted')->count(),
            'available_animals' => Animal::available()->count(),
            'total_stories' => Story::published()->count(),
        ];

        // Get hero section settings
        $heroSettings = [
            'background_image' => Setting::get('hero_background_image', ''),
            'title' => Setting::get('hero_title', 'Every Paw Deserves Love'),
            'subtitle' => Setting::get('hero_subtitle', 'Join us in our mission to rescue, rehabilitate, and rehome animals in need. Together, we can make a difference, one paw at a time.'),
            'cta1_text' => Setting::get('hero_cta1_text', 'Adopt a Pet'),
            'cta1_url' => Setting::get('hero_cta1_url', '/animals'),
            'cta2_text' => Setting::get('hero_cta2_text', 'Donate Now'),
            'cta2_url' => Setting::get('hero_cta2_url', '/donate'),
            'cta3_text' => Setting::get('hero_cta3_text', 'Volunteer'),
            'cta3_url' => Setting::get('hero_cta3_url', '/volunteer'),
        ];

        // Get featured animals section settings
        $featuredAnimalsSettings = [
            'title' => Setting::get('animals_homepage_title', 'Meet Our Featured Friends'),
            'description' => Setting::get('animals_homepage_description', 'These amazing animals are looking for their forever homes. Could one of them be perfect for your family?'),
        ];

        // Get stories section settings
        $storiesSettings = [
            'title' => Setting::get('stories_homepage_title', 'Success Stories'),
            'description' => Setting::get('stories_homepage_description', 'Read heartwarming stories of rescue, recovery, and the joy of finding forever homes.'),
        ];

        // Get CTA section settings
        $ctaSettings = [
            'title' => Setting::get('cta_title', 'Ready to Make a Difference?'),
            'description' => Setting::get('cta_description', 'Whether you\'re looking to adopt, volunteer, or donate, every action helps us save more lives.'),
            'button1_text' => Setting::get('cta_button1_text', 'Get in Touch'),
            'button1_url' => Setting::get('cta_button1_url', '/contact'),
            'button2_text' => Setting::get('cta_button2_text', 'Start Volunteering'),
            'button2_url' => Setting::get('cta_button2_url', '/volunteer'),
        ];

        return view('livewire.homepage', [
            'featuredAnimals' => $featuredAnimals,
            'latestStories' => $latestStories,
            'stats' => $stats,
            'heroSettings' => $heroSettings,
            'featuredAnimalsSettings' => $featuredAnimalsSettings,
            'storiesSettings' => $storiesSettings,
            'ctaSettings' => $ctaSettings,
        ]);
    }
}