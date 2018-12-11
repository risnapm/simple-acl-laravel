<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Api\Controller;
use Validator;
use App\UserRole;
use App\Transformers\SkeletonTransformer;

class UserRoleController extends Controller
{
    public function add(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
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
            $userRole = UserRole::create($input);

//            return $this->setMeta([
//                'code' => $this->getStatusCode(),
//                'message' => $this->getStatusMessage()
//            ])->respondWithItem($userRole, new SkeletonTransformer());

            return response()->json([
                'code'    => 200,
                'data' => $userRole
            ]);

        }catch (QueryException $exception) {

            if ($exception->getCode() == 23000) {
                $message = 'User was already have that role';
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
