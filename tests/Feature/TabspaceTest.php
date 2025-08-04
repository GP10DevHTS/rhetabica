<?php

use App\Models\User;
use Livewire\Livewire;
use App\Models\Package;
use App\Models\Tabspace;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create([
        'email' => 'admin@rhetabica.net',
        'password' => bcrypt('password'),
    ]);

    $this->user = User::factory()->create([
        'email' => 'user@rhetabica.com',
        'password' => bcrypt('password'),
    ]);

    $this->package = Package::factory()->create([
        'name' => 'Free',
        'price' => 29.99,
        'max_tab_spaces' => 5,
        'max_tournaments_per_tab' => 10,
    ]);
});

test('a user can create a tabspace', function () {
    // Ensure user has an active subscription
    $this->user->subscriptions()->create([
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addDays(30),
    ]);

    $this->actingAs($this->user);

    $tabspaceName = Str::random(10) . ' tabspace';

    Livewire::actingAs($this->user)
        ->test('tabspaces.index')
        ->set('name', $tabspaceName)
        ->call('save');

    $this->assertDatabaseHas('tabspaces', [
        'user_id' => $this->user->id,
        'name' => $tabspaceName,
    ]);
});

test('a user cannot create a tabspace with a name that is already taken', function () {
    $user = User::factory()->create();
    // Give user a subscription to ensure they can create tabspaces
    $user->subscriptions()->create([
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addDays(30),
    ]);

    Tabspace::factory()->create(['user_id' => $user->id, 'name' => 'My First Tabspace']);
    $this->actingAs($user);

    Livewire::actingAs($user)
        ->test('tabspaces.index')
        ->set('name', 'My First Tabspace')
        ->call('save')
        ->assertHasErrors(['name' => 'unique']);
});

test('a user can see their tabspaces on the index page', function () {
    $user = User::factory()->create();
    $tabspace = Tabspace::factory()->create(['user_id' => $user->id, 'name' => 'My Tabspace']);
    $this->actingAs($user);

    $this->get(route('tabspaces.index'))
        ->assertSee('My Tabspace');
});

test('a user cannot see other users tabspaces', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $tabspace1 = Tabspace::factory()->create(['user_id' => $user1->id, 'name' => 'User 1 Tabspace']);
    $tabspace2 = Tabspace::factory()->create(['user_id' => $user2->id, 'name' => 'User 2 Tabspace']);

    $this->actingAs($user1);

    $this->get(route('tabspaces.index'))
        ->assertSee('User 1 Tabspace')
        ->assertDontSee('User 2 Tabspace');
});
