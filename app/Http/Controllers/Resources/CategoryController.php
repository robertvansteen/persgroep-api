<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
use App\Category;
use Dingo\Api\Routing\Helpers;

class CategoriesController extends Controller
{
    use Helpers;

    public function index(Request $request)
    {
		return Category::all();
    }

	public function show($id)
	{
		return Category::findOrFail($id)
			->with('stories')
			->popular()
			->withLikes()
			->remember(60)
			->paginate($request->input('limit') ?: 25);
	}
}
