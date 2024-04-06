<?php

namespace App\Livewire\Opportunities;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Illuminate\View\View;
use App\Models\Opportunity;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

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

    public function updateOpportunities($data): void
    {
        $order = collect();

        foreach ($data as $group) {
            $order->push(
                collect($group['items'])
                    ->map(fn ($item) => $item['value'])
                    ->join(',')
            );
        }

        $open = explode(',', $order[0] ?? '');
        $won  = explode(',', $order[1] ?? '');
        $lost = explode(',', $order[2] ?? '');

        $sortOrder = $order->join(',');

        DB::table('opportunities')->whereIn('id', $open)->update(['status' => 'open']);
        DB::table('opportunities')->whereIn('id', $won)->update(['status' => 'won']);
        DB::table('opportunities')->whereIn('id', $lost)->update(['status' => 'lost']);
        DB::table('opportunities')->update(['sort_order' => DB::raw("FIELD(id, $sortOrder)")]);

    }
}
