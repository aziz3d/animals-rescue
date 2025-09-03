<?php

use Livewire\Volt\Component;
use App\Models\Volunteer;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.public')] class extends Component
{
    // Multi-step form state
    public $currentStep = 1;
    public $totalSteps = 3;
    
    // Step 1: Personal Information
    #[Validate('required|string|max:255')]
    public $first_name = '';
    
    #[Validate('required|string|max:255')]
    public $last_name = '';
    
    #[Validate('required|email|max:255')]
    public $email = '';
    
    #[Validate('required|string|max:20')]
    public $phone = '';
    
    #[Validate('nullable|string|max:255')]
    public $address = '';
    
    #[Validate('nullable|string|max:100')]
    public $city = '';
    
    #[Validate('nullable|string|max:50')]
    public $state = '';
    
    #[Validate('nullable|string|max:10')]
    public $zip_code = '';
    
    // Step 2: Areas of Interest and Availability
    #[Validate('required|array|min:1')]
    public $areas_of_interest = [];
    
    #[Validate('required|array|min:1')]
    public $availability_days = [];
    
    #[Validate('required|string')]
    public $hours_per_week = '';
    
    // Step 3: Experience and Motivation
    #[Validate('nullable|string|max:2000')]
    public $experience = '';
    
    #[Validate('nullable|string|max:2000')]
    public $motivation = '';
    
    #[Validate('accepted')]
    public $commitment_agreement = false;
    
    #[Validate('accepted')]
    public $background_check_agreement = false;
    
    // Form submission state
    public $submitted = false;
    public $submissionError = '';

    public function mount()
    {
        // Initialize arrays
        $this->areas_of_interest = [];
        $this->availability_days = [];
    }

    public function nextStep()
    {
        $this->validateCurrentStep();
        
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            // Validate all previous steps before allowing navigation
            for ($i = 1; $i < $step; $i++) {
                $this->validateStep($i);
            }
            $this->currentStep = $step;
        }
    }

    private function validateCurrentStep()
    {
        $this->validateStep($this->currentStep);
    }

    private function validateStep($step)
    {
        switch ($step) {
            case 1:
                $this->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'required|string|max:20',
                ]);
                break;
            case 2:
                $this->validate([
                    'areas_of_interest' => 'required|array|min:1',
                    'availability_days' => 'required|array|min:1',
                    'hours_per_week' => 'required|string',
                ]);
                break;
            case 3:
                $this->validate([
                    'commitment_agreement' => 'accepted',
                    'background_check_agreement' => 'accepted',
                ]);
                break;
        }
    }

    public function submit()
    {
        try {
            // Validate all steps
            for ($i = 1; $i <= $this->totalSteps; $i++) {
                $this->validateStep($i);
            }

            // Create volunteer application
            $volunteer = Volunteer::create([
                'name' => trim($this->first_name . ' ' . $this->last_name),
                'email' => $this->email,
                'phone' => $this->phone,
                'areas_of_interest' => $this->areas_of_interest,
                'availability' => [
                    'days' => $this->availability_days,
                    'hours_per_week' => $this->hours_per_week,
                    'address' => $this->address,
                    'city' => $this->city,
                    'state' => $this->state,
                    'zip_code' => $this->zip_code,
                ],
                'experience' => $this->experience,
                'status' => 'pending',
            ]);

            $this->submitted = true;
            
            // Reset form (but keep submitted state)
            $this->reset([
                'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip_code',
                'areas_of_interest', 'availability_days', 'hours_per_week', 'experience', 'motivation',
                'commitment_agreement', 'background_check_agreement', 'submissionError'
            ]);
            $this->currentStep = 1;
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so they can be caught by Livewire
            throw $e;
        } catch (\Exception $e) {
            $this->submissionError = 'There was an error submitting your application. Please try again.';
        }
    }

    public function getStepTitleProperty()
    {
        return match($this->currentStep) {
            1 => 'Personal Information',
            2 => 'Interests & Availability',
            3 => 'Experience & Agreement',
            default => 'Step ' . $this->currentStep
        };
    }

    public function getProgressPercentageProperty()
    {
        return ($this->currentStep / $this->totalSteps) * 100;
    }


}; ?>

<div class="bg-amber-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($submitted)
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="mb-6">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-green-800 mb-4">Application Submitted Successfully!</h2>
                    <p class="text-gray-700 mb-6">
                        Thank you for your interest in volunteering with Lovely Paws Rescue. We have received your application and will review it within 5-7 business days.
                    </p>
                    <p class="text-sm text-gray-600 mb-6">
                        You will receive an email confirmation shortly, and we'll contact you to schedule an orientation session if your application is approved.
                    </p>
                    <a href="{{ route('home') }}" 
                       class="inline-block bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                        Return to Home
                    </a>
                </div>
            </div>
        @else
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-amber-800 mb-4">
                    Volunteer Application
                </h1>
                <p class="text-lg text-amber-700 max-w-2xl mx-auto">
                    Join our dedicated team of volunteers and make a direct impact on the lives of rescued animals.
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-amber-700">{{ $this->stepTitle }}</span>
                    <span class="text-sm text-amber-600">Step {{ $currentStep }} of {{ $totalSteps }}</span>
                </div>
                <div class="w-full bg-amber-200 rounded-full h-2">
                    <div class="bg-amber-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $this->progressPercentage }}%"></div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="flex justify-center mb-8">
                <div class="flex space-x-4">
                    @for($i = 1; $i <= $totalSteps; $i++)
                        <button wire:click="goToStep({{ $i }})"
                                class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-colors duration-200
                                       {{ $currentStep >= $i ? 'bg-amber-600 text-white' : 'bg-amber-200 text-amber-600' }}
                                       {{ $currentStep == $i ? 'ring-2 ring-amber-300' : '' }}">
                            {{ $i }}
                        </button>
                    @endfor
                </div>
            </div>

            @if($submissionError)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ $submissionError }}
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <form wire:submit="submit">
                    @if($currentStep == 1)
                        <!-- Step 1: Personal Information -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-amber-800 mb-4">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="first-name" class="block text-sm font-medium text-amber-700 mb-2">
                                        First Name <span class="text-red-500" aria-label="required">*</span>
                                    </label>
                                    <input type="text" 
                                           id="first-name"
                                           wire:model="first_name" 
                                           required 
                                           autocomplete="given-name"
                                           aria-describedby="@error('first_name') first-name-error @enderror"
                                           class="w-full border border-amber-200 rounded-md px-3 py-2 focus-visible @error('first_name') error-field @enderror">
                                    @error('first_name') 
                                        <p class="error-message" id="first-name-error" role="alert">{{ $message }}</p> 
                                    @enderror
                                </div>
                                <div>
                                    <label for="last-name" class="block text-sm font-medium text-amber-700 mb-2">
                                        Last Name <span class="text-red-500" aria-label="required">*</span>
                                    </label>
                                    <input type="text" 
                                           id="last-name"
                                           wire:model="last_name" 
                                           required 
                                           autocomplete="family-name"
                                           aria-describedby="@error('last_name') last-name-error @enderror"
                                           class="w-full border border-amber-200 rounded-md px-3 py-2 focus-visible @error('last_name') error-field @enderror">
                                    @error('last_name') 
                                        <p class="error-message" id="last-name-error" role="alert">{{ $message }}</p> 
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-amber-700 mb-2">Email Address *</label>
                                    <input type="email" wire:model="email" required 
                                           class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('email') border-red-500 @enderror">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-amber-700 mb-2">Phone Number *</label>
                                    <input type="tel" wire:model="phone" required 
                                           class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('phone') border-red-500 @enderror">
                                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-2">Address</label>
                                <input type="text" wire:model="address"
                                       class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-amber-700 mb-2">City</label>
                                    <input type="text" wire:model="city"
                                           class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-amber-700 mb-2">State</label>
                                    <input type="text" wire:model="state"
                                           class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-amber-700 mb-2">ZIP Code</label>
                                    <input type="text" wire:model="zip_code"
                                           class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($currentStep == 2)
                        <!-- Step 2: Interests & Availability -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-amber-800 mb-4">Interests & Availability</h3>
                            
                            <!-- Areas of Interest -->
                            <fieldset>
                                <legend class="block text-sm font-medium text-amber-700 mb-3">
                                    Areas of Interest (Select all that apply) <span class="text-red-500" aria-label="required">*</span>
                                </legend>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3" role="group" aria-describedby="@error('areas_of_interest') areas-error @enderror">
                                    <label class="flex items-center touch-target">
                                        <input type="checkbox" 
                                               wire:model="areas_of_interest" 
                                               value="animal-care" 
                                               class="text-amber-600 focus-visible touch-target">
                                        <span class="ml-2 text-gray-700">Animal Care</span>
                                    </label>
                                    <label class="flex items-center touch-target">
                                        <input type="checkbox" 
                                               wire:model="areas_of_interest" 
                                               value="adoption-support" 
                                               class="text-amber-600 focus-visible touch-target">
                                        <span class="ml-2 text-gray-700">Adoption Support</span>
                                    </label>
                                    <label class="flex items-center touch-target">
                                        <input type="checkbox" 
                                               wire:model="areas_of_interest" 
                                               value="administrative" 
                                               class="text-amber-600 focus-visible touch-target">
                                        <span class="ml-2 text-gray-700">Administrative</span>
                                    </label>
                                    <label class="flex items-center touch-target">
                                        <input type="checkbox" 
                                               wire:model="areas_of_interest" 
                                               value="fundraising" 
                                               class="text-amber-600 focus-visible touch-target">
                                        <span class="ml-2 text-gray-700">Fundraising Events</span>
                                    </label>
                                    <label class="flex items-center touch-target">
                                        <input type="checkbox" 
                                               wire:model="areas_of_interest" 
                                               value="transport" 
                                               class="text-amber-600 focus-visible touch-target">
                                        <span class="ml-2 text-gray-700">Animal Transport</span>
                                    </label>
                                    <label class="flex items-center touch-target">
                                        <input type="checkbox" 
                                               wire:model="areas_of_interest" 
                                               value="professional" 
                                               class="text-amber-600 focus-visible touch-target">
                                        <span class="ml-2 text-gray-700">Professional Skills</span>
                                    </label>
                                </div>
                                @error('areas_of_interest') 
                                    <p class="error-message" id="areas-error" role="alert">{{ $message }}</p> 
                                @enderror
                            </fieldset>

                            <!-- Availability -->
                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-3">Availability *</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-2">Days Available</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" wire:model="availability_days" value="weekdays" 
                                                       class="text-amber-600 focus:ring-amber-500">
                                                <span class="ml-2 text-gray-700">Weekdays</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" wire:model="availability_days" value="weekends" 
                                                       class="text-amber-600 focus:ring-amber-500">
                                                <span class="ml-2 text-gray-700">Weekends</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" wire:model="availability_days" value="evenings" 
                                                       class="text-amber-600 focus:ring-amber-500">
                                                <span class="ml-2 text-gray-700">Evenings</span>
                                            </label>
                                        </div>
                                        @error('availability_days') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-2">Hours per Week *</label>
                                        <select wire:model="hours_per_week" required
                                                class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('hours_per_week') border-red-500 @enderror">
                                            <option value="">Select hours per week</option>
                                            <option value="1-2">1-2 hours</option>
                                            <option value="3-5">3-5 hours</option>
                                            <option value="6-10">6-10 hours</option>
                                            <option value="10+">10+ hours</option>
                                        </select>
                                        @error('hours_per_week') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($currentStep == 3)
                        <!-- Step 3: Experience & Agreement -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-amber-800 mb-4">Experience & Agreement</h3>
                            
                            <!-- Experience -->
                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-2">Previous Experience with Animals</label>
                                <textarea wire:model="experience" rows="4" 
                                          placeholder="Please describe any previous experience you have with animals, volunteering, or relevant skills..."
                                          class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                            </div>

                            <!-- Motivation -->
                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-2">Why do you want to volunteer with us?</label>
                                <textarea wire:model="motivation" rows="4" 
                                          placeholder="Tell us what motivates you to volunteer and what you hope to contribute..."
                                          class="w-full border border-amber-200 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                            </div>

                            <!-- Agreement -->
                            <div class="space-y-3">
                                <label class="flex items-start">
                                    <input type="checkbox" wire:model="commitment_agreement" required 
                                           class="text-amber-600 focus:ring-amber-500 mt-1 @error('commitment_agreement') border-red-500 @enderror">
                                    <span class="ml-2 text-gray-700 text-sm">
                                        I understand that volunteering requires a commitment and I will notify the organization if I cannot fulfill my volunteer duties. *
                                    </span>
                                </label>
                                @error('commitment_agreement') <span class="text-red-500 text-sm block ml-6">{{ $message }}</span> @enderror
                                
                                <label class="flex items-start">
                                    <input type="checkbox" wire:model="background_check_agreement" required 
                                           class="text-amber-600 focus:ring-amber-500 mt-1 @error('background_check_agreement') border-red-500 @enderror">
                                    <span class="ml-2 text-gray-700 text-sm">
                                        I agree to undergo a background check if required for my volunteer position. *
                                    </span>
                                </label>
                                @error('background_check_agreement') <span class="text-red-500 text-sm block ml-6">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6 mt-6 border-t border-amber-200">
                        @if($currentStep > 1)
                            <button type="button" wire:click="previousStep"
                                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors duration-200">
                                Previous
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if($currentStep < $totalSteps)
                            <button type="button" wire:click="nextStep"
                                    class="bg-amber-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                                Next
                            </button>
                        @else
                            <button type="submit"
                                    class="bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                                Submit Application
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Next Steps Info -->
            <div class="mt-12 bg-amber-100 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-amber-800 mb-6 text-center">What Happens Next?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-amber-600">1</span>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Application Review</h3>
                        <p class="text-gray-700 text-sm">We'll review your application and contact you within a week</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-amber-600">2</span>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Orientation</h3>
                        <p class="text-gray-700 text-sm">Attend a volunteer orientation session to learn about our procedures</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-amber-600">3</span>
                        </div>
                        <h3 class="font-semibold text-amber-800 mb-2">Start Helping</h3>
                        <p class="text-gray-700 text-sm">Begin your volunteer work and start making a difference!</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>