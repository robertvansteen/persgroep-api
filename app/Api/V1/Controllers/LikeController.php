<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Like;
use Dingo\Api\Routing\Helpers;

class LikeController extends Controller
{
    use Helpers;

    public function store(Request $request)
    {
        return Like::create($request->all());
    }
}
