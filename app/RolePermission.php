<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'permission_id', 'role_id'
    ];
}
