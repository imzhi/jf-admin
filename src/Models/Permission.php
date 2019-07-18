<?php

namespace Imzhi\JFAdmin\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
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

    public function permissionExtra()
    {
        return $this->hasOne(PermissionExtra::class);
    }
}
