<?php

namespace Tests\Feature;

use App\Livewire\Subscriptions\Index;
use App\Livewire\Subscriptions\Create;
use App\Livewire\Subscriptions\Edit;
use App\Livewire\Subscriptions\Show;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Carbon\Carbon;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();
        
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
            'max_namespaces' => 5,
            'max_tournaments' => 10,
        ]);
    }

    
    public function admin_can_view_subscriptions_index()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('subscriptions.index'));
        $response->assertStatus(200);
        $response->assertSee('Subscription Management');
    }

    
    public function non_admin_cannot_view_subscriptions_index()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('subscriptions.index'));
        $response->assertStatus(403);
    }

    
    public function admin_can_create_subscription()
    {
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
    }

    
    public function subscription_creation_validates_required_fields()
    {
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
    }

    
    public function subscription_creation_deactivates_existing_active_subscriptions()
    {
        $this->actingAs($this->admin);

        // Create an existing active subscription
        $existingSubscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'active',
        ]);

        $startDate = now();
        $endDate = now()->addYear();

        Livewire::test(Create::class)
            ->set('user_id', $this->user->id)
            ->set('package_id', $this->package->id)
            ->set('start_date', $startDate->format('Y-m-d\TH:i'))
            ->set('end_date', $endDate->format('Y-m-d\TH:i'))
            ->set('status', 'active')
            ->call('save');

        // Check that the existing subscription was deactivated
        $this->assertDatabaseHas('subscriptions', [
            'id' => $existingSubscription->id,
            'status' => 'inactive',
        ]);

        // Check that the new subscription is active
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
        ]);
    }

    
    public function admin_can_edit_subscription()
    {
        $this->actingAs($this->admin);

        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
        ]);

        $newEndDate = now()->addMonths(6);

        Livewire::test(Edit::class, ['subscription' => $subscription])
            ->set('end_date', $newEndDate->format('Y-m-d\TH:i'))
            ->set('status', 'inactive')
            ->call('save')
            ->assertRedirect(route('subscriptions.index'));

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'inactive',
        ]);
    }

    
    public function admin_can_delete_subscription()
    {
        $this->actingAs($this->admin);

        $subscription = Subscription::factory()->create();

        Livewire::test(Index::class)
            ->call('deleteSubscription', $subscription->id);

        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }

    
    public function admin_can_extend_subscription()
    {
        $this->actingAs($this->admin);

        $subscription = Subscription::factory()->create([
            'end_date' => now()->addDays(30),
        ]);

        Livewire::test(Show::class, ['subscription' => $subscription])
            ->set('days', 60)
            ->call('extendSubscription');

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'end_date' => $subscription->end_date->addDays(60),
        ]);
    }

    
    public function subscription_is_active_method_works()
    {
        $activeSubscription = Subscription::factory()->create([
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $expiredSubscription = Subscription::factory()->create([
            'status' => 'active',
            'end_date' => now()->subDays(30),
        ]);

        $inactiveSubscription = Subscription::factory()->create([
            'status' => 'inactive',
            'end_date' => now()->addDays(30),
        ]);

        $this->assertTrue($activeSubscription->isActive());
        $this->assertFalse($expiredSubscription->isActive());
        $this->assertFalse($inactiveSubscription->isActive());
    }

    
    public function subscription_is_expired_method_works()
    {
        $activeSubscription = Subscription::factory()->create([
            'end_date' => now()->addDays(30),
        ]);

        $expiredSubscription = Subscription::factory()->create([
            'end_date' => now()->subDays(30),
        ]);

        $this->assertFalse($activeSubscription->isExpired());
        $this->assertTrue($expiredSubscription->isExpired());
    }

    
    public function user_can_get_active_subscription()
    {
        $activeSubscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $inactiveSubscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'inactive',
            'end_date' => now()->addDays(30),
        ]);

        $activeSub = $this->user->activeSubscription();

        $this->assertEquals($activeSubscription->id, $activeSub->id);
    }

    
    public function user_without_active_subscription_returns_null()
    {
        $inactiveSubscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'inactive',
        ]);

        $activeSub = $this->user->activeSubscription();

        $this->assertNull($activeSub);
    }
} 