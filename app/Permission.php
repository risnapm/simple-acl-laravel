<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name','uri'
    ];
    /*
    * Method untuk yang mendefinisikan relasi antara model user dan model Role
    */
    public function getPermissionObject()
    {
        return $this->belongsToMany(Permission::class)->using(RolePermission::class);
    }
}
