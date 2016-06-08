<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use JWTAuth;
use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticationController extends Controller
{
    use Helpers;

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
}
