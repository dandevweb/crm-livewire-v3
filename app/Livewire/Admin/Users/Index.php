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
                    fn (Builder $q) => $q->where('id', $this->search_permissions)
                )
            )
            ->get();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ];
    }

    #[Computed]
    public function permissions(): Collection
    {
        return Permission::all();
    }
}
