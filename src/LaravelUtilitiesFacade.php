<?php

namespace Cyaoz94\LaravelUtilities;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cyaoz94\LaravelUtilities\Skeleton\SkeletonClass
 */
class LaravelUtilitiesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-utilities';
    }
}
