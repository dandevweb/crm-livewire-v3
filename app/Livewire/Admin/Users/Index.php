<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use App\Models\{Permission, User};
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Livewire\{Component, WithPagination};
use Illuminate\Database\Eloquent\{Builder, Collection};

class Index extends Component
{
    public ?string $search           = null;
    public array $search_permissions = [];
    public bool $search_trash        = false;

    public string $sortDirection = 'asc';
    public string $sortColumnBy  = 'id';

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
    }

    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    #[Computed]
    public function users(): Collection
    {
        $this->validate([
            'search_permissions' => 'exists:permissions,id',
        ]);

        return User::query()
            ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas(
                    'permissions',
                    fn (Builder $q) => $q->whereIn('id', $this->search_permissions)
                )
            )
            ->when(
                $this->search_trash,
                fn (Builder $q) => $q->onlyTrashed() /** @phpstan-ignore-line */
            )
            ->orderBy($this->sortColumnBy, $this->sortDirection)
            ->get();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
            ['key' => 'permissions', 'label' => 'Permissions', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
        ];
    }

    #[Computed]
    public function permissions(): Collection
    {
        return Permission::all();
    }

    public function sortBy(string $column, $direction): void
    {
        $this->sortDirection = $direction;
        $this->sortColumnBy  = $column;
    }
}
