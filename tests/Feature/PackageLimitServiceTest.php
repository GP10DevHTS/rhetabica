<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PackageLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageLimitServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PackageLimitService $service;
    protected User $admin;
    protected User $user;
    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();
        
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
    }

    
    public function admin_can_always_create_tab_spaces()
    {
        $this->assertTrue($this->service->canCreateTabSpace($this->admin));
    }

    
    public function admin_can_always_create_tournaments()
    {
        $this->assertTrue($this->service->canCreateTournamentInTabSpace($this->admin));
    }

    
    public function user_without_subscription_cannot_create_tab_spaces()
    {
        $this->assertFalse($this->service->canCreateTabSpace($this->user));
    }

    
    public function user_without_subscription_cannot_create_tournaments()
    {
        $this->assertFalse($this->service->canCreateTournamentInTabSpace($this->user));
    }

    
    public function user_with_active_subscription_can_create_tab_spaces_within_limit()
    {
        // Create active subscription
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $this->assertTrue($this->service->canCreateTabSpace($this->user));
    }

    
    public function user_with_active_subscription_can_create_tournaments_within_limit()
    {
        // Create active subscription
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $this->assertTrue($this->service->canCreateTournamentInTabSpace($this->user));
    }

    
    public function user_with_expired_subscription_cannot_create_tab_spaces()
    {
        // Create expired subscription
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->subDays(30),
        ]);

        $this->assertFalse($this->service->canCreateTabSpace($this->user));
    }

    
    public function user_with_expired_subscription_cannot_create_tournaments()
    {
        // Create expired subscription
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->subDays(30),
        ]);

        $this->assertFalse($this->service->canCreateTournamentInTabSpace($this->user));
    }

    
    public function admin_has_unlimited_tab_space_slots()
    {
        $this->assertEquals(-1, $this->service->getRemainingTabSpaceSlots($this->admin));
    }

    
    public function admin_has_unlimited_tournament_slots()
    {
        $this->assertEquals(-1, $this->service->getRemainingTournamentSlotsInTabSpace($this->admin));
    }

    
    public function user_without_subscription_has_zero_tab_space_slots()
    {
        $this->assertEquals(0, $this->service->getRemainingTabSpaceSlots($this->user));
    }

    
    public function user_without_subscription_has_zero_tournament_slots()
    {
        $this->assertEquals(0, $this->service->getRemainingTournamentSlotsInTabSpace($this->user));
    }

    public function user_with_active_subscription_has_correct_tab_space_slots()
    {
        // Create active subscription
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        // Package allows 5 tab spaces, user has 0 currently
        $this->assertEquals(5, $this->service->getRemainingTabSpaceSlots($this->user));
    }

    public function user_with_active_subscription_has_correct_tournament_slots()
    {
        // Create active subscription
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        // Package allows 10 tournaments per tab, user has 0 currently
        $this->assertEquals(10, $this->service->getRemainingTournamentSlotsInTabSpace($this->user));
    }

    public function package_with_unlimited_tab_spaces_returns_unlimited_slots()
    {
        $unlimitedPackage = Package::factory()->create([
            'max_tab_spaces' => -1,
            'max_tournaments_per_tab' => 10,
        ]);

        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $unlimitedPackage->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $this->assertEquals(-1, $this->service->getRemainingTabSpaceSlots($this->user));
    }

    public function package_with_unlimited_tournaments_returns_unlimited_slots()
    {
        $unlimitedPackage = Package::factory()->create([
            'max_tab_spaces' => 5,
            'max_tournaments_per_tab' => -1,
        ]);

        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $unlimitedPackage->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $this->assertEquals(-1, $this->service->getRemainingTournamentSlotsInTabSpace($this->user));
    }

    public function get_user_limits_returns_correct_data_for_admin()
    {
        $limits = $this->service->getUserLimits($this->admin);

        $this->assertEquals([
            'tab_spaces' => -1,
            'tournaments_per_tab' => -1,
            'remaining_tab_spaces' => -1,
            'remaining_tournaments_per_tab' => -1,
        ], $limits);
    }

    public function get_user_limits_returns_correct_data_for_user_without_subscription()
    {
        $limits = $this->service->getUserLimits($this->user);

        $this->assertEquals([
            'tab_spaces' => 0,
            'tournaments_per_tab' => 0,
            'remaining_tab_spaces' => 0,
            'remaining_tournaments_per_tab' => 0,
        ], $limits);
    }

    public function get_user_limits_returns_correct_data_for_user_with_subscription()
    {
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $limits = $this->service->getUserLimits($this->user);

        $this->assertEquals([
            'tab_spaces' => 5,
            'tournaments_per_tab' => 10,
            'remaining_tab_spaces' => 5,
            'remaining_tournaments_per_tab' => 10,
        ], $limits);
    }

    public function user_with_inactive_subscription_has_zero_slots()
    {
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'inactive',
            'end_date' => now()->addDays(30),
        ]);

        $this->assertEquals(0, $this->service->getRemainingTabSpaceSlots($this->user));
        $this->assertEquals(0, $this->service->getRemainingTournamentSlotsInTabSpace($this->user));
    }
} 