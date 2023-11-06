<?php

namespace App\Livewire;

use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        return <<<'HTML'
            <div>
                <h1>Welcome to Livewire!</h1>
            </div>
            HTML;
    }
}
