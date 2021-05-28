<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Database\Factories;
use App\Http\Middleware;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth')->except(['register', 'login']);

    }

    /**
     * @OA\Post(
     *     path="/src/public/auth/register",
     *     operationId="/src/public/auth/register",
     *     tags={"Register User"},
     * 
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *       @OA\Property(property="name", type="string", format="string", example="Ando"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="string", example="AndoAndo"),
     *    ),
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Authors",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */

    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
        return $this->respondWithToken($token);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
    /**
     * @OA\Post(
     *     path="/src/public/auth/login",
     *     operationId="/src/public/auth/login",
     *     tags={"Login"},
     * 
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *         required={"email","password"},
     *         @OA\Property(property="email", type="string", format="eail", example="user1@mail.com"),
     *         @OA\Property(property="password", type="string", format="string", example="AndoAndo"),
     *         ),
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Authors",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */



    public function login(Request $request)
    {

        /* $this->validate($request, [
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);*/

        $credentials = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    /**
     * @OA\Get(
     *     path="/src/public/auth/me",
     *     operationId="/src/public/auth/me",
     *     tags={"me"},
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Returns Authors",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */

    public function me()
    {
        return response()->json(auth()->user());
    }
    /**
     * @OA\Post(
     *     path="/src/public/auth/logout",
     *     operationId="/src/public/auth/logout",
     *     tags={"logout"},
     *       security={{"bearerAuth":{}}},
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Returns Authors",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *    
     *     security={ {"bearer": {}} },
     * )
     */


    public function logout()
    {

        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
