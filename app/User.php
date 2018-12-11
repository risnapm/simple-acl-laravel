<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
    * Method untuk yang mendefinisikan relasi antara model user dan model Role
    */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    /*
    * Method untuk menambahkan role (hak akses) baru pada user
    */
    public function putRole($role)
    {
        if (is_string($role))
        {
            $role = Role::whereRoleName($role)->first();
        }
        return $this->roles()->attach($role);
    }
    /*
    * Method untuk menghapus role (hak akses) pada user
    */
    public function forgetRole($role)
    {
        if (is_string($role))
        {
            $role = Role::whereRoleName($role)->first();
        }
        return $this->roles()->detach($role);
    }
    /*
    * Method untuk mengecek apakah user yang sedang login punya hak akses untuk mengakses page sesuai rolenya
    */
    public function hasRole($roleName)
    {
        foreach ($this->roles as $role)
        {
            if ($role->role_name === $roleName) return true;
        }
        return false;
    }

    public function hasPermission($permissionUri){

        foreach ($this->roles as $role)
        {
            $rl = Role::select('permissions.uri')
                ->join('role_permissions','role_permissions.role_id','=','roles.id')
                ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                ->where('roles.id','=',$role->id)->get();

            foreach($rl as $r){
                if($r->uri == $permissionUri){
                    return true;
                    break;
                }
            }
        }

        return false;
    }


}
