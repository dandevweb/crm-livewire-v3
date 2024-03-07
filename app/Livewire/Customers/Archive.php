<?php

namespace App\Livewire\Customers;

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

    public function archive(): void
    {
        $this->customer->delete();
    }
}
