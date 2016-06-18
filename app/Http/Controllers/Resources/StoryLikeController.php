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

	public function store($id)
	{
		return Like::create([
			'user_id'  => 1,
			'story_id' => $id,
		]);
	}

	public function destroy($id)
	{
		return Like::where('user_id', 1)
					->where('story_id', $id)
					->delete();
	}
}
