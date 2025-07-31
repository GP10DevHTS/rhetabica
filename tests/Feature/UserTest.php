<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create the free package
        Package::create([
            'name' => 'Free',
            'description' => 'Basic package for new users with limited features',
            'price' => 0.00,
            'max_tab_spaces' => 1,
            'max_tournaments_per_tab' => 3,
            'is_active' => true,
        ]);
    }

    public function test_new_user_gets_free_package_automatically()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        // Check that the user has an active subscription
        $this->assertNotNull($user->activeSubscription());
        
        // Check that the subscription is for the free package
        $this->assertEquals('Free', $user->activeSubscription()->package->name);
        
        // Check that the subscription is active
        $this->assertEquals('active', $user->activeSubscription()->status);
        
        // Check that the subscription expires in 30 days
        $this->assertEquals(
            now()->addDays(30)->format('Y-m-d'),
            $user->activeSubscription()->end_date->format('Y-m-d')
        );
    }

    public function test_admin_user_does_not_get_free_package()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Check that the admin doesn't have an active subscription
        $this->assertNull($admin->activeSubscription());
    }

    public function test_user_with_existing_subscription_does_not_get_free_package()
    {
        // Create a user with an existing subscription
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        // Manually create a subscription for this user
        $premiumPackage = Package::create([
            'name' => 'Premium',
            'description' => 'Premium package',
            'price' => 19.99,
            'max_tab_spaces' => 5,
            'max_tournaments_per_tab' => 10,
            'is_active' => true,
        ]);

        $user->subscriptions()->create([
            'package_id' => $premiumPackage->id,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addYear(),
        ]);

        // Create another user to trigger the event
        $newUser = User::factory()->create([
            'is_admin' => false,
        ]);

        // The new user should get the free package
        $this->assertNotNull($newUser->activeSubscription());
        $this->assertEquals('Free', $newUser->activeSubscription()->package->name);
    }

    public function test_free_package_not_assigned_when_free_package_does_not_exist()
    {
        // Delete the free package
        Package::where('name', 'Free')->delete();

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        // Check that the user doesn't have an active subscription
        $this->assertNull($user->activeSubscription());
    }
} 