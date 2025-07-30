<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckSubscription;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckSubscriptionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected CheckSubscription $middleware;
    protected User $admin;
    protected User $user;
    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->middleware = new CheckSubscription();
        
        $this->admin = User::factory()->admin()->create([
            'email' => 'admin@rhetabica.net',
        ]);
        
        $this->user = User::factory()->create([
            'email' => 'user@rhetabica.com',
        ]);

        $this->package = Package::factory()->create([
            'name' => 'Test Package',
            'max_namespaces' => 5,
            'max_tournaments' => 10,
        ]);
    }

    public function admin_can_bypass_subscription_check()
    {
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $this->admin);

        $response = $this->middleware->handle($request, function ($request) {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function user_with_active_subscription_can_access()
    {
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $this->user);

        $response = $this->middleware->handle($request, function ($request) {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function user_without_subscription_is_redirected()
    {
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $this->user);

        $response = $this->middleware->handle($request, function ($request) {
            return new Response('OK', 200);
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('dashboard', $response->getTargetUrl());
    }

    public function user_with_expired_subscription_is_redirected()
    {
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'end_date' => now()->subDays(30),
        ]);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $this->user);

        $response = $this->middleware->handle($request, function ($request) {
            return new Response('OK', 200);
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('dashboard', $response->getTargetUrl());
    }

    public function user_with_inactive_subscription_is_redirected()
    {
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'status' => 'inactive',
            'end_date' => now()->addDays(30),
        ]);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $this->user);

        $response = $this->middleware->handle($request, function ($request) {
            return new Response('OK', 200);
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('dashboard', $response->getTargetUrl());
    }
} 