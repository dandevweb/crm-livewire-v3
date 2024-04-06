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
            ->orderByRaw("
                case
                    when status = 'open' then 1
                    when status = 'won' then 2
                    when status = 'lost' then 3
                end
            ")
            ->get();
    }

    public function updateOpportunities($data): void
    {
        dd($data);
    }
}
