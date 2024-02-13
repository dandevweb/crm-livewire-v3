<?php

namespace App\Livewire\Admin\Users;

use Livewire\Attributes\On;
use Livewire\Component;

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
        session()->put('impersonator', user()->id);
        session()->put('impersonate', $userId);

        $this->redirectRoute('dashboard');
    }
}
