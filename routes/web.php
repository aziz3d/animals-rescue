<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public routes for the rescue website
Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/animals', \App\Livewire\Animals\Index::class)->name('animals.index');
Route::get('/animals/{animal}', \App\Livewire\Animals\Show::class)->name('animals.show');

Volt::route('/stories', 'stories.index')->name('stories.index');
Volt::route('/stories/{story}', 'stories.show')->name('stories.show');

Route::get('/donate', \App\Livewire\Donations\Index::class)->name('donate');
Route::get('/donate/confirmation', \App\Livewire\Donations\Confirmation::class)->name('donate.confirmation');
Route::get('/donate/receipt/{donation}', \App\Livewire\Donations\Receipt::class)->name('donate.receipt');

Volt::route('/volunteer', 'volunteer.index')->name('volunteer');
Volt::route('/volunteer/apply', 'volunteer.application')->name('volunteer.apply');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/privacy-policy', function () {
    return view('public.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('public.terms-of-service');
})->name('terms-of-service');

Volt::route('dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Admin routes for contact management
    Volt::route('admin/contacts', 'admin.contacts.index')->name('admin.contacts.index');
    Volt::route('admin/contacts/{contact}', 'admin.contacts.show')->name('admin.contacts.show');

    // Admin routes for animals management
    Volt::route('admin/animals', 'admin.animals.index')->name('admin.animals.index');
    Volt::route('admin/animals/create', 'admin.animals.create')->name('admin.animals.create');
    Volt::route('admin/animals/{animal}', 'admin.animals.show')->name('admin.animals.show');
    Volt::route('admin/animals/{animal}/edit', 'admin.animals.edit')->name('admin.animals.edit');

    // Admin routes for stories management
    Volt::route('admin/stories', 'admin.stories.index')->name('admin.stories.index');
    Volt::route('admin/stories/create', 'admin.stories.create')->name('admin.stories.create');
    Volt::route('admin/stories/{story}', 'admin.stories.show')->name('admin.stories.show');
    Volt::route('admin/stories/{story}/edit', 'admin.stories.edit')->name('admin.stories.edit');

    // Admin routes for volunteer management
    Volt::route('admin/volunteers', 'admin.volunteers.index')->name('admin.volunteers.index');
    Volt::route('admin/volunteers/{volunteer}', 'admin.volunteers.show')->name('admin.volunteers.show');

    // Admin routes for comment management
    Volt::route('admin/comments', 'admin.comments.index')->name('admin.comments.index');

    // Admin settings routes
    Volt::route('admin/settings/general', 'admin.settings.general')->name('admin.settings.general');
    Volt::route('admin/settings/animals', 'admin.settings.animals')->name('admin.settings.animals');
    Volt::route('admin/settings/stories', 'admin.settings.stories')->name('admin.settings.stories');
    Volt::route('admin/settings/volunteers', 'admin.settings.volunteers')->name('admin.settings.volunteers');
    Volt::route('admin/settings/donate', 'admin.settings.donate')->name('admin.settings.donate');
    Volt::route('admin/settings/payment', 'admin.settings.payment')->name('admin.settings.payment');
    Volt::route('admin/settings/contact', 'admin.settings.contact')->name('admin.settings.contact');
    Volt::route('admin/settings/privacy-policy', 'admin.settings.privacy-policy')->name('admin.settings.privacy-policy');
    Volt::route('admin/settings/terms-of-service', 'admin.settings.terms-of-service')->name('admin.settings.terms-of-service');

});

require __DIR__.'/auth.php';
