<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(
        protected AuthRepository $authRepository,
        protected AuthService $authService,
    ) {}

    /**
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'email' => 'required|email|unique:users,email',
                'name' => 'required',
                'nick' => 'required|unique:users,nickname',
                'password' => 'required|min:8'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ]);
        }
        try {
            $data = $validator->validated();
            $user = $this->authRepository->register($data);
            return response()->json([
                'message' => 'usuario registrado con éxito',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes',
            'nick' => 'sometimes',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->messages(),
            ]);
        }
        try{
            $data = $validator->validated();
            $res = $this->authService->login($data);
            return response()->json([
                'message' => 'usuario loggeado correctamente',
                'user' => $res['user'],
                'token' => $res['token'],
            ]);
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'deslogeado correctamente'
        ]);
    }

    public function info(Request $request){

    }
}
