<?php

namespace App\Livewire\Tabspaces;

use App\Models\Tabspace;
use App\Services\PackageLimitService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public function save()
    {
        $packageLimitService = app(PackageLimitService::class);

        if (!Auth::check()) {
            return abort(403);
        }

        if ($packageLimitService->hasReachedTabspaceLimit(Auth::user())) {
            session()->flash('limit-reached', 'You have reached the maximum number of tabspaces allowed for your subscription.');
            return;
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tabspaces')->where('user_id', Auth::id())],
        ]);

        Tabspace::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
        ]);

        return redirect('/tabspaces');
    }

    public function render()
    {
        return view('livewire.tabspaces.create')
            ->layout('components.layouts.app');
    }
}
