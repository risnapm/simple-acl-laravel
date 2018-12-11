<?php

namespace App\Http\Controllers\Api;

use App\Role;
use App\Transformers\SkeletonTransformer;
use App\Helpers\GenerateCodeHelper;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Api\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return $this->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem($success, new SkeletonTransformer());
        }
        else{
            return $this->setStatusCode(401)->setStatusMessage('Unauthorised')->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->setStatusCode(401)->setStatusMessage($validator->errors())->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }

        try{

            $input               = $request->all();
            $input[ 'password' ] = bcrypt($input[ 'password' ]);
            $input['code'] = GenerateCodeHelper::getUuid();
            $user = User::create($input);

            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['user'] =  $user;
            return response()->json(['meta'=>[
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ],'data'=>$success], $this->getStatusCode());
//        return $this->setMeta([
//            'code' => $this->getStatusCode(),
//            'message' => $this->getStatusMessage()
//        ])->respondWithItem($success, new SkeletonTransformer());

        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                $message = 'Email ' . $request->get('email') . ' already registered.';
            } else {
                $message = $exception->getMessage();
            }

            return $this->setStatusCode(400)->setStatusMessage($message)->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }

    }

    public function logout(){
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return $this->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }else{
            return $this->setStatusCode(500)->setStatusMessage('Something went wrong')->setMeta([
                'code' => $this->getStatusCode(),
                'message' => $this->getStatusMessage()
            ])->respondWithItem([], new SkeletonTransformer());
        }
    }

    public function details(Request $request)
    {
        if (Auth::check()) {
            if($request->user()->hasPermission($request->path())){
                $user = Auth::user();

                return response()->json(['meta'=>[
                    'code' => $this->getStatusCode(),
                    'message' => $this->getStatusMessage()
                ],
                    'data'=>$user], $this->getStatusCode());
//            return $this->setMeta([
//                'code' => $this->getStatusCode(),
//                'message' => $this->getStatusMessage()
//            ])->respondWithItem($user, new UserTransformer());
            }else{
                return $this->setStatusCode(500)->setStatusMessage('Unauthorised')->setMeta([
                    'code' => $this->getStatusCode(),
                    'message' => $this->getStatusMessage()
                ])->respondWithItem([], new SkeletonTransformer());
            }
        }else{
            return response()->json([
                'code'  => 401,
                'error' => 'Unauthorised'
            ], 401);
//            return $this->setStatusCode(500)->setStatusMessage('Unauthorised')->setMeta([
//                'code' => $this->getStatusCode(),
//                'message' => $this->getStatusMessage()
//            ])->respondWithItem([], new SkeletonTransformer());
        }

    }


    public function index(Request $request){

        $paginate = $request->get('per_page') ? $request->get('per_page') : 10;

        $user = User::paginate($paginate);
        if($user){
            return $this->setMeta([
                'total' => $user->total(),
                'per_page' => $user->perPage(),
                'current_page' => $user->currentPage(),
                'total_page' => $user->lastPage()
            ])->respondWithCollection($user, new UserTransformer());
        }else{
            return $this->setStatusCode(404)->respondWithArray([
                'message' => 'Not Found'
            ]);
        }

    }
}
