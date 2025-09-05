<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public routes for browsing public tabspaces and tournaments
Route::get('explore/tabspaces', \App\Livewire\Tabspaces\Explore::class)->name('tabspaces.explore');
Route::get('explore/tournaments', \App\Livewire\Tournaments\Explore::class)->name('tournaments.explore');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Tabspace routes
    Route::get('tabspaces', \App\Livewire\Tabspaces\Index::class)->name('tabspaces.index');
    Route::get('tabspaces/{tabspace:slug}', \App\Livewire\Tabspaces\Show::class)->name('tabspaces.show');

    // Tournament routes
    Route::get('tournaments/{tournament:slug}', \App\Livewire\Tournaments\Show::class)->name('tournaments.show');
    // Route::get('tournaments/{tournament:slug}/participants', \App\Livewire\Tournaments\Participants\Index::class)->name('tournament.participants');
});

// Admin-only routes for package and subscription management
Route::middleware(['auth', 'admin'])->group(function () {
    // Package routes
    Route::get('packages', \App\Livewire\Packages\Index::class)->name('packages.index');
    Route::get('packages/create', \App\Livewire\Packages\Create::class)->name('packages.create');
    Route::get('packages/{package}', \App\Livewire\Packages\Show::class)->name('packages.show');
    Route::get('packages/{package}/edit', \App\Livewire\Packages\Edit::class)->name('packages.edit');

    // Subscription routes
    Route::get('subscriptions', \App\Livewire\Subscriptions\Index::class)->name('subscriptions.index');
    Route::get('subscriptions/create', \App\Livewire\Subscriptions\Create::class)->name('subscriptions.create');
    Route::get('subscriptions/{subscription}', \App\Livewire\Subscriptions\Show::class)->name('subscriptions.show');
    Route::get('subscriptions/{subscription}/edit', \App\Livewire\Subscriptions\Edit::class)->name('subscriptions.edit');
});

require __DIR__.'/auth.php';
