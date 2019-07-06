<?php

namespace Imzhi\JFAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class JFAdmin.
 */
class JFAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Imzhi\JFAdmin\JFAdmin::class;
    }
}
