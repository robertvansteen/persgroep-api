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
		$query = Assignment::query();
		$user = app('Dingo\Api\Auth\Auth')->user();

		if ($request->has('status')) {
			$query->where('status', $request->status);
		}

		if ($user) {
			$query->with('users');
		}

		$assignments = $query->get();

		if ($user) {
			$assignments = $assignments->map(function ($assignment) use ($user) {
				$pivot = $assignment->users->find($user->id);
				$assignment->subscribe_status = ($pivot)
					? $pivot->pivot->status
					: null;
				return $assignment;
			});
		}

		return $assignments;
	}

	public function show($id)
	{
		$user = app('Dingo\Api\Auth\Auth')->user();
		$assignment = Assignment::with('users')->findOrFail($id);
		$pivot = $user ? $assignment->users->find($user->id) : null;

		if (!$user || !$pivot) {
			$assignment->subscribe_status = null;
			return $assignment;
		}

		$assignment->subscribe_status = $pivot->pivot->status;
		return $assignment;
	}

	public function subscribe(Request $request, $id)
	{
		$user = app('Dingo\Api\Auth\Auth')->user();
		$assignment = Assignment::findOrFail($id);
		$assignment->users()->save($user);

		return $this->created();
	}
}
