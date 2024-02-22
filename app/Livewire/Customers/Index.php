<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\{Component, WithPagination};
use Livewire\Attributes\Computed;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('livewire.customers.index');
    }

    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        return Customer::paginate();
    }
}
