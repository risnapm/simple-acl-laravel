<?php

namespace App\Http\Controllers\Api;

use App\Role;
use App\Transformers\RoleTransformer;
use App\Transformers\SkeletonTransformer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Api\Controller;
use Validator;

class RoleController extends Controller
{
    public function add(Request $request){

        $validator = Validator::make($request->all(), [
            'role_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(401)->setStatusMessage($validator->errors())->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }

        try{
            $input = $request->all();
            $role = Role::create($input);

            return $this->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem($role, new RoleTransformer());

        }catch (QueryException $exception) {

            if ($exception->getCode() == 23000) {
                $message = 'Role ' . $request->get('role_name') . ' already exist.';
            } else {
                $message = $exception->getMessage();
            }

            return $this->setStatusCode(400)->setStatusMessage($message)->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }


    }
}
