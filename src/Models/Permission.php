<?php

namespace Imzhi\InspiniaAdmin\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
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
