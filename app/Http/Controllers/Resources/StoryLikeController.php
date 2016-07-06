<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Like;
use App\Story;
use Dingo\Api\Routing\Helpers;

class StoryLikeController extends Controller
{
    use Helpers;

	public function __construct()
	{
		$this->middleware('api.auth');
	}

	public function store($id)
	{
		$user = $this->auth->user();

		return Like::firstOrCreate([
			'user_id'  => $user->id,
			'story_id' => $id,
		]);
	}

	public function destroy($id)
	{
		$user = $this->auth->user();

		$like = Like::where('user_id', $user->id)
					->where('story_id', $id)
					->first();

		if ($like) $like->delete();

		return $this->noContent();
	}
}
