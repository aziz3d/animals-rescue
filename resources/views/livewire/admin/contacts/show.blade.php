<?php

use App\Models\Contact;
use App\Mail\ContactReply;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component
{
    public Contact $contact;
    public string $reply = '';
    public bool $showReplyForm = false;

    public function mount(Contact $contact)
    {
        $this->contact = $contact;
        
        // Mark as read when viewed
        if ($contact->status === 'new') {
            $contact->markAsRead();
        }
    }

    public function toggleReplyForm()
    {
        $this->showReplyForm = !$this->showReplyForm;
        if (!$this->showReplyForm) {
            $this->reply = '';
        }
    }

    public function sendReply()
    {
        $this->validate([
            'reply' => 'required|string|min:10|max:5000',
        ]);

        try {
            // Send email reply to contact
            Mail::to($this->contact->email)->send(new ContactReply($this->contact, $this->reply));

            // Mark as replied
            $this->contact->markAsReplied();
            
            // Reset form
            $this->reply = '';
            $this->showReplyForm = false;
            
            session()->flash('success', 'Reply sent successfully!');
        } catch (\Exception $e) {
            // If email fails, still mark as replied but show different message
            $this->contact->markAsReplied();
            
            // Reset form
            $this->reply = '';
            $this->showReplyForm = false;
            
            session()->flash('warning', 'Reply marked as sent, but email delivery may have failed. Please check your mail configuration.');
        }
    }

    public function markAsRead()
    {
        $this->contact->markAsRead();
        session()->flash('success', 'Message marked as read.');
    }

    public function markAsReplied()
    {
        $this->contact->markAsReplied();
        session()->flash('success', 'Message marked as replied.');
    }

    public function deleteContact()
    {
        $this->contact->delete();
        return redirect()->route('admin.contacts.index')
                        ->with('success', 'Contact message deleted successfully.');
    }

    public function getStatusColorProperty()
    {
        return match($this->contact->status) {
            'new' => 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900',
            'read' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900',
            'replied' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
        };
    }
}; ?>

<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Message</h1>
                <p class="text-gray-600 dark:text-gray-400">From {{ $contact->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <flux:button wire:click="toggleReplyForm" variant="primary" size="sm">
                    <flux:icon.envelope class="w-4 h-4 mr-2" />
                    {{ $showReplyForm ? 'Cancel Reply' : 'Reply' }}
                </flux:button>
                <flux:dropdown>
                    <flux:button variant="ghost" size="sm">
                        <flux:icon.ellipsis-horizontal class="w-4 h-4" />
                    </flux:button>
                    <flux:menu>
                        @if($contact->status !== 'read')
                            <flux:menu.item wire:click="markAsRead">
                                <flux:icon.eye class="w-4 h-4 mr-2" />
                                Mark as Read
                            </flux:menu.item>
                        @endif
                        @if($contact->status !== 'replied')
                            <flux:menu.item wire:click="markAsReplied">
                                <flux:icon.check class="w-4 h-4 mr-2" />
                                Mark as Replied
                            </flux:menu.item>
                        @endif
                        <flux:menu.separator />
                        <flux:menu.item wire:click="deleteContact" wire:confirm="Are you sure you want to delete this contact message?">
                            <flux:icon.trash class="w-4 h-4 mr-2" />
                            Delete Message
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mb-6">
        <flux:button href="{{ route('admin.contacts.index') }}" variant="ghost" size="sm">
            <flux:icon.arrow-left class="w-4 h-4 mr-2" />
            Back to Messages
        </flux:button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Message Content -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <!-- Message Header -->
                <div class="border-b border-gray-200 dark:border-zinc-700 pb-4 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $contact->subject }}</h2>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $this->statusColor }}">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Received {{ $contact->created_at->format('M j, Y \a\t g:i A') }}
                    </div>
                </div>

                <!-- Message Body -->
                <div class="prose dark:prose-invert max-w-none">
                    <div class="whitespace-pre-wrap text-gray-900 dark:text-white">{{ $contact->message }}</div>
                </div>
            </div>

            <!-- Reply Form -->
            @if($showReplyForm)
                <div class="mt-6 bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Send Reply</h3>
                    
                    <form wire:submit="sendReply">
                        <div class="mb-4">
                            <flux:field>
                                <flux:label>Reply Message</flux:label>
                                <flux:textarea 
                                    wire:model="reply" 
                                    rows="6" 
                                    placeholder="Type your reply here..."
                                    required
                                />
                                <flux:error name="reply" />
                            </flux:field>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Reply will be sent to: {{ $contact->email }}
                            </div>
                            <div class="flex space-x-3">
                                <flux:button wire:click="toggleReplyForm" variant="ghost">
                                    Cancel
                                </flux:button>
                                <flux:button type="submit" variant="primary">
                                    <flux:icon.paper-airplane class="w-4 h-4 mr-2" />
                                    Send Reply
                                </flux:button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Email Configuration:</strong> Make sure your mail settings are configured in the .env file. 
                            The system will attempt to send the email and mark the message as replied regardless of delivery status.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contact Details Sidebar -->
        <div class="space-y-6">
            <!-- Contact Information -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Name</label>
                        <p class="text-gray-900 dark:text-white">{{ $contact->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Email</label>
                        <p class="text-gray-900 dark:text-white">
                            <a href="mailto:{{ $contact->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $contact->email }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Subject</label>
                        <p class="text-gray-900 dark:text-white">{{ $contact->subject }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $this->statusColor }}">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Received</label>
                        <p class="text-gray-900 dark:text-white">{{ $contact->created_at->format('M j, Y') }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $contact->created_at->format('g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    @if($contact->status !== 'read')
                        <flux:button wire:click="markAsRead" variant="outline" class="w-full justify-start">
                            <flux:icon.eye class="w-4 h-4 mr-2" />
                            Mark as Read
                        </flux:button>
                    @endif

                    @if($contact->status !== 'replied')
                        <flux:button wire:click="markAsReplied" variant="outline" class="w-full justify-start">
                            <flux:icon.check class="w-4 h-4 mr-2" />
                            Mark as Replied
                        </flux:button>
                    @endif

                    <flux:button wire:click="toggleReplyForm" variant="outline" class="w-full justify-start">
                        <flux:icon.envelope class="w-4 h-4 mr-2" />
                        {{ $showReplyForm ? 'Cancel Reply' : 'Send Reply' }}
                    </flux:button>

                    <hr class="border-gray-200 dark:border-zinc-700">

                    <flux:button 
                        wire:click="deleteContact" 
                        wire:confirm="Are you sure you want to delete this contact message? This action cannot be undone."
                        variant="danger" 
                        class="w-full justify-start"
                    >
                        <flux:icon.trash class="w-4 h-4 mr-2" />
                        Delete Message
                    </flux:button>
                </div>
            </div>

            <!-- Related Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Related Actions</h3>
                
                <div class="space-y-3">
                    @if(str_contains(strtolower($contact->subject), 'adoption'))
                        <flux:button href="{{ route('animals.index') }}" variant="outline" class="w-full justify-start">
                            <flux:icon.heart class="w-4 h-4 mr-2" />
                            View Animals
                        </flux:button>
                    @endif

                    @if(str_contains(strtolower($contact->subject), 'volunteer'))
                        <flux:button href="{{ route('volunteer') }}" variant="outline" class="w-full justify-start">
                            <flux:icon.user-group class="w-4 h-4 mr-2" />
                            Volunteer Info
                        </flux:button>
                    @endif

                    @if(str_contains(strtolower($contact->subject), 'donation'))
                        <flux:button href="{{ route('donate') }}" variant="outline" class="w-full justify-start">
                            <flux:icon.currency-dollar class="w-4 h-4 mr-2" />
                            Donation Page
                        </flux:button>
                    @endif

                    <flux:button href="{{ route('admin.contacts.index') }}" variant="outline" class="w-full justify-start">
                        <flux:icon.inbox class="w-4 h-4 mr-2" />
                        All Messages
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>