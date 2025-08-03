<?php

namespace App\Livewire\Tabspaces;

use Flux\Flux;
use Livewire\Component;
use App\Models\Tabspace;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Services\PackageLimitService;

class Index extends Component
{

     public string $name = '';
     public string $context = '';

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
            'context' => ['sometimes', 'nullable', 'max:1000'],
        ]);

        Tabspace::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'context' => $this->context,
        ]);

        Flux::modals()->close();

        $this->reset();

    }



    public function render()
    {

        return view('livewire.tabspaces.index',[
            'tabspaces' => Auth::user()
        ->tabspaces()
        ->latest() // Orders by created_at descending
        ->get(),
        ]);
    }
}
