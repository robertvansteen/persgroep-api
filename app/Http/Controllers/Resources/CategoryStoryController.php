<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Story;
use App\Category;
use Dingo\Api\Routing\Helpers;

class CategoryStoryController extends Controller
{
    use Helpers;

	public function index(Request $request, $categoryId)
	{
		$category = Category::findOrFail($categoryId);

		$stories = $category
			->stories()
			->wherePivot('category_id', $categoryId)
			->popular()
			->withLikes()
			->with('categories')
			->with('author')
			->paginate($request->input('limit') ?: 25);

		return $stories;
	}
}
