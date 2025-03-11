<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    private UserService $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $process_registration = $this->user_service->register($data);
        if (!$process_registration['success']) {
            return response()
                ->json(
                    ['message' => $process_registration['message']],
                    $process_registration['status_code']
                );
        }

        return response()
            ->json([
                'message' => 'registration successful',
                'token' => $process_registration['token']
            ], 201);
    }

    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();

        $process_login = $this->user_service->login($data);
        if (!$process_login['success']) {
            return response()
                ->json(
                    ['message' => $process_login['message']],
                    $process_login['status_code']
                );
        }

        return response()
            ->json([
                'message' => 'login successful',
                'token' => $process_login['token']
            ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'logout successful'
        ], 200);
    }
}
