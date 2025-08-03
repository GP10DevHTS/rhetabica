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
     public ?Tabspace $editingTabspace = null;

    public function edit(Tabspace $tabspace)
    {
        $this->editingTabspace = $tabspace;
        $this->name = $tabspace->name;
        $this->context = $tabspace->context ?? '';
        Flux::modal('edit-tabspace-modal')->show();
    }

    public function update()
    {
        if (!Auth::check() || !$this->editingTabspace) {
            return abort(403);
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tabspaces')->where('user_id', Auth::id())->ignore($this->editingTabspace->id)],
            'context' => ['sometimes', 'nullable', 'max:1000'],
        ]);

        $this->editingTabspace->update([
            'name' => $this->name,
            'context' => $this->context,
        ]);

        Flux::modals()->close();
        $this->reset('name', 'context', 'editingTabspace');
    }

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
        $tabspaces = collect();

        if (Auth::check()) {
            $tabspaces = Auth::user()
                ->tabspaces()
                ->latest() // Orders by created_at descending
                ->get();
        }

        return view('livewire.tabspaces.index', [
            'tabspaces' => $tabspaces,
        ]);
    }
}
