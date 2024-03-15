<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Opportunity::factory(300)->create();
    }
}
