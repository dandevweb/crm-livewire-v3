<?php

namespace App\Livewire\Customers;

use Livewire\Attributes\{On, Validate};
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Create extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.create');
    }

    #[On('customer::create')]
    public function open(): void
    {
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function save(): void
    {
        $this->form->create();
    }
}
