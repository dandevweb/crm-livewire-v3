<?php

namespace Database\Seeders;

use App\Models\{Customer, Opportunity};
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run(): void
    {
        for($i = 0; $i < 20; $i++) {
            Opportunity::factory()->create([
                'customer_id' => Customer::inRandomOrder()->first()->id,
            ]);
        }
    }
}
