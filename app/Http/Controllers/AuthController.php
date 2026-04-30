<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\UserRepositoryInterface;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    protected $userRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verify2fa']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user = $this->userRepository->create(array_merge(
            $validator->validated(),
            [
                'password' => Hash::make($request->password),
                'google2fa_secret' => $secret,
            ]
        ));

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'two_factor_secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = Auth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // If user has 2FA enabled, we need to verify it
        if ($user->google2fa_secret) {
            return response()->json([
                'message' => '2FA verification required',
                'temp_token' => $token, // In a real app, this should be a limited token
                'requires_2fa' => true
            ]);
        }

        return $this->createNewToken($token);
    }

    /**
     * Verify 2FA code.
     */
    public function verify2fa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'one_time_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Set the token to authenticate the user temporarily
        Auth::setToken($request->token);
        $user = Auth::authenticate();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            return $this->createNewToken($request->token);
        }

        return response()->json(['error' => 'Invalid 2FA code'], 401);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
}
