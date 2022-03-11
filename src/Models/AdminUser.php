<?php

namespace Cyaoz94\LaravelUtilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Cyaoz94\LaravelUtilities\Filters\Filterable;

class AdminUser extends Authenticatable
{
    use HasFactory, HasRoles, Filterable;

    protected $guard_name = 'admin';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
