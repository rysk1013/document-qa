<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ){}

    public function register(RegisterRequest $request)
    {
        $this->authService->storeUser(
            $request->name,
            $request->email,
            $request->password,
        );

        return response()->json([
            'message' => 'User registered successfully.',
        ], Response::HTTP_CREATED);
    }
}
