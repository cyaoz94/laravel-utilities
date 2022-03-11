<?php

namespace Cyaoz94\LaravelUtilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;
use Cyaoz94\LaravelUtilities\Filters\Filterable;

class Role extends SpatieRole
{
    use HasFactory, Filterable;
}
