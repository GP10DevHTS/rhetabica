<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateSubscriptionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subscription statuses based on expiration dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating subscription statuses...');

        // Find expired subscriptions that are still marked as active
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();

        $count = $expiredSubscriptions->count();

        if ($count > 0) {
            foreach ($expiredSubscriptions as $subscription) {
                $subscription->update(['status' => 'expired']);
                $this->line("Updated subscription {$subscription->id} for user {$subscription->user->name} to expired");
            }
            
            $this->info("Updated {$count} expired subscriptions.");
        } else {
            $this->info('No expired subscriptions found.');
        }

        return Command::SUCCESS;
    }
} 