<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_name'
    ];
    /*
    * Method untuk yang mendefinisikan relasi antara model user dan model Role
    */
    public function getUserObject()
    {
        return $this->belongsToMany(User::class)->using(UserRole::class);
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }
}
