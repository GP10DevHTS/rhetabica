<?php

use App\Http\Middleware\CheckSubscription;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->middleware = new CheckSubscription();
    
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

test('admin can bypass subscription check', function () {
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn () => $this->admin);

    $response = $this->middleware->handle($request, function ($request) {
        return new Response('OK', 200);
    });

    expect($response->getStatusCode())->toBe(200);
});

test('user with active subscription can access', function () {
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

    expect($response->getStatusCode())->toBe(200);
});

test('user without subscription is redirected', function () {
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn () => $this->user);

    $response = $this->middleware->handle($request, function ($request) {
        return new Response('OK', 200);
    });

    expect($response->getStatusCode())->toBe(302);
    expect($response->getTargetUrl())->toContain('dashboard');
});

test('user with expired subscription is redirected', function () {
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

    expect($response->getStatusCode())->toBe(302);
    expect($response->getTargetUrl())->toContain('dashboard');
});

test('user with inactive subscription is redirected', function () {
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

    expect($response->getStatusCode())->toBe(302);
    expect($response->getTargetUrl())->toContain('dashboard');
}); 