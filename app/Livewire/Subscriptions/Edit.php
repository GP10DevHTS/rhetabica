<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Package;
use Livewire\Component;

class Edit extends Component
{
    public Subscription $subscription;
    public $user_id;
    public $package_id;
    public $start_date;
    public $end_date;
    public $status;

    protected function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,expired',
        ];
    }

    public function mount(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->user_id = $subscription->user_id;
        $this->package_id = $subscription->package_id;
        $this->start_date = $subscription->start_date->format('Y-m-d\TH:i');
        $this->end_date = $subscription->end_date->format('Y-m-d\TH:i');
        $this->status = $subscription->status;
    }

    public function render()
    {
        $users = User::orderBy('name')->get();
        $packages = Package::active()->orderBy('name')->get();
        
        return view('livewire.subscriptions.edit', compact('users', 'packages'))
            ->layout('components.layouts.app', ['title' => 'Edit Subscription']);
    }

    public function save()
    {
        $this->validate();

        // If making this subscription active, deactivate others for the same user
        if ($this->status === 'active') {
            Subscription::where('user_id', $this->user_id)
                ->where('id', '!=', $this->subscription->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $this->subscription->update([
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Subscription updated successfully.');
        return $this->redirect(route('subscriptions.index'));
    }
} 