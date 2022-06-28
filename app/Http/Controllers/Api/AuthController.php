<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API untuk authentikasi"
 * )
 *
 */
class AuthController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/login",
     *     summary="Authentikasi user",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *      @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"email", "password"},
     *         @OA\Property(
     *           description="Email",
     *           property="email",
     *           type="string",
     *           example="admin@admin.com"
     *         ),
     *         @OA\Property(
     *           description="Password Login",
     *           property="password",
     *           type="string",
     *           example="123456"
     *         ),
     *       )
     *     )
     *   ),
     *   @OA\Response(response=200, description="Success create"),
     *   @OA\Response(response=422, description="Unprocessable Content or validation error"),
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token', [$user->role->name])->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Melihat current user ",
     *     description="Melihat current user",
     *     tags={"auth"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): \Illuminate\Http\JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function admin()
    {
        return response()->json(['adminOnly']);
    }
}
