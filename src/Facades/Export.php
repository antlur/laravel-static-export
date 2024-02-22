<?php

namespace Antlur\Export\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Antlur\Export\Export
 */
class Export extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Antlur\Export\Export::class;
    }
}
