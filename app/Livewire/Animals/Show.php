<?php

namespace App\Livewire\Animals;

use App\Models\Animal;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\NewContactSubmission;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Show extends Component
{
    public Animal $animal;
    public $selectedImageIndex = 0;
    public $showInquiryForm = false;
    
    // Inquiry form fields
    public $inquiry_name = '';
    public $inquiry_email = '';
    public $inquiry_phone = '';
    public $inquiry_message = '';
    public $inquiry_type = 'adoption'; // adoption or question

    public function mount(Animal $animal)
    {
        $this->animal = $animal;
        
        // Debug: Log image information
        \Log::info("Animal {$animal->id} loaded with images:", [
            'raw_images' => $animal->images,
            'image_urls' => $animal->image_urls,
            'first_image' => $animal->first_image,
            'selected_index' => $this->selectedImageIndex
        ]);
    }

    public function selectImage($index)
    {
        $this->selectedImageIndex = $index;
        
        // Debug logging
        \Log::info("Image selected: index {$index}, total images: " . count($this->animal->image_urls ?? []));
        
        // Dispatch browser event for debugging
        $this->dispatch('image-selected', ['index' => $index]);
    }

    public function toggleInquiryForm($type = 'adoption')
    {
        $this->inquiry_type = $type;
        $this->showInquiryForm = !$this->showInquiryForm;
        
        if (!$this->showInquiryForm) {
            $this->resetInquiryForm();
        }
    }

    public function submitInquiry()
    {
        $this->validate([
            'inquiry_name' => 'required|string|max:255',
            'inquiry_email' => 'required|email|max:255',
            'inquiry_phone' => 'nullable|string|max:20',
            'inquiry_message' => 'required|string|max:1000',
        ]);

        // Create contact record
        $subject = $this->inquiry_type === 'adoption' 
            ? "Adoption Inquiry - {$this->animal->name}" 
            : "Question about {$this->animal->name}";
            
        $message = "Animal: {$this->animal->name} (ID: {$this->animal->id})\n";
        $message .= "Inquiry Type: " . ucfirst($this->inquiry_type) . "\n\n";
        if ($this->inquiry_phone) {
            $message .= "Phone: {$this->inquiry_phone}\n\n";
        }
        $message .= $this->inquiry_message;

        $contact = Contact::create([
            'name' => $this->inquiry_name,
            'email' => $this->inquiry_email,
            'subject' => $subject,
            'message' => $message,
            'status' => 'new',
        ]);

        // Send notification to all users (since no role system is implemented)
        $users = User::all();

        // Send notification to users
        if ($users->isNotEmpty()) {
            Notification::send($users, new NewContactSubmission($contact));
        }

        session()->flash('inquiry_sent', true);
        session()->flash('inquiry_type', $this->inquiry_type);
        $this->resetInquiryForm();
        $this->showInquiryForm = false;
    }

    public function resetInquiryForm()
    {
        $this->inquiry_name = '';
        $this->inquiry_email = '';
        $this->inquiry_phone = '';
        $this->inquiry_message = '';
    }

    public function shareOnFacebook()
    {
        $url = urlencode(request()->url());
        $text = urlencode("Check out {$this->animal->name} - available for adoption at Lovely Paws Rescue!");
        
        return redirect()->to("https://www.facebook.com/sharer/sharer.php?u={$url}&quote={$text}");
    }

    public function shareOnTwitter()
    {
        $url = urlencode(request()->url());
        $text = urlencode("Meet {$this->animal->name}! This adorable {$this->animal->species} is looking for a forever home. #AdoptDontShop");
        
        return redirect()->to("https://twitter.com/intent/tweet?url={$url}&text={$text}");
    }

    public function getStatusColorProperty()
    {
        return match($this->animal->adoption_status) {
            'available' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'adopted' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function render()
    {
        return view('livewire.animals.show')
            ->layout('components.layouts.public', [
                'title' => $this->animal->name . ' - Lovely Paws Rescue'
            ]);
    }
}