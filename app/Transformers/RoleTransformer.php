<?php

namespace App\Transformers;

use App\Role;
use Carbon\Carbon;

class RoleTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    protected $availableIncludes = [];

    public function transform(Role $role) {

        return [
            'id' => $role->id,
            'name' =>  $role->role_name,
            'updated_at' => Carbon::parse($role->updated_at)->toDateTimeString(),
            'created_at' => Carbon::parse($role->created_at)->toDateTimeString()
        ];
    }
}