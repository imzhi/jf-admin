<?php

namespace Imzhi\JFAdmin\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\Access\Gate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use SoftDeletes;
    use HasRoles;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'login_time', 'deleted_at',
    ];

    // 状态，0-禁用，1-启用
    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;

    public static function statusRels()
    {
        return [
            static::STATUS_DISABLE => '禁用',
            static::STATUS_ENABLE => '启用',
        ];
    }

    /**
     * Determine if the entity has any one of the given ability.
     *
     * @param  string  $ability
     * @param  array|mixed  $arguments
     * @return bool
     */
    public function any($ability, $arguments = [])
    {
        return app(Gate::class)->forUser($this)->any($ability, $arguments);
    }
}
