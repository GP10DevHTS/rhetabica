# Package Management System

This document describes the package management system implemented in the Rhetabica application.

## Overview

The package management system allows administrators to create and manage subscription packages that limit user access to features like tab spaces and tournaments. Only administrators can manage packages and subscriptions.

## Features

### Package Management
- Create, edit, and delete packages
- Set pricing for packages
- Configure limits for tab spaces and tournaments per tab
- Activate/deactivate packages
- View package statistics

### Subscription Management
- Assign packages to users
- Set subscription start and end dates
- Manage subscription status (active, inactive, expired)
- Extend subscription duration
- View subscription details and history

### Access Control
- Admin-only access to package and subscription management
- Middleware to check subscription status
- Service to check package limits

## Database Structure

### Packages Table
- `id` - Primary key
- `name` - Package name
- `description` - Package description
- `price` - Package price (decimal)
- `max_tab_spaces` - Maximum number of tab spaces allowed (-1 for unlimited)
- `max_tournaments_per_tab` - Maximum number of tournaments allowed per tab space (-1 for unlimited)
- `is_active` - Whether the package is available for subscription
- `created_at`, `updated_at` - Timestamps

### Subscriptions Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `package_id` - Foreign key to packages table
- `start_date` - Subscription start date
- `end_date` - Subscription end date
- `status` - Subscription status (active, inactive, expired)
- `created_at`, `updated_at` - Timestamps

## Routes

### Package Routes (Admin Only)
- `GET /packages` - List all packages
- `GET /packages/create` - Show create package form
- `POST /packages` - Store new package
- `GET /packages/{package}` - Show package details
- `GET /packages/{package}/edit` - Show edit package form
- `PUT /packages/{package}` - Update package
- `DELETE /packages/{package}` - Delete package

### Subscription Routes (Admin Only)
- `GET /subscriptions` - List all subscriptions
- `GET /subscriptions/create` - Show create subscription form
- `POST /subscriptions` - Store new subscription
- `GET /subscriptions/{subscription}` - Show subscription details
- `GET /subscriptions/{subscription}/edit` - Show edit subscription form
- `PUT /subscriptions/{subscription}` - Update subscription
- `DELETE /subscriptions/{subscription}` - Delete subscription
- `POST /subscriptions/{subscription}/extend` - Extend subscription

## Models

### Package Model
```php
use App\Models\Package;

// Get all active packages
$packages = Package::active()->get();

// Get package subscriptions
$package->subscriptions;
```

### Subscription Model
```php
use App\Models\Subscription;

// Check if subscription is active
$subscription->isActive();

// Check if subscription is expired
$subscription->isExpired();

// Get related user and package
$subscription->user;
$subscription->package;
```

### User Model
```php
use App\Models\User;

// Get user's subscriptions
$user->subscriptions;

// Get user's active subscription
$user->activeSubscription();
```

## Services

### PackageLimitService
The `PackageLimitService` provides methods to check package limits:

```php
use App\Services\PackageLimitService;

$service = new PackageLimitService();

// Check if user can create more tab spaces
$canCreateTabSpace = $service->canCreateTabSpace($user);

// Check if user can create more tournaments in a specific tab space
$canCreateTournament = $service->canCreateTournamentInTabSpace($user, $tabSpaceId);

// Get remaining slots
$remainingTabSpaces = $service->getRemainingTabSpaceSlots($user);
$remainingTournaments = $service->getRemainingTournamentSlotsInTabSpace($user, $tabSpaceId);

// Get all user limits
$limits = $service->getUserLimits($user);
```

## Middleware

### Admin Middleware
The `IsAdmin` middleware ensures only administrators can access certain routes:

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin-only routes
});
```

### Subscription Middleware
The `CheckSubscription` middleware checks if a user has an active subscription:

```php
Route::middleware(['auth', 'subscription'])->group(function () {
    // Routes requiring active subscription
});
```

## Commands

### Update Subscription Statuses
Automatically update expired subscription statuses:

```bash
php artisan subscriptions:update-statuses
```

This command should be run regularly (e.g., daily) via a cron job to keep subscription statuses up to date.

## Usage Examples

### Creating a Package
1. Navigate to `/packages/create` (admin only)
2. Fill in package details:
   - Name: "Professional"
   - Description: "Advanced features for growing organizations"
   - Price: $29.99
   - Max Tab Spaces: 10
   - Max Tournaments per Tab: 5
   - Active: Yes

### Creating a Subscription
1. Navigate to `/subscriptions/create` (admin only)
2. Select a user and package
3. Set start and end dates
4. Set status to "Active"

### Checking Package Limits in Code
```php
use App\Services\PackageLimitService;

$service = new PackageLimitService();
$user = auth()->user();

if ($service->canCreateTabSpace($user)) {
    // Create tab space
} else {
    // Show upgrade message
}

if ($service->canCreateTournamentInTabSpace($user, $tabSpaceId)) {
    // Create tournament in this tab space
} else {
    // Show upgrade message
}
```

## Future Enhancements

When implementing tab spaces and tournaments, update the `PackageLimitService` to use actual counts:

```php
// In PackageLimitService methods, replace:
$currentTabSpacesCount = 0; // $user->tabSpaces()->count();
$currentTournamentsCount = 0; // $user->tournaments()->where('tab_space_id', $tabSpaceId)->count();

// With actual model relationships:
$currentTabSpacesCount = $user->tabSpaces()->count();
$currentTournamentsCount = $user->tournaments()->where('tab_space_id', $tabSpaceId)->count();
```

## Security Notes

- All package and subscription management routes are protected by admin middleware
- Users cannot access subscription management features
- Package limits are enforced through the `PackageLimitService`
- Subscription status is automatically updated via scheduled commands 