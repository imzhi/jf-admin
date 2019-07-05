<?php

namespace Imzhi\InspiniaAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class InspiniaAdmin.
 */
class InspiniaAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Imzhi\InspiniaAdmin\InspiniaAdmin::class;
    }
}
