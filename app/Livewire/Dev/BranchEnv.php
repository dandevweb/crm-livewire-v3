<?php

namespace App\Livewire\Dev;

use Illuminate\Support\Facades\Process;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read string $branch
 * @property-read string $env
 */
class BranchEnv extends Component
{
    public function render(): string
    {
        return <<<'blade'
            <div class="flex items-center gap-2">
                <x-badge :value="$this->branch" />
                <x-badge :value="$this->env" />
            </div>

        blade;
    }

    #[Computed]
    public function env(): string
    {
        return app()->environment();
    }

    #[Computed]
    public function branch(): string
    {
        $process = Process::run('git branch --show-current');

        return trim($process->output());
    }
}
