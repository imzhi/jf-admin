<?php

namespace Imzhi\JFAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionExtra extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'permission_id';
}
