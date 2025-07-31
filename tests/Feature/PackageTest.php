<?php

use App\Livewire\Packages\Index;
use App\Livewire\Packages\Create;
use App\Livewire\Packages\Edit;
use App\Livewire\Packages\Show;
use App\Models\Package;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create([
        'email' => 'admin@rhetabica.net',
        'password' => bcrypt('password'),
    ]);
    
    $this->user = User::factory()->create([
        'email' => 'user@rhetabica.com',
        'password' => bcrypt('password'),
    ]);
});

test('admin can view packages index', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('packages.index'));
    $response->assertStatus(200);
    $response->assertSee('Package Management');
});

test('non admin cannot view packages index', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('packages.index'));
    $response->assertStatus(403);
});

test('admin can create package', function () {
    $this->actingAs($this->admin);

    Livewire::test(Create::class)
        ->set('name', 'Test Package')
        ->set('description', 'A test package')
        ->set('price', 29.99)
        ->set('max_tab_spaces', 5)
        ->set('max_tournaments_per_tab', 10)
        ->set('is_active', true)
        ->call('save')
        ->assertRedirect(route('packages.index'));

    $this->assertDatabaseHas('packages', [
        'name' => 'Test Package',
        'description' => 'A test package',
        'price' => 29.99,
        'max_tab_spaces' => 5,
        'max_tournaments_per_tab' => 10,
        'is_active' => true,
    ]);
});

test('package creation validates required fields', function () {
    $this->actingAs($this->admin);

    Livewire::test(Create::class)
        ->set('name', '')
        ->set('price', -1)
        ->set('max_tab_spaces', 0)
        ->set('max_tournaments_per_tab', 0)
        ->call('save')
        ->assertHasErrors([
            'name' => 'required',
            'price' => 'min',
            'max_tab_spaces' => 'min',
            'max_tournaments_per_tab' => 'min',
        ]);
});

test('admin can edit package', function () {
    $this->actingAs($this->admin);

    $package = Package::factory()->create([
        'name' => 'Original Package',
        'description' => 'Original description',
        'price' => 19.99,
        'max_tab_spaces' => 3,
        'max_tournaments_per_tab' => 5,
        'is_active' => true,
    ]);

    Livewire::test(Edit::class, ['package' => $package])
        ->set('name', 'Updated Package')
        ->set('description', 'Updated description')
        ->set('price', 39.99)
        ->set('max_tab_spaces', 7)
        ->set('max_tournaments_per_tab', 15)
        ->set('is_active', false)
        ->call('save')
        ->assertRedirect(route('packages.index'));

    $this->assertDatabaseHas('packages', [
        'id' => $package->id,
        'name' => 'Updated Package',
        'description' => 'Updated description',
        'price' => 39.99,
        'max_tab_spaces' => 7,
        'max_tournaments_per_tab' => 15,
        'is_active' => false,
    ]);
});

test('admin can delete package without active subscriptions', function () {
    $this->actingAs($this->admin);

    $package = Package::factory()->create();

    Livewire::test(Index::class)
        ->call('deletePackage', $package->id)
        ->assertRedirect(route('packages.index'));

    $this->assertDatabaseMissing('packages', ['id' => $package->id]);
});

test('admin cannot delete package with active subscriptions', function () {
    $this->actingAs($this->admin);

    $package = Package::factory()->create();
    $user = User::factory()->create();
    
    // Create an active subscription for this package
    $subscription = $user->subscriptions()->create([
        'package_id' => $package->id,
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addYear(),
    ]);

    Livewire::test(Index::class)
        ->call('deletePackage', $package->id)
        ->assertNotRedirected();

    $this->assertDatabaseHas('packages', ['id' => $package->id]);
});

test('admin can view package details', function () {
    $this->actingAs($this->admin);

    $package = Package::factory()->create([
        'name' => 'Test Package',
        'description' => 'Test description',
        'price' => 29.99,
        'max_tab_spaces' => 5,
        'max_tournaments_per_tab' => 10,
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.show', $package));
    $response->assertStatus(200);
    $response->assertSee('Test Package');
    $response->assertSee('Test description');
    $response->assertSee('29.99');
});

test('package scope active works', function () {
    $activePackage = Package::factory()->create(['is_active' => true]);
    $inactivePackage = Package::factory()->create(['is_active' => false]);

    $activePackages = Package::active()->get();

    expect($activePackages)->toContain($activePackage);
    expect($activePackages)->not->toContain($inactivePackage);
}); 