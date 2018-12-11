<?php

namespace App\Transformers;

use App\Permission;
use Carbon\Carbon;

class PermissionTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    protected $availableIncludes = [];

    public function transform(Permission $permission) {

        return [
            'id' => $permission->id,
            'name' =>  $permission->name,
            'uri' =>  $permission->uri,
            'updated_at' => Carbon::parse($permission->updated_at)->toDateTimeString(),
            'created_at' => Carbon::parse($permission->created_at)->toDateTimeString()
        ];
    }
}