<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Assignment;
use Dingo\Api\Routing\Helpers;

class AssignmentController extends Controller
{
    use Helpers;

	public function index(Request $request)
	{
		$status = $request->status ?: 'open';

		$assignments = Assignment::where('status', $status)
			->paginate(25);

		return $assignments;
	}

	public function subscribe(Request $request, $id)
	{
		$user = app('Dingo\Api\Auth\Auth')->user();
		$assignment = Assignment::findOrFail($id);
		$assignment->users()->save($user);

		return $this->created();
	}
}
