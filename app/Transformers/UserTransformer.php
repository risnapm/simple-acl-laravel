<?php

namespace App\Transformers;

use App\User;
use Carbon\Carbon;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    protected $availableIncludes = [];

    public function transform(User $user) {

        return [
            'id' => $user->id,
            'code' =>$user->code,
            'email' =>  $user->email,
            'name' => $user->name,
            'updated_at' => Carbon::parse($user->updated_at)->toDateTimeString(),
            'created_at' => Carbon::parse($user->created_at)->toDateTimeString()
        ];
    }
}