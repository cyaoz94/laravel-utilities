<?php


namespace Cyaoz94\LaravelUtilities\Filters;


trait Filterable
{
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
