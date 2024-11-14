<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\RequestHandlerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $requestHandler;

    public function __construct(RequestHandlerService $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }
    //
    public function register(RegisterRequest $request)
    {
        return $this->requestHandler->asyncHandler(function () use ($request): JsonResponse {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token
            ]);

        });

    }

    public function login(LoginRequest $request)
    {
        return $this->requestHandler->asyncHandler(function () use ($request): JsonResponse {
            $credentials = $request->only('email', 'password');

            if(!Auth::attempt($credentials)) {
                throw new ErrorResponse('Invalid credentials', 401);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new ErrorResponse('Invalid credentials', 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ], 201);
        });
    }
}
