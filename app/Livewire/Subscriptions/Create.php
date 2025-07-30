<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Package;
use Livewire\Component;
use Carbon\Carbon;

class Create extends Component
{
    public $user_id = '';
    public $package_id = '';
    public $start_date;
    public $end_date;
    public $status = 'active';

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'package_id' => 'required|exists:packages,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'status' => 'required|in:active,inactive,expired',
    ];

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d\TH:i');
        $this->end_date = now()->addYear()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $users = User::orderBy('name')->get();
        $packages = Package::active()->orderBy('name')->get();
        
        return view('livewire.subscriptions.create', compact('users', 'packages'))
            ->layout('components.layouts.app', ['title' => 'Create Subscription']);
    }

    public function save()
    {
        $this->validate();

        // Deactivate any existing active subscriptions for this user
        if ($this->status === 'active') {
            Subscription::where('user_id', $this->user_id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        Subscription::create([
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Subscription created successfully.');
        return $this->redirect(route('subscriptions.index'));
    }
} 