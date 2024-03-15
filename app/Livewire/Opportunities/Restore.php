<?php

namespace App\Livewire\Opportunities;

use Livewire\Component;
use App\Models\Opportunity;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;

class Restore extends Component
{
    public Opportunity $opportunity;
    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.opportunities.restore');
    }

    #[On('opportunity::restore')]
    public function confirmAction(int $id): void
    {
        $this->opportunity = Opportunity::onlyTrashed()->findOrFail($id);
        $this->modal       = true;
    }

    public function restore(): void
    {
        $this->opportunity->restore();
        $this->modal = false;
        $this->dispatch('opportunity::reload')->to('opportunities.index');
    }
}
