<?php

namespace App\Livewire\Opportunities;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Illuminate\View\View;
use App\Models\Opportunity;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

/**
 * @property-read Collection|Opportunity[] $opportunities
 * @property-read Collection|Opportunity[] $opens
 * @property-read Collection|Opportunity[] $wons
 * @property-read Collection|Opportunity[] $losts
 */
class Board extends Component
{
    public function render(): View
    {
        return view('livewire.opportunities.board');
    }

    #[Computed]
    public function opportunities(): Collection
    {
        return Opportunity::query()
            ->orderByRaw("field(status, 'open', 'won', 'lost')")
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function opens(): Collection
    {
        return $this->opportunities->where('status', 'open');
    }

    #[Computed]
    public function wons(): Collection
    {
        return $this->opportunities->where('status', '=', 'won');
    }

    #[Computed]
    public function losts(): Collection
    {
        return $this->opportunities->where('status', 'lost');
    }

    public function updateOpportunities($data): void
    {
        $order = $this->getItemsInOrder($data);
        $this->updateStatuses($order);
        $this->updateSortOrders($order);
    }

    private function getItemsInOrder($data): \Illuminate\Support\Collection
    {
        $order = collect();

        foreach ($data as $group) {
            $order->push(
                collect($group['items'])
                    ->map(fn ($item) => $item['value'])
                    ->join(',')
            );
        }

        return $order;
    }

    private function updateStatuses(\Illuminate\Support\Collection $collection): void
    {
        foreach(['open', 'won', 'lost'] as $status) {
            $this->updateStatus($status, $collection);
        }
    }

    private function updateStatus(string $status, \Illuminate\Support\Collection $collection): void
    {
        $id = match ($status) {
            'open'  => 0,
            'won'   => 1,
            'lost'  => 2,
            default => null
        };

        $list = $collection[$id];
        $ids  = explode(',', $list);

        if (filled($list)) {
            DB::table('opportunities')->whereIn('id', $ids)->update(['status' => $status]);
        }
    }

    private function updateSortOrders(\Illuminate\Support\Collection $collection): void
    {
        $sortOrder = $collection->filter(fn ($f) => filled($f))->join(',');

        DB::table('opportunities')->update(['sort_order' => DB::raw("FIELD(id, $sortOrder)")]);
    }
}
