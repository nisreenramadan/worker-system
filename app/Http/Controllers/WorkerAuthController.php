<?php
namespace App\Http\Controllers;

use Validator;
use App\Models\Admin;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WorkerStoreRequest;
use App\Services\WorkerService\WorkerAuthService\WorkerLoginService;
use App\Services\WorkerService\WorkerAuthService\WorkerRegisterService;

class WorkerAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
    	// $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }
        // if (! $token = auth()->guard("worker")->attempt($validator->validated())) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        // return $this->createNewToken($token);
        return (new WorkerLoginService())->login($request);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(WorkerStoreRequest $request) {
        return (new WorkerRegisterService())->register($request);
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|between:2,100',
        //     'email' => 'required|string|email|max:100|unique:admins',
        //     'password' => 'required|string|min:6',
        //     'phone' => 'required|string|max:17',
        //     'photo' => 'required|image|mimes:jpg,png,jpeg',
        //     'location' => 'required|string|min:6',

        // ]);
        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(), 400);
        // }
        // $worker = Worker::create(array_merge(
        //             $validator->validated(),
        //             [
        //                 'password' => bcrypt($request->password),
        //                 'photo' => $request->file('photo')->store('workers'),
        //             ]
        //         ));
        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $worker
        // ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard("worker")->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->guard("worker")->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard("worker")->user()
        ]);
    }
}
