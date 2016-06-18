<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
use App\Story;
use Dingo\Api\Routing\Helpers;

class StoryController extends Controller
{
    use Helpers;

	public function __construct()
	{
		$this->middleware('api-auth', ['only' => ['store']]);
	}

    public function index(Request $request)
    {
		$stories = Story::with('author')
			->withLikes($this->auth->user())
			->orderBy('score', 'DESC')
			->paginate(25);

        return $stories;
    }

    public function show($id)
    {
        $story = Story::with('author')
			->findOrFail($id);

		$story->related = Story::orderByRaw("RAND()")
			->with('author')
			->withLikes($this->auth->user())
			->take(5)
			->get();

		return $story;
    }

	public function store(Request $request)
	{
		$user = app('Dingo\Api\Auth\Auth')->user();

		$story = new Story();
		$story->fill($request->all());
		$story->author()->associate($user);

		return $story;
	}
}
