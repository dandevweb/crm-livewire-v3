<?php

namespace App\Livewire\Opportunities;

use Livewire\Component;
use Illuminate\View\View;
use App\Models\Opportunity;
use Livewire\Attributes\Computed;

class Board extends Component
{
    public function render(): View
    {
        return view('livewire.opportunities.board');
    }

    #[Computed]
    public function opportunities()
    {
        return Opportunity::query()
            ->get();
    }
}
