<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use Livewire\Component;
use Livewire\Attributes\On;

class Impersonate extends Component
{
    public function render()
    {
        return <<<'HTML'
        <div></div>
        HTML;
    }

    #[On('user::impersonation')]
    public function impersonate(int $userId): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        session()->put('impersonator', user()->id);
        session()->put('impersonate', $userId);

        $this->redirectRoute('dashboard');
    }
}
