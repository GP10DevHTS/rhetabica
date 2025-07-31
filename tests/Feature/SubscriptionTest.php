<?php

use App\Livewire\Subscriptions\Index;
use App\Livewire\Subscriptions\Create;
use App\Livewire\Subscriptions\Edit;
use App\Livewire\Subscriptions\Show;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Livewire;
use Carbon\Carbon;

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

test('admin can view subscriptions index', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('subscriptions.index'));
    $response->assertStatus(200);
    $response->assertSee('Subscription Management');
});

test('non admin cannot view subscriptions index', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('subscriptions.index'));
    $response->assertStatus(403);
});

test('admin can create subscription', function () {
    $this->actingAs($this->admin);

    $startDate = now();
    $endDate = now()->addYear();

    Livewire::test(Create::class)
        ->set('user_id', $this->user->id)
        ->set('package_id', $this->package->id)
        ->set('start_date', $startDate->format('Y-m-d\TH:i'))
        ->set('end_date', $endDate->format('Y-m-d\TH:i'))
        ->set('status', 'active')
        ->call('save')
        ->assertRedirect(route('subscriptions.index'));

    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
    ]);
});

test('subscription creation validates required fields', function () {
    $this->actingAs($this->admin);

    Livewire::test(Create::class)
        ->set('user_id', '')
        ->set('package_id', '')
        ->set('start_date', '')
        ->set('end_date', '')
        ->call('save')
        ->assertHasErrors([
            'user_id' => 'required',
            'package_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
});

test('subscription creation deactivates existing active subscriptions', function () {
    $this->actingAs($this->admin);

    // Create an existing active subscription
    $existingSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonth(),
    ]);

    $startDate = now();
    $endDate = now()->addYear();

    Livewire::test(Create::class)
        ->set('user_id', $this->user->id)
        ->set('package_id', $this->package->id)
        ->set('start_date', $startDate->format('Y-m-d\TH:i'))
        ->set('end_date', $endDate->format('Y-m-d\TH:i'))
        ->set('status', 'active')
        ->call('save')
        ->assertRedirect(route('subscriptions.index'));

    // Check that the existing subscription was deactivated
    $this->assertDatabaseHas('subscriptions', [
        'id' => $existingSubscription->id,
        'status' => 'inactive',
    ]);

    // Check that the new subscription was created
    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
    ]);
});

test('admin can edit subscription', function () {
    $this->actingAs($this->admin);

    $subscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addMonth(),
    ]);

    $newEndDate = now()->addYear();

    Livewire::test(Edit::class, ['subscription' => $subscription])
        ->set('end_date', $newEndDate->format('Y-m-d\TH:i'))
        ->set('status', 'inactive')
        ->call('save')
        ->assertRedirect(route('subscriptions.index'));

    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
        'status' => 'inactive',
    ]);
});

test('admin can delete subscription', function () {
    $this->actingAs($this->admin);

    $subscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
    ]);

    Livewire::test(Index::class)
        ->call('deleteSubscription', $subscription->id)
        ->assertRedirect(route('subscriptions.index'));

    $this->assertSoftDeleted('subscriptions', ['id' => $subscription->id]);
});

test('admin can extend subscription', function () {
    $this->actingAs($this->admin);

    $subscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addDay(),
    ]);

    $newEndDate = now()->addYear();

    Livewire::test(Edit::class, ['subscription' => $subscription])
        ->set('end_date', $newEndDate->format('Y-m-d\TH:i'))
        ->call('save')
        ->assertRedirect(route('subscriptions.index'));

    // The datetime-local input only captures minutes, so seconds will be 00
    $expectedDate = $newEndDate->format('Y-m-d H:i') . ':00';

    $this->assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
        'end_date' => $expectedDate,
    ]);
});

test('subscription is active method works', function () {
    $activeSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonth(),
    ]);

    $inactiveSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'inactive',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonth(),
    ]);

    $expiredSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now()->subMonth(),
        'end_date' => now()->subDay(),
    ]);

    expect($activeSubscription->isActive())->toBeTrue();
    expect($inactiveSubscription->isActive())->toBeFalse();
    expect($expiredSubscription->isActive())->toBeFalse();
});

test('subscription is expired method works', function () {
    $activeSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonth(),
    ]);

    $expiredSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now()->subMonth(),
        'end_date' => now()->subDay(),
    ]);

    expect($activeSubscription->isExpired())->toBeFalse();
    expect($expiredSubscription->isExpired())->toBeTrue();
});

test('user can get active subscription', function () {
    $activeSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonth(),
    ]);

    $inactiveSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'inactive',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonth(),
    ]);

    $userActiveSubscription = $this->user->activeSubscription();

    expect($userActiveSubscription->id)->toBe($activeSubscription->id);
    expect($userActiveSubscription->id)->not->toBe($inactiveSubscription->id);
});

test('user without active subscription returns null', function () {
    $inactiveSubscription = Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'inactive',
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonth(),
    ]);

    $userActiveSubscription = $this->user->activeSubscription();

    expect($userActiveSubscription)->toBeNull();
}); 