<?php

use App\Models\Package;
use App\Models\User;
use App\Models\Tabspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

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
        'name' => 'Test Package',
        'price' => 29.99,
        'max_tab_spaces' => 5,
        'max_tournaments_per_tab' => 10,
    ]);
});

test('a user can create a tabspace', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('tabspaces.index')
        ->set('name', 'My First Tabspace')
        ->call('save');

    $this->assertDatabaseHas('tabspaces', [
        'user_id' => $user->id,
        'name' => 'My First Tabspace',
    ]);
});

test('a user cannot create a tabspace if they have reached their limit', function () {
    $user = User::factory()->create();
    $user->subscriptions()->delete();
    $package = Package::factory()->create(['max_tab_spaces' => 1]);
    $user->subscriptions()->create([
        'package_id' => $package->id,
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addDays(30),
    ]);
    Tabspace::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Livewire::test('tabspaces.index')
        ->set('name', 'My Second Tabspace')
        ->call('save')
        ->assertSessionHas('limit-reached');

    $this->assertDatabaseMissing('tabspaces', [
        'user_id' => $user->id,
        'name' => 'My Second Tabspace',
    ]);
});

test('a user cannot create a tabspace with a name that is already taken', function () {
    $user = User::factory()->create();
    Tabspace::factory()->create(['user_id' => $user->id, 'name' => 'My First Tabspace']);
    $this->actingAs($user);

    Livewire::test('tabspaces.index')
        ->set('name', 'My First Tabspace')
        ->call('save')
        ->assertHasErrors(['name' => 'unique']);
});

test('a guest cannot create a tabspace', function () {
    Livewire::test('tabspaces.index')
        ->set('name', 'My First Tabspace')
        ->call('save')
        ->assertForbidden();
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
