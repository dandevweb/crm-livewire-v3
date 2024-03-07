<?php

namespace App\Livewire\Customers;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Customer;
use Illuminate\Contracts\View\View;

class Archive extends Component
{
    public Customer $customer;

    public function render(): View
    {
        return view('livewire.customers.archive');
    }

    #[On('customer::archive')]
    public function confirmAction(int $id): void
    {
        $this->customer = Customer::findOrFail($id);
        $this->archive();
    }

    public function archive(): void
    {
        $this->customer->delete();
    }
}
