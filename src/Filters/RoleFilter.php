<?php

namespace Cyaoz94\LaravelUtilities\Filters;

use Cyaoz94\LaravelUtilities\Filters\QueryFilter;

class RoleFilter extends QueryFilter
{
    public function name($value)
    {
        return parent::like('name', $value);
    }

    public function createdAtFrom($value)
    {
        return parent::ge('created_at', $value);
    }

    public function createdAtTo($value)
    {
        return parent::le('created_at', $value);
    }
}
