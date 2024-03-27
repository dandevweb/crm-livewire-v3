<?php

namespace App\Livewire\Opportunities;

use Livewire\Component;
use App\Models\{Customer, Opportunity};
use Livewire\Attributes\{On};
use Illuminate\Contracts\View\View;

class Update extends Component
{
    public Form $form;

    public bool $modal = false;


    public function render(): View
    {
        return view('livewire.opportunities.update');
    }

    #[On('opportunity::update')]
    public function load(int $id): void
    {
        $opportunity = Opportunity::find($id);
        $this->form->setOpportunity($opportunity);

        $this->form->resetErrorBag();
        $this->search();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->form->update();

        $this->modal = false;
        $this->dispatch('opportunity::reload')->to('opportunities.index');
    }

    public function search(string $value = ''): void
    {
        $this->form->searchCustomers($value);
    }

}
