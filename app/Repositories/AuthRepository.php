<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function register(array $data) :User{
        return User::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'nickname' => $data['nick'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);
    }
}
