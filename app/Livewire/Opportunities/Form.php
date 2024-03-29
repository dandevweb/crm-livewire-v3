<?php

namespace App\Livewire\Opportunities;

use App\Models\{Customer, Opportunity};
use Livewire\Form as BaseForm;
use Livewire\Attributes\Validate;
use Illuminate\Database\Eloquent\Collection;

class Form extends BaseForm
{
    public ?Opportunity $opportunity = null;

    #[Validate(['required', 'min:3', 'max:255'])]
    public string $title = '';

    #[Validate(['required', 'in:open,won,lost'])]
    public string $status = 'open';

    #[Validate(['required'])]
    public ?string $amount = null;

    #[Validate(['required', 'exists:customers,id'])]
    public ?int $customer_id = null;

    public Collection|array $customers = [];


    public function setOpportunity(Opportunity $opportunity): void
    {
        $this->opportunity = $opportunity;

        $this->customer_id = $opportunity->customer_id;
        $this->title       = $opportunity->title;
        $this->status      = $opportunity->status;
        $this->amount      = $opportunity->amount ?? 0;

        $this->searchCustomers();
    }

    public function create(): void
    {
        $this->validate();

        Opportunity::create([
            'customer_id' => $this->customer_id,
            'title'       => $this->title,
            'status'      => $this->status,
            'amount'      => $this->amount,
        ]);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $this->opportunity->customer_id = $this->customer_id;
        $this->opportunity->title       = $this->title;
        $this->opportunity->status      = $this->status;
        /** @phpstan-ignore-next-line */
        $this->opportunity->amount = $this->amount ?? 0;

        $this->opportunity->update();
    }

    public function searchCustomers(string $value = ''): void
    {
        $this->customers = Customer::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->when(
                filled($this->customer_id),
                fn (Collection $customers) => $customers->merge(
                    Customer::query()
                        ->whereId($this->customer_id)
                        ->get(['id', 'name'])
                )
            )
            ->merge(
                Customer::query()
                    ->whereId($this->customer_id)->get(['id', 'name'])
            );
    }
}
