<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserService
{
    public function register(array $data)
    {
        try {
            $user = User::where('email', '=', $data['email'])->first();
            if ($user) {
                throw new \Exception('user already exist');
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return ['success' => true, 'token' => $token];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'status_code' => 422];
        } catch (\Throwable $th) {
            return ['suceces' => false, 'message' => 'something error', 'status_code' => 500];
        }
    }

    public function login(array $data)
    {
        try {
            $user = User::where('email', '=', $data['email'])->first();
            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw new \Exception('email or password is invalid');
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return ['success' => true, 'token' => $token];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'status_code' => 422];
        } catch (\Throwable $th) {
            return ['suceces' => false, 'message' => 'something error', 'status_code' => 500];
        }
    }
}
