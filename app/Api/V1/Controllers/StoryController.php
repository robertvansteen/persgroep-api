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
        $stories = Story::score()->orderBy('score', 'DESC')->paginate(25);

        return $stories;
    }

    public function show($id)
    {
        return Story::findOrFail($id);
    }
}
