<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Assignment;
use Dingo\Api\Routing\Helpers;

class UserController extends Controller
{
    use Helpers;

	protected $user;

	public function __construct()
	{
		$this->middleware('api.auth');
	}

	public function index()
	{
		return app('Dingo\Api\Auth\Auth')->user();
	}

	public function assignments(Request $requeset)
	{
		$user = app('Dingo\Api\Auth\Auth')->user();
		return $user->assignments;
	}
}
