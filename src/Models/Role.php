<?php

namespace Imzhi\JFAdmin\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
