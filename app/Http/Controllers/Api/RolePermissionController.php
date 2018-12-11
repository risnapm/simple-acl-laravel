<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Api\Controller;
use Validator;
use App\RolePermission;

class RolePermissionController extends Controller
{
    public function add(Request $request){

        $validator = Validator::make($request->all(), [
            'permission_id' => 'required',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(401)->setStatusMessage($validator->errors())->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }

        try{
            $input = $request->all();
            $rolePermission = RolePermission::create($input);

//            return $this->setMeta([
//                'code' => $this->getStatusCode(),
//                'message' => $this->getStatusMessage()
//            ])->respondWithItem($userRole, new SkeletonTransformer());

            return response()->json([
                'code'    => 400,
                'data' => $rolePermission
            ]);

        }catch (QueryException $exception) {

            if ($exception->getCode() == 23000) {
                $message = 'Role was already have that permission';
            } else {
                $message = $exception->getMessage();
            }

            return response()->json([
                'code'    => 400,
                'message' => $message
            ]);
//            return $this->setStatusCode(400)->setStatusMessage($message)->setMeta([
//                'code' => $this->getStatusCode(),
//                'message' => $this->getStatusMessage()
//            ])->respondWithItem([], new SkeletonTransformer());
        }
    }
}
