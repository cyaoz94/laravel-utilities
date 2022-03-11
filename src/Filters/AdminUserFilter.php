<?php

namespace Cyaoz94\LaravelUtilities\Filters;

class AdminUserFilter extends QueryFilter
{
    public function name($value)
    {
        return parent::like('name', $value);
    }

    public function username($value)
    {
        return parent::like('username', $value);
    }

    public function email($value)
    {
        return parent::like('email', $value);
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
