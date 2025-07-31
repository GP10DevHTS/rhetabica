<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use App\Models\Tabspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TabspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_tabspace()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('tabspaces.create')
            ->set('name', 'My First Tabspace')
            ->call('save')
            ->assertDispatched('tabspace-created');

        $this->assertDatabaseHas('tabspaces', [
            'user_id' => $user->id,
            'name' => 'My First Tabspace',
        ]);
    }

    public function test_a_user_cannot_create_a_tabspace_if_they_have_reached_their_limit()
    {
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

        Livewire::test('tabspaces.create')
            ->set('name', 'My Second Tabspace')
            ->call('save')
            ->assertSessionHas('limit-reached');

        $this->assertDatabaseMissing('tabspaces', [
            'user_id' => $user->id,
            'name' => 'My Second Tabspace',
        ]);
    }

    public function test_a_user_cannot_create_a_tabspace_with_a_name_that_is_already_taken()
    {
        $user = User::factory()->create();
        Tabspace::factory()->create(['user_id' => $user->id, 'name' => 'My First Tabspace']);
        $this->actingAs($user);

        Livewire::test('tabspaces.create')
            ->set('name', 'My First Tabspace')
            ->call('save')
            ->assertHasErrors(['name' => 'unique']);
    }

    public function test_a_guest_cannot_create_a_tabspace()
    {
        Livewire::test('tabspaces.create')
            ->set('name', 'My First Tabspace')
            ->call('save')
            ->assertForbidden();
    }

    public function test_a_user_can_see_their_tabspaces_on_the_index_page()
    {
        $user = User::factory()->create();
        $tabspace = Tabspace::factory()->create(['user_id' => $user->id, 'name' => 'My Tabspace']);
        $this->actingAs($user);

        $this->get(route('tabspaces.index'))
            ->assertSee('My Tabspace');
    }

    public function test_a_user_cannot_see_other_users_tabspaces()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $tabspace1 = Tabspace::factory()->create(['user_id' => $user1->id, 'name' => 'User 1 Tabspace']);
        $tabspace2 = Tabspace::factory()->create(['user_id' => $user2->id, 'name' => 'User 2 Tabspace']);

        $this->actingAs($user1);

        $this->get(route('tabspaces.index'))
            ->assertSee('User 1 Tabspace')
            ->assertDontSee('User 2 Tabspace');
    }
}
