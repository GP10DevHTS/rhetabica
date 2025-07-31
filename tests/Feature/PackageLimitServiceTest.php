<?php

use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PackageLimitService;

beforeEach(function () {
    $this->service = new PackageLimitService();
    
    $this->admin = User::factory()->admin()->create([
        'email' => 'admin@rhetabica.net',
    ]);
    
    $this->user = User::factory()->create([
        'email' => 'user@rhetabica.com',
    ]);

    $this->package = Package::factory()->create([
        'name' => 'Test Package',
        'max_tab_spaces' => 5,
        'max_tournaments_per_tab' => 10,
    ]);
});

test('admin can always create tab spaces', function () {
    expect($this->service->canCreateTabSpace($this->admin))->toBeTrue();
});

test('admin can always create tournaments', function () {
    expect($this->service->canCreateTournamentInTabSpace($this->admin))->toBeTrue();
});

test('user without subscription cannot create tab spaces', function () {
    expect($this->service->canCreateTabSpace($this->user))->toBeFalse();
});

test('user without subscription cannot create tournaments', function () {
    expect($this->service->canCreateTournamentInTabSpace($this->user))->toBeFalse();
});

test('user with active subscription can create tab spaces within limit', function () {
    // Create active subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'end_date' => now()->addDays(30),
    ]);

    expect($this->service->canCreateTabSpace($this->user))->toBeTrue();
});

test('user with active subscription can create tournaments within limit', function () {
    // Create active subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'end_date' => now()->addDays(30),
    ]);

    expect($this->service->canCreateTournamentInTabSpace($this->user))->toBeTrue();
});

test('user with expired subscription cannot create tab spaces', function () {
    // Create expired subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'end_date' => now()->subDays(30),
    ]);

    expect($this->service->canCreateTabSpace($this->user))->toBeFalse();
});

test('user with expired subscription cannot create tournaments', function () {
    // Create expired subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'end_date' => now()->subDays(30),
    ]);

    expect($this->service->canCreateTournamentInTabSpace($this->user))->toBeFalse();
});

test('admin has unlimited tab space slots', function () {
    $slots = $this->service->getTabSpaceSlots($this->admin);
    expect($slots)->toBe(-1); // -1 indicates unlimited
});

test('admin has unlimited tournament slots', function () {
    $slots = $this->service->getTournamentSlots($this->admin);
    expect($slots)->toBe(-1); // -1 indicates unlimited
});

test('user without subscription has zero tab space slots', function () {
    $slots = $this->service->getTabSpaceSlots($this->user);
    expect($slots)->toBe(0);
});

test('user without subscription has zero tournament slots', function () {
    $slots = $this->service->getTournamentSlots($this->user);
    expect($slots)->toBe(0);
});

test('user with active subscription has correct tab space slots', function () {
    // Create active subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'end_date' => now()->addDays(30),
    ]);

    $slots = $this->service->getTabSpaceSlots($this->user);
    expect($slots)->toBe(5); // Should match package max_tab_spaces
});

test('user with active subscription has correct tournament slots', function () {
    // Create active subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'end_date' => now()->addDays(30),
    ]);

    $slots = $this->service->getTournamentSlots($this->user);
    expect($slots)->toBe(10); // Should match package max_tournaments_per_tab
});

test('package with unlimited tab spaces returns unlimited slots', function () {
    $unlimitedPackage = Package::factory()->create([
        'name' => 'Unlimited Package',
        'max_tab_spaces' => -1, // -1 indicates unlimited
        'max_tournaments_per_tab' => 10,
    ]);

    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $unlimitedPackage->id,
        'status' => 'active',
        'end_date' => now()->addDays(30),
    ]);

    $slots = $this->service->getTabSpaceSlots($this->user);
    expect($slots)->toBe(-1); // Should be unlimited
});

test('package with unlimited tournaments returns unlimited slots', function () {
    $unlimitedPackage = Package::factory()->create([
        'name' => 'Unlimited Package',
        'max_tab_spaces' => 5,
        'max_tournaments_per_tab' => -1, // -1 indicates unlimited
    ]);

    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $unlimitedPackage->id,
        'status' => 'active',
        'end_date' => now()->addDays(30),
    ]);

    $slots = $this->service->getTournamentSlots($this->user);
    expect($slots)->toBe(-1); // Should be unlimited
});

test('get user limits returns correct data for admin', function () {
    $limits = $this->service->getUserLimits($this->admin);

    expect($limits)->toHaveKey('tab_spaces');
    expect($limits)->toHaveKey('tournaments');
    expect($limits['tab_spaces'])->toBe(-1); // Unlimited
    expect($limits['tournaments'])->toBe(-1); // Unlimited
});

test('get user limits returns correct data for user without subscription', function () {
    $limits = $this->service->getUserLimits($this->user);

    expect($limits)->toHaveKey('tab_spaces');
    expect($limits)->toHaveKey('tournaments');
    expect($limits['tab_spaces'])->toBe(0);
    expect($limits['tournaments'])->toBe(0);
});

test('get user limits returns correct data for user with subscription', function () {
    // Create active subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'active',
        'end_date' => now()->addDays(30),
    ]);

    $limits = $this->service->getUserLimits($this->user);

    expect($limits)->toHaveKey('tab_spaces');
    expect($limits)->toHaveKey('tournaments');
    expect($limits['tab_spaces'])->toBe(5); // Should match package max_tab_spaces
    expect($limits['tournaments'])->toBe(10); // Should match package max_tournaments_per_tab
});

test('user with inactive subscription has zero slots', function () {
    // Create inactive subscription
    Subscription::factory()->create([
        'user_id' => $this->user->id,
        'package_id' => $this->package->id,
        'status' => 'inactive',
        'end_date' => now()->addDays(30),
    ]);

    $tabSpaceSlots = $this->service->getTabSpaceSlots($this->user);
    $tournamentSlots = $this->service->getTournamentSlots($this->user);

    expect($tabSpaceSlots)->toBe(0);
    expect($tournamentSlots)->toBe(0);
}); 