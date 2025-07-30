<?php

namespace Tests\Feature;

use App\Livewire\Packages\Index;
use App\Livewire\Packages\Create;
use App\Livewire\Packages\Edit;
use App\Livewire\Packages\Show;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PackageTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

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
    }

    
    public function admin_can_view_packages_index()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('packages.index'));
        $response->assertStatus(200);
        $response->assertSee('Package Management');
    }

    
    public function non_admin_cannot_view_packages_index()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('packages.index'));
        $response->assertStatus(403);
    }

    
    public function admin_can_create_package()
    {
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
    }

    
    public function package_creation_validates_required_fields()
    {
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
    }

    
    public function admin_can_edit_package()
    {
        $this->actingAs($this->admin);

        $package = Package::factory()->create([
            'name' => 'Original Name',
            'price' => 19.99,
        ]);

        Livewire::test(Edit::class, ['package' => $package])
            ->set('name', 'Updated Name')
            ->set('price', 39.99)
            ->set('max_tab_spaces', 10)
            ->set('max_tournaments_per_tab', 20)
            ->call('save')
            ->assertRedirect(route('packages.index'));

        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'name' => 'Updated Name',
            'price' => 39.99,
            'max_tab_spaces' => 10,
            'max_tournaments_per_tab' => 20,
        ]);
    }

    
    public function admin_can_delete_package_without_active_subscriptions()
    {
        $this->actingAs($this->admin);

        $package = Package::factory()->create();

        Livewire::test(Index::class)
            ->call('deletePackage', $package->id)
            ->assertDispatchedBrowserEvent('package-deleted');

        $this->assertDatabaseMissing('packages', ['id' => $package->id]);
    }

    
    public function admin_cannot_delete_package_with_active_subscriptions()
    {
        $this->actingAs($this->admin);

        $package = Package::factory()->create();
        $subscription = \App\Models\Subscription::factory()->create([
            'package_id' => $package->id,
            'status' => 'active',
        ]);

        Livewire::test(Index::class)
            ->call('deletePackage', $package->id);

        $this->assertDatabaseHas('packages', ['id' => $package->id]);
    }

    
    public function admin_can_view_package_details()
    {
        $this->actingAs($this->admin);

        $package = Package::factory()->create([
            'name' => 'Test Package',
            'description' => 'Test Description',
        ]);

        $response = $this->get(route('packages.show', $package));
        $response->assertStatus(200);
        $response->assertSee('Test Package');
        $response->assertSee('Test Description');
    }

    
    public function package_scope_active_works()
    {
        $activePackage = Package::factory()->create(['is_active' => true]);
        $inactivePackage = Package::factory()->create(['is_active' => false]);

        $activePackages = Package::active()->get();

        $this->assertTrue($activePackages->contains($activePackage));
        $this->assertFalse($activePackages->contains($inactivePackage));
    }
} 