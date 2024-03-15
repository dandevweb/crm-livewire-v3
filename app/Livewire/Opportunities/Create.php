<?php

namespace App\Livewire\Opportunities;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;

class Create extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.opportunities.create');
    }

    #[On('opportunity::create')]
    public function open(): void
    {
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->form->create();

        $this->modal = false;
        $this->dispatch('opportunity::reload')->to('opportunities.index');
    }
}
