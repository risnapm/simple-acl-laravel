<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Api\Controller;
use Validator;
use App\Transformers\SkeletonTransformer;
use App\Transformers\PermissionTransformer;
use App\Permission;

class PermissionController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'uri' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(401)->setStatusMessage($validator->errors())->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }

        try{
            $input = $request->all();
            $permission = Permission::create($input);

            return $this->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem($permission, new PermissionTransformer());

        } catch (QueryException $exception) {

            if ($exception->getCode() == 23000) {
                $message = 'Permission ' . $request->get('uri') . ' already exist.';
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
