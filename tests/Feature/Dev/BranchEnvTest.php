<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Dev\BranchEnv;

use function Pest\Laravel\{actingAs, get};

it('should show a current branch in the page', function () {
    Process::fake([
        'git branch --show-current' => Process::result('master'),
    ]);

    Livewire::test(BranchEnv::class)
        ->assertSet('branch', 'master')
        ->assertSee('master');

    Process::assertRan('git branch --show-current');

});

it('should not load livewire component on production environment', function () {
    $user = User::factory()->create();

    app()->detectEnvironment(fn () => 'production');

    actingAs($user);

    get(route('dashboard'))
        ->assertDontSeeLivewire('dev.branch-env');

    get(route('login'))
        ->assertDontSeeLivewire('dev.branch-env');
});

it('should load livewire component on non production environment', function () {
    $user = User::factory()->create();

    app()->detectEnvironment(fn () => 'local');

    actingAs($user);

    get(route('dashboard'))
        ->assertSeeLivewire('dev.branch-env');

    get(route('login'))
        ->assertSeeLivewire('dev.branch-env');
});
