<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use Livewire\Component;
use Carbon\Carbon;

class Show extends Component
{
    public Subscription $subscription;
    public $days = 30;

    public function mount(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function render()
    {
        return view('livewire.subscriptions.show')
            ->layout('components.layouts.app', ['title' => 'Subscription Details']);
    }

    public function extendSubscription()
    {
        $this->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $newEndDate = Carbon::parse($this->subscription->end_date)->addDays($this->days);
        $this->subscription->update(['end_date' => $newEndDate]);

        session()->flash('success', "Subscription extended by {$this->days} days.");
    }

    public function deleteSubscription()
    {
        $this->subscription->delete();
        session()->flash('success', 'Subscription deleted successfully.');
        return $this->redirect(route('subscriptions.index'));
    }
} 