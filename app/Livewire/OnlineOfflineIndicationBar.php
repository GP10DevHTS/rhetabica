<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class OnlineOfflineIndicationBar extends Component
{
    public string $quote = '';

    protected function getInspirationQuote(): string
    {
        Artisan::call('inspire');
        return trim(Artisan::output());
    }


    public function render()
    {
        $this->quote = $this->getInspirationQuote();

        return view('livewire.online-offline-indication-bar');
    }
}
