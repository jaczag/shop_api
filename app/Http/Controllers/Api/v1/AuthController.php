<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\auth\LoginRequest;
use App\Http\Requests\v1\auth\RegisterRequest;
use App\Http\Resources\v1\UserResource;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param UserService $service
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, UserService $service): JsonResponse
    {
        $data = $request->validated();

        try {
            $service->assignData($data)->saveUser();
            return $this->successResponse(null, __('messages.Account created successfully'));
        } catch (Exception $exception) {
            reportError($exception);
        }

        return $this->errorResponse(__('messages.Something went wrong'));
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            if (!Auth::attempt($data)) {
                return $this->errorResponse(
                    __('auth.failed'),
                    Response::HTTP_UNAUTHORIZED,
                );
            }

            $token = optional(Auth::user())->createToken('auth')->plainTextToken;

            return $this->successResponse([
                'user' => UserResource::make(Auth::user()),
                'token' => $token,
            ]);
        } catch (Exception $e) {
            reportError($e);
        }
        return $this->errorResponse(__('messages.Something went wrong'));
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            optional(Auth::user())->tokens()->delete();
            return $this->successResponse();
        } catch (Exception $exception) {
            reportError($exception);
        }

        return $this->errorResponse(
            __('messages.Something went wrong.')
        );
    }

}
