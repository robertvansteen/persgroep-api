<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Config;
use JWTAuth;
use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use Helpers;

	public function __construct()
	{
		$this->middleware('api.auth', ['only' => ['me']]);
	}

    /**
     * Authenticate the user.
     *
     * @param  Request $request
     * @return Response
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

	public function refresh(Request $request)
	{
		$token = JWTAuth::getToken();

		if (!$token) {
            return response()->json(['error' => 'token not provided'], 401);
		}

		try {
			$token = JWTAuth::refresh($token);
		} catch(TokenInvalidException $e) {
            return response()->json(['error' => 'invalid token'], 401);
		}

		return response()->json(compact('token'));
	}

	public function me()
	{
		$user = app('Dingo\Api\Auth\Auth')->user();
		return $user;
	}
}
