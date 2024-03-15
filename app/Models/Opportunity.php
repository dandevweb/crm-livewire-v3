<?php

namespace App\Models;

use App\Traits\Models\HasSearch;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Opportunity extends Model
{
    use HasFactory;
    use HasSearch;
    use SoftDeletes;

    protected function amount(): Attribute
    {
        return new Attribute(
            fn ($value) => $value ? $value / 100 : 0,
            fn ($value) => $value ? $value * 100 : 0
        );
    }
}
