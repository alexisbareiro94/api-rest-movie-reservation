<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $data) {
        $email = $data['email'] ?? null;
        $nick = $data['nick'] ?? null;
        $password = $data['password'];

        $user = User::where('email', $email)->orWhere('nickname', $nick)->first();

        if(!$user){
            throw new \Exception('nickname o email no existen');
        }

        if(!Hash::check($password, $user->password)){
            throw new \Exception('password incorrecto');
        }

        if($email == null){
            $credentials = [
                'nickname' => $nick,
                'password' => $password,
            ];
        }else{
            $credentials = [
                'email' => $email,
                'password' => $password,
            ];
        }

        if(!Auth::attempt($credentials)){
            throw new \Exception('algo salio mal en el attempt');
        };  
        
        $user = Auth::user();
        if($user->role == 'admin'){
            $token = $user->createToken('authToken', ['create', 'edit', 'destroy'])->plainTextToken;
        }else{
            $token = $user->createToken('authToken')->plainTextToken;
        }
        
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
