<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
use App\Category;
use Dingo\Api\Routing\Helpers;

class CategoryController extends Controller
{
    use Helpers;

    /**
     * Show all the categories.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
		return Category::all();
    }

    /**
     * Show a specific category.
     *
     * @param  String $id
     * @return Response
     */
	public function show($id)
	{
		return Category::findOrFail($id)
			->with('stories')
			->popular()
			->paginate($request->input('limit') ?: 25);
	}
}
