<?php

namespace Tonysm\TurboLaravelTestHelpers;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tonysm\TurboLaravelTestHelpers\Skeleton\SkeletonClass
 */
class TurboLaravelTestHelpersFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'turbo-laravel-test-helpers';
    }
}
