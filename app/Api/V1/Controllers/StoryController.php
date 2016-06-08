<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
use App\Story;
use Dingo\Api\Routing\Helpers;

class StoryController extends Controller
{
    use Helpers;

    public function index()
    {
        $user = $this->auth->user();

        $stories = Story::score()
            ->likes($user)
            ->with('author')
            ->orderBy('score', 'DESC')
            ->paginate(25);

        // TODO: Refactor this to somewhere.
        $stories->map(function($story) {
            $story->liked = ($story->likes->count() > 0);
            unset($story->likes);
            return $story;
        });

        return $stories;
    }

    public function show($id)
    {
        return Story::findOrFail($id);
    }
}
