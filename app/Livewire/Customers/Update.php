<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;

class Update extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.update');
    }

    #[On('customer::update')]
    public function load(int $id): void
    {
        $customer = Customer::find($id);
        $this->form->setCustomer($customer);
        $this->form->resetErrorBag();
        $this->modal = true;
    }


    public function save(): void
    {
        $this->form->update();

        $this->modal = false;
        $this->dispatch('customer::reload')->to('customers.index');
    }
}
